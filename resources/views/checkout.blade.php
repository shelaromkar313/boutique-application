@extends('layouts.app')

@section('title', 'Secure Checkout | ESTILO WEAR')

@section('content')

<div class="pb-16 sm:pb-24 pt-4 sm:pt-8" x-data="{
    step: 1,
    paymentMethod: 'upi',
    orderPlaced: false,
    orderId: '',
    
    // Form fields
    name: '',
    email: '',
    phone: '',
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

    submitOrder() {
        if (!this.name || !this.email || !this.phone || !this.address || !this.pincode) {
            $store.shop.showToast('Please fill all required shipping information.');
            return;
        }
        this.orderId = 'EST-' + Math.floor(100000 + Math.random() * 900000);
        this.orderPlaced = true;
        $store.shop.cart = [];
        $store.shop.saveCart();
    }
}">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Step Indicators --}}
        <template x-if="!orderPlaced">
            <div>
                <div class="text-center mb-8">
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Encrypted Checkout</span>
                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Complete Your Boutique Order</h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Form --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- 1. Customer & Shipping Info --}}
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm space-y-5">
                            <div class="flex items-center gap-3 border-b border-[var(--color-bisque)]/40 pb-3">
                                <span class="w-7 h-7 rounded-full bg-[var(--color-ebony)] text-white text-xs font-bold flex items-center justify-center">1</span>
                                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Shipping & Customer Information</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-sans">
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">Full Name *</label>
                                    <input type="text" x-model="name" placeholder="e.g. Radhika Sharma" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">Email Address *</label>
                                    <input type="email" x-model="email" placeholder="radhika@example.com" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">Phone / WhatsApp *</label>
                                    <input type="tel" x-model="phone" placeholder="+91 98765 43210" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">6-Digit PIN Code *</label>
                                    <input type="text" x-model="pincode" maxlength="6" placeholder="e.g. 400001" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required />
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">Street Address / House No. *</label>
                                    <textarea x-model="address" rows="2" placeholder="Apartment, Studio, Landmark, Street" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" required></textarea>
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">City</label>
                                    <input type="text" x-model="city" placeholder="e.g. Mumbai" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" />
                                </div>
                                <div>
                                    <label class="block font-bold text-[var(--color-ebony)] mb-1">State</label>
                                    <input type="text" x-model="state" placeholder="e.g. Maharashtra" class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)]" />
                                </div>
                            </div>
                        </div>

                        {{-- 2. Payment Method --}}
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm space-y-4">
                            <div class="flex items-center gap-3 border-b border-[var(--color-bisque)]/40 pb-3">
                                <span class="w-7 h-7 rounded-full bg-[var(--color-ebony)] text-white text-xs font-bold flex items-center justify-center">2</span>
                                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Select Payment Method</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label :class="paymentMethod === 'upi' ? 'border-[var(--color-rose-antique)] bg-[var(--color-champagne-light)]/60 shadow-sm' : 'border-[var(--color-bisque)]/60 bg-white'" class="flex flex-col items-center p-4 rounded-2xl border-2 cursor-pointer transition-all">
                                    <input type="radio" name="pay" value="upi" x-model="paymentMethod" class="sr-only" />
                                    <span class="text-sm font-bold font-serif text-[var(--color-ebony)]">UPI / QR Code</span>
                                    <span class="text-[10px] font-sans text-[var(--color-ebony)]/60 mt-1">GPay, PhonePe, Paytm</span>
                                </label>

                                <label :class="paymentMethod === 'card' ? 'border-[var(--color-rose-antique)] bg-[var(--color-champagne-light)]/60 shadow-sm' : 'border-[var(--color-bisque)]/60 bg-white'" class="flex flex-col items-center p-4 rounded-2xl border-2 cursor-pointer transition-all">
                                    <input type="radio" name="pay" value="card" x-model="paymentMethod" class="sr-only" />
                                    <span class="text-sm font-bold font-serif text-[var(--color-ebony)]">Cards / Netbanking</span>
                                    <span class="text-[10px] font-sans text-[var(--color-ebony)]/60 mt-1">All Major Indian Banks</span>
                                </label>

                                <label :class="paymentMethod === 'cod' ? 'border-[var(--color-rose-antique)] bg-[var(--color-champagne-light)]/60 shadow-sm' : 'border-[var(--color-bisque)]/60 bg-white'" class="flex flex-col items-center p-4 rounded-2xl border-2 cursor-pointer transition-all">
                                    <input type="radio" name="pay" value="cod" x-model="paymentMethod" class="sr-only" />
                                    <span class="text-sm font-bold font-serif text-[var(--color-ebony)]">Cash on Delivery</span>
                                    <span class="text-[10px] font-sans text-[var(--color-ebony)]/60 mt-1">Pay at doorstep</span>
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Order Summary & Place Order --}}
                    <div class="space-y-6">
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[var(--color-bisque)]/60 shadow-[var(--shadow-floating)] space-y-5">
                            <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)]/60 pb-3 uppercase tracking-wider">Order Summary</h3>

                            {{-- Mini Cart items --}}
                            <div class="max-h-48 overflow-y-auto space-y-3 pr-1">
                                <template x-for="(item, idx) in $store.shop.cart" :key="idx">
                                    <div class="flex items-center gap-3 text-xs font-sans">
                                        <img :src="item.image" :alt="item.name" class="w-10 h-12 object-cover rounded-lg flex-shrink-0" />
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-bold text-[var(--color-ebony)] truncate" x-text="item.name"></h5>
                                            <span class="text-[10px] text-[var(--color-ebony)]/60" x-text="item.color + ' • ' + item.size + ' × ' + item.qty"></span>
                                        </div>
                                        <span class="font-serif font-bold text-[var(--color-ebony)]" x-text="'₹' + (item.price * item.qty).toLocaleString('en-IN')"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Price breakdown --}}
                            <div class="space-y-2 text-xs font-sans border-t border-[var(--color-bisque)]/30 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-[var(--color-ebony)]/70">Subtotal</span>
                                    <span class="font-bold text-[var(--color-ebony)]" x-text="'₹' + $store.shop.cartSubtotal.toLocaleString('en-IN')"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[var(--color-ebony)]/70">Shipping</span>
                                    <span class="text-[var(--color-thyme)] font-bold" x-text="shipping === 0 ? 'FREE' : '₹' + shipping"></span>
                                </div>
                                <div class="flex justify-between text-base font-serif font-bold text-[var(--color-ebony)] pt-2 border-t border-[var(--color-bisque)]">
                                    <span>Total Payable</span>
                                    <span x-text="'₹' + finalTotal.toLocaleString('en-IN')"></span>
                                </div>
                            </div>

                            <button @click="submitOrder()" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full shadow-lg transition-all hover:scale-[1.02]">
                                Place Order Now ✦
                            </button>

                            <div class="text-[10px] font-sans text-[var(--color-ebony)]/60 text-center space-y-1">
                                <p>🔒 256-Bit SSL Encrypted Payment</p>
                                <p>🚚 Dispatched within 24 hours from Jaipur/Varanasi Atelier</p>
                            </div>
                        </div>
                    </div>

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

    </div>

</div>

@endsection
