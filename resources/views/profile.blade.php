@extends('layouts.app')

@section('title', 'My Profile & Orders | ESTILO WEAR')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-12" x-data="{ activeTab: 'orders' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Flash Alerts --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-emerald-600 text-base">✨</span>
                <span class="text-xs font-sans font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">✕</button>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-300 text-rose-900 px-5 py-3.5 rounded-2xl space-y-1 shadow-sm">
            <div class="flex items-center gap-2 text-xs font-bold font-sans">
                <span>⚠️</span> Please correct the following errors:
            </div>
            <ul class="list-disc pl-6 text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-serif font-bold text-2xl sm:text-3xl shadow-inner ring-4 ring-pink-100">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-pink-50 border border-pink-200 text-[var(--color-rose-antique)] text-[10px] font-sans font-bold uppercase tracking-wider">
                        <span>✨ Estilo Atelier Member</span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">{{ $user->name }}</h1>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">{{ $user->email }} • {{ $user->phone ?? 'Phone not set' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="/shop" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-md">
                    🛍️ Explore Collections
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-xs font-sans font-bold uppercase tracking-wider px-4 py-2.5 rounded-full transition-colors">
                        🚪 Log Out
                    </button>
                </form>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-3 border-b border-[var(--color-bisque)] pb-2">
            <button @click="activeTab = 'orders'"
                    :class="activeTab === 'orders' ? 'bg-[var(--color-ebony)] text-white shadow-sm' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-5 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all">
                Order History ({{ $orders->count() }})
            </button>
            <button @click="activeTab = 'edit'"
                    :class="activeTab === 'edit' ? 'bg-[var(--color-ebony)] text-white shadow-sm' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-5 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all">
                Account Settings
            </button>
        </div>

        {{-- TAB 1: ORDER HISTORY --}}
        <div x-show="activeTab === 'orders'" class="space-y-6">
            @forelse($orders as $ord)
            @php
                $items = is_string($ord->items) ? json_decode($ord->items, true) : ($ord->items ?? []);
            @endphp
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[var(--color-bisque)]/60">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500">Order Number</span>
                        <h3 class="font-mono text-base font-bold text-[var(--color-ebony)]">{{ $ord->order_no }}</h3>
                        <span class="text-xs text-gray-500">Placed on {{ $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : 'Recently' }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500 block">Total Amount</span>
                            <span class="font-serif text-lg font-bold text-[var(--color-ebony)]">₹{{ number_format($ord->total, 0) }}</span>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $ord->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $ord->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $ord->status === 'confirmed' || $ord->status === 'paid' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $ord->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}
                            {{ $ord->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            ● {{ $ord->status }}
                        </span>
                    </div>
                </div>

                {{-- Garments in this order --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-sans font-bold uppercase tracking-wider text-gray-500">Ordered Couture</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($items as $item)
                        <div class="flex items-center gap-3 p-3 bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)]/60">
                            <div class="w-12 h-14 bg-gray-200 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                <img src="{{ $item['image'] ?? '/storage/hero/hero-main.jpg' }}" alt="{{ $item['name'] ?? 'Garment' }}" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-serif text-xs font-bold text-[var(--color-ebony)] truncate">{{ $item['name'] ?? 'Couture Item' }}</h5>
                                <span class="text-[10px] text-gray-500 font-sans block">Size: {{ $item['selectedSize'] ?? $item['size'] ?? 'Standard' }} • Color: {{ $item['selectedColor'] ?? $item['color'] ?? 'Standard' }} • Qty: {{ $item['quantity'] ?? $item['qty'] ?? 1 }}</span>
                                <span class="font-serif font-bold text-xs text-[var(--color-rose-antique)]">₹{{ number_format($item['price'] ?? 0, 0) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Delivery Destination --}}
                <div class="pt-3 border-t border-[var(--color-bisque)]/40 flex flex-col sm:flex-row justify-between text-xs text-gray-600 gap-2">
                    <div>
                        <span class="font-bold text-[var(--color-ebony)]">Delivery Address:</span>
                        {{ $ord->address }}, {{ $ord->city }}, {{ $ord->state }} - {{ $ord->pincode }}
                    </div>
                    <div>
                        <span class="font-bold text-[var(--color-ebony)]">Payment:</span> {{ strtoupper($ord->payment_method ?? 'Online Pre-paid') }}
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-12 text-center shadow-sm space-y-4">
                <div class="text-4xl">🛍️</div>
                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">No Orders Yet</h3>
                <p class="text-xs font-sans text-gray-500 max-w-md mx-auto">You haven't placed any couture orders yet. Discover our artisanal Chikankari, Anarkalis, and Luxury Silk Sarees.</p>
                <a href="/shop" class="inline-block bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-full transition-all shadow-md">
                    Start Shopping
                </a>
            </div>
            @endforelse
        </div>

        {{-- TAB 2: ACCOUNT SETTINGS --}}
        <div x-show="activeTab === 'edit'" class="space-y-6" style="display: none;">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6 max-w-2xl">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Update Profile Details</h3>
                    <p class="text-xs font-sans text-gray-500">Edit your name, contact phone number, and security password.</p>
                </div>

                <form action="/profile" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Email Address (Read-only)</label>
                        <input type="email" value="{{ $user->email }}" readonly class="w-full bg-gray-100 border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans text-gray-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Contact Mobile Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="9876543210" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">New Password (Leave empty to keep current)</label>
                        <input type="password" name="password" placeholder="••••••••" minlength="6" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
