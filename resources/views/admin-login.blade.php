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
class="min-h-[85vh] flex items-center justify-center py-10 px-4 sm:px-6 bg-gradient-to-b from-slate-950 via-slate-900 to-black text-slate-100">

    <div class="w-full max-w-md space-y-6">

        {{-- Top Security Badge --}}
        <div class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-300 text-xs font-mono font-semibold tracking-wider uppercase shadow-inner">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Restricted Terminal • HQ Console</span>
            </div>
            
            <div class="flex items-center justify-center gap-2">
                <div class="w-10 h-10 overflow-hidden rounded-full border border-amber-400/40 bg-white/10 p-1 flex items-center justify-center">
                    <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-full h-full object-cover rounded-full" />
                </div>
                <span class="font-serif text-2xl font-bold tracking-[0.25em] text-white">ESTILO WEAR</span>
            </div>
            <p class="text-xs font-sans text-slate-400">Authorized Personnel Only — Unauthorized access is prohibited and logged.</p>
        </div>

        {{-- Security Card --}}
        <div class="bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-slate-800 shadow-[0_20px_50px_rgba(0,0,0,0.8)] overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-500 via-rose-500 to-amber-300"></div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- Header --}}
                <div class="space-y-1 text-center sm:text-left">
                    <h2 class="font-serif text-2xl font-bold text-white flex items-center justify-center sm:justify-start gap-2">
                        <span>Admin Verification</span>
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </h2>
                    <p class="text-xs font-sans text-slate-400">Enter master administrator credentials to access store controls.</p>
                </div>

                {{-- Alert Messages --}}
                @if(session('info'))
                <div class="p-3.5 rounded-xl bg-blue-950/60 border border-blue-800/80 text-blue-200 text-xs font-sans flex items-start gap-2.5">
                    <span class="text-base">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800/80 text-emerald-200 text-xs font-sans flex items-start gap-2.5">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/60 border border-rose-800/80 text-rose-200 text-xs font-sans space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <span>⚠️</span> Authentication Error:
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-300">
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
                        <label class="block text-[11px] font-sans font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Administrator Email
                        </label>
                        <div class="relative">
                            <input type="email" name="email" x-model="emailInput" required placeholder="admin@estilo.com"
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-xs font-mono text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors" />
                            <span class="absolute right-3.5 top-3 text-slate-500 text-sm">✉️</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-sans font-bold text-slate-300 uppercase tracking-wider">
                                Master Password
                            </label>
                            <span class="text-[10px] font-mono text-amber-400/80">Estilo Encrypted</span>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" x-model="passwordInput" required placeholder="••••••••"
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-xs font-mono text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors" />
                            <span class="absolute right-3.5 top-3 text-slate-500 text-sm">🔑</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                            <input type="checkbox" name="remember" x-model="remember" class="accent-amber-500 rounded" />
                            <span>Maintain active session</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-sans text-xs font-bold uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Authenticate & Enter HQ Console</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                {{-- Demo Auto-Fill Trigger --}}
                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                    <span class="text-[10.5px] font-sans text-slate-500">Presentation Quick Access:</span>
                    <button type="button" @click="fillDemo()"
                            class="text-[11px] font-mono text-amber-400 hover:text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 px-3 py-1 rounded-lg transition-colors flex items-center gap-1.5">
                        <span>⚡ Fill Admin Keys</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Footer notes --}}
        <div class="text-center space-y-1">
            <p class="text-[11px] font-mono text-slate-500">
                🔒 Security Protocol: <span class="text-slate-400">Strict Role Validation (role=admin)</span>
            </p>
            <a href="/" class="inline-block text-[11px] font-sans text-slate-400 hover:text-white transition-colors">
                ← Return to Public Storefront
            </a>
        </div>

    </div>

</div>
@endsection
