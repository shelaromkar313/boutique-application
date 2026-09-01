@extends('layouts.app')

@section('title', 'Estilo HQ Console — Administrator Authentication')

@section('content')
<div x-data="{
    emailInput: 'admin@estilo.com',
    passwordInput: 'Admin@123',
    remember: true,
    fillDemo() {
        this.emailInput = 'admin@estilo.com';
        this.passwordInput = 'Admin@123';
    }
}"
class="min-h-[85vh] flex items-center justify-center py-8 px-3 sm:px-6 bg-gradient-to-b from-[var(--color-champagne-light)]/40 to-[var(--color-offwhite)]">

    <div class="w-full max-w-md space-y-5">

        {{-- Brand / top badge --}}
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 border border-[var(--color-bisque)] text-[var(--color-ebony)] text-[10px] font-sans font-bold uppercase tracking-[0.18em] shadow-sm backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Restricted Terminal • HQ Console</span>
            </div>

            <div class="flex items-center justify-center gap-2">
                <div class="w-10 h-10 overflow-hidden rounded-full border border-[var(--color-bisque)] bg-white p-1 flex items-center justify-center shadow-sm">
                    <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-full h-full object-cover rounded-full" />
                </div>
                <span class="font-serif text-2xl font-bold tracking-[0.2em] text-[var(--color-ebony)]">ESTILO WEAR</span>
            </div>
            <p class="text-xs font-sans text-[var(--color-ebony)]/65">Authorized Personnel Only — Unauthorized access is prohibited and logged.</p>
        </div>

        {{-- Security Card --}}
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl border border-[var(--color-bisque)] shadow-[0_20px_50px_rgba(26,24,24,0.12)] overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-[var(--color-rose-antique)] via-[#f5c79b] to-[var(--color-rose-deep)]"></div>

            <div class="p-6 sm:p-8 space-y-5">

                {{-- Header --}}
                <div class="space-y-1 text-center">
                    <h2 class="font-serif text-2xl font-bold text-[var(--color-ebony)] flex items-center justify-center gap-2">
                        <span>Admin Verification</span>
                        <svg class="w-5 h-5 text-[var(--color-rose-antique)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Enter master administrator credentials to access store controls.</p>
                </div>

                {{-- Alert Messages --}}
                @if(session('info'))
                <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-900 text-xs font-sans flex items-start gap-2.5">
                    <span class="text-base">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-sans flex items-start gap-2.5">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-sans space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <span>⚠️</span> Authentication Error:
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-700">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Admin Login Form --}}
                <form action="/estilo-hq-console/login" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">
                            Administrator Email
                        </label>
                        <div class="relative">
                            <input type="email" name="email" x-model="emailInput" required placeholder="admin@estilo.com"
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-mono text-[var(--color-ebony)] placeholder-[var(--color-ebony)]/45 focus:outline-none focus:border-[var(--color-rose-antique)] focus:ring-1 focus:ring-[var(--color-rose-antique)] transition-colors" />
                            <span class="absolute right-3.5 top-3 text-[var(--color-ebony)]/45 text-sm">✉️</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">
                                Master Password
                            </label>
                            <span class="text-[10px] font-mono text-[var(--color-rose-antique)]">Estilo Encrypted</span>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" x-model="passwordInput" required placeholder="••••••••"
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-mono text-[var(--color-ebony)] placeholder-[var(--color-ebony)]/45 focus:outline-none focus:border-[var(--color-rose-antique)] focus:ring-1 focus:ring-[var(--color-rose-antique)] transition-colors" />
                            <span class="absolute right-3.5 top-3 text-[var(--color-ebony)]/45 text-sm">🔑</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)]">
                            <input type="checkbox" name="remember" x-model="remember" class="accent-[var(--color-rose-antique)] rounded" />
                            <span>Maintain active session</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest shadow-lg shadow-black/10 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Authenticate & Enter HQ Console</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                {{-- Demo Auto-Fill Trigger --}}
                <div class="pt-3 border-t border-[var(--color-bisque)] flex items-center justify-between">
                    <span class="text-[10.5px] font-sans text-[var(--color-ebony)]/55">Presentation Quick Access:</span>
                    <button type="button" @click="fillDemo()"
                            class="text-[11px] font-sans font-bold text-[var(--color-ebony)] hover:text-[var(--color-ebony)] bg-[var(--color-champagne-light)] hover:bg-[#f6dfd1] border border-[var(--color-bisque)] px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                        <span>⚡ Fill Admin Keys</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Footer notes --}}
        <div class="text-center space-y-1">
            <p class="text-[11px] font-mono text-[var(--color-ebony)]/55">
                🔒 Security Protocol: <span class="text-[var(--color-ebony)]/70">Strict Role Validation (role=admin)</span>
            </p>
            <a href="/" class="inline-block text-[11px] font-sans text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] transition-colors">
                ← Return to Public Storefront
            </a>
        </div>

    </div>

</div>
@endsection
