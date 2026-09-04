@extends('layouts.app')

@section('title', 'Estilo HQ Console — Administrator Authentication')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-10 px-4 sm:px-6 bg-gradient-to-b from-[var(--color-ebony)]/5 via-[var(--color-offwhite)] to-[var(--color-champagne-light)]/40">

    <div class="w-full max-w-md space-y-6">

        {{-- Branding above card --}}
        <div class="text-center space-y-2">
            <a href="/" class="inline-flex items-center gap-2 text-[var(--color-ebony)] group">
                <div class="w-10 h-10 overflow-hidden rounded-full border-2 border-amber-300 shadow-md">
                    <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-[160%] -mt-[20%] mix-blend-multiply" />
                </div>
                <span class="font-serif text-2xl font-bold tracking-widest text-[var(--color-ebony)]">ESTILO HQ</span>
            </a>
            <p class="text-[11px] font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)]/60">Executive Management Console</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-2xl overflow-hidden">

            {{-- Top Accent Bar --}}
            <div class="h-1.5 w-full bg-[var(--color-ebony)]"></div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- Header --}}
                <div class="text-center space-y-1.5">
                    <div class="inline-flex items-center gap-1.5 bg-slate-900 text-amber-200 text-[10px] font-sans font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-inner">
                        <span>🛡️</span> Super Administrator Portal
                    </div>
                    <h1 class="font-serif text-2xl font-bold text-[var(--color-ebony)] pt-1">Administrator Sign In</h1>
                    <p class="text-xs font-sans text-gray-500">Provide verified administrative credentials to access boutique controls, inventory, and order dispatching.</p>
                </div>

                {{-- Flash and Error Alerts --}}
                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-4 py-2.5 rounded-xl text-xs font-sans font-bold flex items-center gap-2">
                    <span>✨</span> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-rose-50 border border-rose-300 text-rose-900 px-4 py-2.5 rounded-xl text-xs font-sans space-y-1">
                    @foreach($errors->all() as $error)
                    <div class="flex items-center gap-1.5">
                        <span>⚠️</span> {{ $error }}
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Login Form --}}
                <form action="/estilo-hq-console/login" method="POST" class="space-y-4" id="adminLoginForm">
                    @csrf

                    <div>
                        <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">
                            Administrator Email (Login ID)
                        </label>
                        <div class="relative">
                            <input type="email" name="email" id="adminEmailInput"
                                   value="{{ old('email') }}"
                                   placeholder=" [EMAIL_ADDRESS] " required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans font-semibold focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="adminPasswordInput"
                                   value=""
                                   placeholder="••••••••" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs font-sans text-gray-600">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="rounded text-[var(--color-ebony)] focus:ring-0 accent-[var(--color-ebony)]" />
                            <span>Stay authenticated on this terminal</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg transition-all hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>🔐 Access Management Console</span>
                        <span>→</span>
                    </button>
                </form>


                {{-- Return to Storefront --}}
                <div class="text-center pt-2">
                    <a href="/shop" class="text-xs font-sans font-semibold text-gray-500 hover:text-[var(--color-ebony)] transition-colors">
                        ← Return to Customer Storefront
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
