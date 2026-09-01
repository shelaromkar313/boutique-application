@extends('layouts.app')

@section('title', 'Estilo HQ — Administrator Profile & Security')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Top Navigation Breadcrumb --}}
        <div class="flex items-center justify-between">
            <a href="/estilo-hq-console" class="inline-flex items-center gap-2 text-xs font-sans font-bold text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
            <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-300">
                🔒 Security & Credentials
            </span>
        </div>

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

        {{-- Main Two-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left Column: Profile Card & Session Summary --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Profile Identity Card --}}
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 text-center space-y-4">
                    <div class="w-20 h-20 rounded-full bg-[var(--color-ebony)] text-amber-200 text-3xl font-bold flex items-center justify-center mx-auto shadow-lg border-2 border-amber-300/40">
                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-serif text-xl font-bold text-[var(--color-ebony)]">{{ $admin->name ?? 'Administrator' }}</h2>
                        <span class="inline-block mt-1 text-[10px] font-sans font-bold uppercase tracking-widest text-emerald-800 bg-emerald-100 px-3 py-0.5 rounded-full border border-emerald-300">
                            Atelier Executive Admin
                        </span>
                    </div>
                    <div class="pt-3 border-t border-[var(--color-bisque)]/60 text-left text-xs space-y-2 text-gray-600">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Email:</span>
                            <span class="font-bold text-[var(--color-ebony)]">{{ $admin->email }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Phone:</span>
                            <span class="font-mono font-bold text-[var(--color-ebony)]">{{ $admin->phone ?? '+91 90000 00001' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Status:</span>
                            <span class="font-bold text-emerald-700">● Online & Verified</span>
                        </div>
                    </div>
                </div>

                {{-- Security & Access Badge --}}
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-[var(--color-ebony)] uppercase tracking-wider">
                        <span class="text-base">🛡️</span> Security & Privileges
                    </div>
                    <ul class="text-xs text-gray-600 space-y-2 font-sans">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span> Full Inventory & Stock Management
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span> Order Processing & Dispatch Tracking
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span> Discount Coupons & Ticker Broadcasts
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span> Associate Payouts & Commission Approval
                        </li>
                    </ul>

                    <div class="pt-3 border-t border-[var(--color-bisque)]/60">
                        <a href="/estilo-hq-console" class="w-full bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-xs">
                            <span>◀</span> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Column: Edit Profile & Password Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="border-b border-[var(--color-bisque)]/60 pb-4">
                        <h3 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Edit Administrator Profile</h3>
                        <p class="text-xs font-sans text-gray-500 mt-0.5">Update your display name, contact phone number, and administrative login credentials.</p>
                    </div>

                    <form action="/estilo-hq-console/profile" method="POST" class="space-y-5">
                        @csrf

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans font-bold focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                        </div>

                        {{-- Email & Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Email Address (Login ID) *</label>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                                       class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                            </div>
                            <div>
                                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $admin->phone ?? '9000000001') }}"
                                       class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                            </div>
                        </div>

                        {{-- Change Password --}}
                        <div class="p-4 bg-[var(--color-champagne-light)]/40 rounded-2xl border border-[var(--color-bisque)] space-y-2">
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]">
                                🔑 Change Password (Optional)
                            </label>
                            <input type="password" name="password" placeholder="Leave empty to keep current password unchanged" minlength="6"
                                   class="w-full bg-white border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 shadow-inner" />
                            <span class="text-[10px] text-gray-500 block">Minimum 6 characters. If provided, your login password will update immediately.</span>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center gap-4 pt-4 border-t border-[var(--color-bisque)]/60">
                            <a href="/estilo-hq-console" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-3.5 rounded-xl transition-colors">
                                Return to Console
                            </a>
                            <button type="submit" class="flex-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest py-3.5 px-8 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                                ✨ Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
