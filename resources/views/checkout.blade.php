@extends('layouts.app')

@section('title', 'Secure Checkout | ESTILO WEAR')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@php
    $refCode = session('referral_code');
    $refAssociate = null;
    if ($refCode) {
        $refAssociate = \App\Models\User::where('referral_code', strtoupper($refCode))->first();
    }
@endphp

<div class="pb-16 sm:pb-24 pt-4 sm:pt-8" x-data="{
    step: 1,
    paymentMethod: 'upi',
    orderPlaced: false,
    orderId: '',
    submitting: false,
    openScannerModal: false,
    
    // Form fields
    name: '{{ auth()->check() ? auth()->user()->name : '' }}',
    email: '{{ auth()->check() ? auth()->user()->email : '' }}',
    phone: '{{ auth()->check() ? auth()->user()->phone : '' }}',
    address: '',
    city: '',
    state: '',
    pincode: '',

    get shipping() {
        return $store.shop.cartSubtotal >= 1999 || $store.shop.cart.length === 0 ? 0 : 199;
    },

    get finalTotal() {
        return $store.shop.cartSubtotal + this.shipping;
    },

    openUPIScanner() {
        const upiUrl = 'upi://pay?pa=estilowear@icici&pn=Estilo%20Wear%20Boutique&am=' + this.finalTotal + '&cu=INR';
        // Trigger UPI payment intent on mobile
        window.location.href = upiUrl;
        // Open scanner modal for desktop view
        this.openScannerModal = true;
        $store.shop.showToast('Launching UPI Payment Scanner... 📲');
    },

    copyUPI() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText('estilowear@icici');
        }
        $store.shop.showToast('Copied UPI ID: estilowear@icici ✨');
    },

    async submitOrder() {
        if (!this.name || !this.email || !this.phone || !this.address || !this.pincode) {
            $store.shop.showToast('Please fill all required shipping information.');
            return;
        }

        if ($store.shop.cart.length === 0) {
            $store.shop.showToast('Your shopping bag is empty.');
            return;
        }

        this.submitting = true;

        try {
            if (this.paymentMethod === 'card' || this.paymentMethod === 'upi') {
                const res = await fetch('/api/payments/create-order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        items: $store.shop.cart,
                        subtotal: $store.shop.cartSubtotal,
                        shipping: this.shipping,
                        discount: $store.shop.discountAmount || 0,
                    })
                });
                
                const orderData = await res.json();

                if (!res.ok || !orderData.success) {
                    alert('Error: ' + (orderData.message || 'Could not start payment.'));
                    this.submitting = false;
                    return;
                }

                var options = {
                    key: '{{ env('RAZORPAY_KEY_ID') }}',
                    amount: orderData.amount,
                    currency: orderData.currency || 'INR',
                    name: 'Estilo Wear Boutique',
                    description: 'Premium Couture Purchase',
                    order_id: orderData.order_id,
                    prefill: {
                        name: this.name,
                        email: this.email,
                        contact: this.phone
                    },
                    theme: { color: '#800020' },
                    handler: async (response) => {
                        const verifyRes = await fetch('/api/payments/verify', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                name: this.name, email: this.email, phone: this.phone,
                                address: this.address, city: this.city, state: this.state, pincode: this.pincode,
                                items: $store.shop.cart, subtotal: $store.shop.cartSubtotal,
                                shipping: this.shipping, discount: $store.shop.discountAmount || 0, total: this.finalTotal,
                                referral_code: '{{ $refCode }}'
                            })
                        });

                        const verifyData = await verifyRes.json();
                        if(verifyRes.ok && verifyData.success) {
                            this.orderId = verifyData.order_id;
                            this.orderPlaced = true;
                            $store.shop.cart = [];
                            $store.shop.saveCart();
                        } else {
                            alert('Payment verification failed! Please contact support.');
                        }
                    }
                };
                
                var rzp1 = new Razorpay(options);
                rzp1.open();
                this.submitting = false;

            } else {
                const res = await fetch('/checkout/place-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        address: this.address,
                        city: this.city || 'Metropolitan',
                        state: this.state || 'India',
                        pincode: this.pincode,
                        payment_method: this.paymentMethod,
                        items: $store.shop.cart,
                        subtotal: $store.shop.cartSubtotal,
                        shipping: this.shipping,
                        discount: $store.shop.discountAmount || 0,
                        total: this.finalTotal,
                        referral_code: '{{ $refCode }}'
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.orderId = data.order_id;
                    this.orderPlaced = true;
                    $store.shop.cart = [];
                    $store.shop.saveCart();
                } else {
                    alert(data.message || 'Unable to place order. Please check details and try again.');
                }
                this.submitting = false;
            }
        } catch(e) {
            console.error('Checkout error:', e);
            alert('A network error occurred. Please try again.');
            this.submitting = false;
        }
    }
}">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Authenticated Customer Status Header --}}
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <div class="text-xs font-sans">
                    <span class="font-bold text-emerald-950">Verified Account: {{ auth()->user()->name }} ({{ auth()->user()->email }})</span><br/>
                    Your order will be linked directly to your profile for 1-click tracking.
                </div>
            </div>
            <a href="/profile" class="text-emerald-700 hover:text-emerald-900 font-bold text-[10px] uppercase tracking-wider bg-white/60 px-3 py-1.5 rounded-full border border-emerald-200">My Orders →</a>
        </div>

        {{-- Referral Partner Notice Banner --}}
        @if($refCode)
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center font-bold text-sm">✦</span>
                <div class="text-xs font-sans">
                    <span class="font-bold">Partner Curated Order:</span> You are shopping through Stylist <strong>{{ $refAssociate->name ?? 'Estilo Associate' }}</strong> (Code: <span class="font-mono font-bold">{{ $refCode }}</span>).
                </div>
            </div>
            <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full">Partner Verified</span>
        </div>
        @endif

        {{-- Empty Bag State Notice --}}
        <template x-if="$store.shop.cart.length === 0 && !orderPlaced">
            <div class="max-w-md mx-auto bg-white p-8 sm:p-10 rounded-3xl border border-[var(--color-bisque)]/60 text-center shadow-sm space-y-4 my-8">
                <div class="text-4xl">🛍️</div>
                <h2 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Your Shopping Bag is Empty</h2>
                <p class="text-xs font-sans text-gray-500">Please add couture garments to your bag before proceeding to checkout.</p>
                <a href="/shop" class="inline-block bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-full transition-all shadow-md">
                    Explore Shop Collections
                </a>
            </div>
        </template>

        <template x-if="$store.shop.cart.length > 0 && !orderPlaced">
            <div class="max-w-6xl mx-auto">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-sans font-bold uppercase tracking-[0.28em] text-[var(--color-rose-antique)]">Checkout</p>
                        <h1 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Place Your Order</h1>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 rounded-full border border-[var(--color-bisque)]/70 bg-white px-3 py-2 text-[10px] font-sans font-semibold text-[var(--color-ebony)]/70 shadow-xs">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        Secure checkout
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[1.45fr_0.9fr] gap-6 lg:gap-8">
                    <div class="space-y-5">
                        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Delivery Details</h3>
                                <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)]/55">Step 1</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-sans">
                                <div class="sm:col-span-1">
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">Full name</label>
                                    <input type="text" x-model="name" placeholder="Enter your name" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">Mobile</label>
                                    <input type="tel" x-model="phone" placeholder="+91 98765 43210" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">Address</label>
                                    <textarea x-model="address" rows="2" placeholder="House no., street, area" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required></textarea>
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">City</label>
                                    <input type="text" x-model="city" placeholder="Mumbai" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">PIN code</label>
                                    <input type="text" x-model="pincode" maxlength="6" placeholder="400001" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1.5">Email</label>
                                    <input type="email" x-model="email" placeholder="you@example.com" class="w-full px-3.5 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Payment</h3>
                                <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)]/55">Step 2</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <button type="button" @click="paymentMethod = 'upi'" :class="paymentMethod === 'upi' ? 'bg-[var(--color-ebony)] text-white' : 'bg-[var(--color-offwhite)] text-[var(--color-ebony)]'" class="rounded-xl py-2.5 text-[11px] font-bold transition-all">UPI</button>
                                <button type="button" @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'bg-[var(--color-ebony)] text-white' : 'bg-[var(--color-offwhite)] text-[var(--color-ebony)]'" class="rounded-xl py-2.5 text-[11px] font-bold transition-all">Card</button>
                                <button type="button" @click="paymentMethod = 'cod'" :class="paymentMethod === 'cod' ? 'bg-[var(--color-ebony)] text-white' : 'bg-[var(--color-offwhite)] text-[var(--color-ebony)]'" class="rounded-xl py-2.5 text-[11px] font-bold transition-all">COD</button>
                            </div>

                            <div class="rounded-2xl border border-[var(--color-bisque)]/70 bg-[var(--color-offwhite)] p-4 text-center" x-show="paymentMethod === 'upi'">
                                <p class="font-bold text-[var(--color-ebony)] text-xs">Pay securely with UPI via Razorpay</p>
                                <p class="mt-1 text-[10px] text-[var(--color-ebony)]/60">You will be securely redirected to Razorpay to scan a QR code or enter your UPI ID.</p>
                            </div>

                            <div class="rounded-2xl border border-[var(--color-bisque)]/70 bg-[var(--color-offwhite)] p-4 text-center" x-show="paymentMethod === 'card'">
                                <p class="font-bold text-[var(--color-ebony)] text-xs">Pay securely with Card via Razorpay</p>
                                <p class="mt-1 text-[10px] text-[var(--color-ebony)]/60">You will be securely redirected to Razorpay to enter your credit or debit card details.</p>
                            </div>

                            <div class="rounded-2xl border border-[var(--color-bisque)]/70 bg-[var(--color-offwhite)] p-4 text-xs font-sans" x-show="paymentMethod === 'cod'">
                                <p class="font-bold text-[var(--color-ebony)]">Cash on Delivery</p>
                                <p class="mt-1 text-[10px] text-[var(--color-ebony)]/60">Pay when your order arrives at your doorstep.</p>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:sticky lg:top-24 self-start">
                        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-[var(--color-bisque)]/60 shadow-[var(--shadow-floating)]">
                            <div class="flex items-center justify-between pb-3 border-b border-[var(--color-bisque)]/60">
                                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Order Summary</h3>
                                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60" x-text="$store.shop.cart.length + ' item(s)'"></span>
                            </div>

                            <div class="space-y-3 py-4">
                                <template x-for="(item, idx) in $store.shop.cart" :key="idx">
                                    <div class="flex items-center gap-3 text-xs font-sans">
                                        <img :src="item.image" :alt="item.name" class="w-14 h-18 object-cover rounded-xl border border-[var(--color-bisque)]/60" />
                                        <div class="min-w-0 flex-1">
                                            <h5 class="font-bold text-[var(--color-ebony)] truncate" x-text="item.name"></h5>
                                            <p class="text-[10px] text-[var(--color-ebony)]/60" x-text="item.color + ' • ' + item.size"></p>
                                            <p class="text-[10px] text-[var(--color-ebony)]/60" x-text="'Qty: ' + item.qty"></p>
                                        </div>
                                        <span class="font-bold text-[var(--color-ebony)]" x-text="'₹' + (item.price * item.qty).toLocaleString('en-IN')"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-2 border-t border-[var(--color-bisque)]/60 pt-4 text-xs font-sans">
                                <div class="flex justify-between text-[var(--color-ebony)]/70">
                                    <span>Subtotal</span>
                                    <span x-text="'₹' + $store.shop.cartSubtotal.toLocaleString('en-IN')"></span>
                                </div>
                                <div class="flex justify-between text-[var(--color-ebony)]/70">
                                    <span>Shipping</span>
                                    <span class="font-bold text-emerald-600" x-text="shipping === 0 ? 'FREE' : '₹' + shipping"></span>
                                </div>
                                <div class="flex justify-between pt-2 text-sm font-serif font-bold text-[var(--color-ebony)]">
                                    <span>Total</span>
                                    <span x-text="'₹' + finalTotal.toLocaleString('en-IN')"></span>
                                </div>
                            </div>

                            <button @click="submitOrder()" class="mt-5 w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-3.5 rounded-full shadow-lg transition-all hover:scale-[1.01]">
                                Place Order
                            </button>

                            <div class="mt-3 text-[10px] font-sans text-[var(--color-ebony)]/60 text-center">
                                <p>🔒 Secure payment</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </template>

        {{-- Order Success Screen --}}
        <template x-if="orderPlaced">
            <div class="max-w-lg mx-auto bg-white p-8 sm:p-12 rounded-3xl border border-[var(--color-bisque)]/60 shadow-2xl text-center space-y-6">
                <div class="w-20 h-20 rounded-full bg-[var(--color-champagne)] text-[var(--color-rose-antique)] flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="space-y-2">
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-widest">Order Confirmed</span>
                    <h2 class="font-serif text-3xl font-bold text-[var(--color-ebony)]">Thank You for Styling with ESTILO WEAR</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/70">
                        Your boutique order <strong class="text-[var(--color-ebony)]" x-text="orderId"></strong> has been received and is being carefully packed in our signature wooden heirloom box.
                    </p>
                </div>

                <div class="p-4 bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)]/40 text-xs font-sans text-left space-y-2">
                    <div class="flex justify-between"><span>Recipient:</span><strong x-text="name"></strong></div>
                    <div class="flex justify-between"><span>Payment Mode:</span><strong class="uppercase" x-text="paymentMethod"></strong></div>
                    <div class="flex justify-between"><span>Estimated Delivery:</span><strong>3-5 Business Days</strong></div>
                </div>

                <div class="pt-2">
                    <a href="/shop" class="inline-block bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest px-8 py-3.5 rounded-full transition-colors">
                        Continue Exploring Collections
                    </a>
                </div>
            </div>
        </template>

        {{-- ENLARGED UPI SCANNER MODAL --}}
        <div x-show="openScannerModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="openScannerModal" x-transition.opacity @click="openScannerModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl z-10 border border-[var(--color-bisque)] text-center space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                    <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">UPI QR Payment Scanner</h3>
                    <button @click="openScannerModal = false" class="text-gray-400 hover:text-gray-600 text-base">✕</button>
                </div>
                <div class="bg-[var(--color-offwhite)] p-4 rounded-2xl border border-[var(--color-bisque)]/80 inline-block shadow-sm">
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent('upi://pay?pa=estilowear@icici&pn=Estilo%20Wear%20Boutique&am=' + finalTotal + '&cu=INR')" alt="Enlarged UPI QR Code" class="w-56 h-56 rounded-xl mx-auto" />
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-[var(--color-ebony)]">Scan with GPay, PhonePe, Paytm, or BHIM</p>
                    <p class="text-[10px] text-gray-500">Total Amount: <span class="font-bold text-emerald-700" x-text="'₹' + finalTotal"></span></p>
                </div>
                <button type="button" @click="openScannerModal = false" class="w-full bg-[var(--color-ebony)] text-white text-xs font-bold uppercase tracking-wider py-3 rounded-xl shadow-md cursor-pointer">
                    Done / Close Scanner
                </button>
            </div>
        </div>

    </div>

</div>

@endsection
