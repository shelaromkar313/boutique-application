@extends('layouts.app')

@section('title', 'Sign In | Estilo Wear Portal')

@section('content')
<div x-data="{
    activeRole: '{{ request('role', 'customer') }}'
}"
class="min-h-[85vh] flex items-center justify-center py-8 px-3 sm:px-6 bg-gradient-to-b from-[var(--color-champagne-light)]/40 to-[var(--color-offwhite)]">

    <div class="w-full max-w-md">

        {{-- Branding above card --}}
        <div class="text-center mb-5">
            <a href="/" class="inline-flex items-center gap-2 text-[var(--color-ebony)] group">
                <div class="w-9 h-9 overflow-hidden rounded-full border border-[var(--color-bisque)] group-hover:border-[var(--color-rose-antique)] transition-colors">
                    <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-[160%] -mt-[20%] mix-blend-multiply" />
                </div>
                <span class="font-serif text-xl font-bold tracking-widest text-[var(--color-ebony)]">ESTILO WEAR</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)] shadow-xl overflow-hidden">

            {{-- Coloured role header bar --}}
            <div class="h-1 w-full transition-colors duration-300"
                 :class="{
                     'bg-[var(--color-rose-antique)]': activeRole === 'customer',
                     'bg-amber-600': activeRole === 'sales_associate',
                     'bg-slate-800': activeRole === 'admin'
                 }"></div>

            <div class="p-5 sm:p-8 space-y-5">

                {{-- Heading --}}
                <div class="text-center space-y-1">
                    <h1 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Welcome Back!</h1>
                    <p class="text-[11px] sm:text-xs font-sans text-[var(--color-ebony)]/60">Sign in to your customer account and continue shopping.</p>
                </div>

                {{-- Session Alerts & Errors --}}
                @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded-xl text-xs font-sans font-bold text-center">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs font-sans font-bold text-center">
                    {{ session('error') }}
                </div>
                @endif
                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs font-sans space-y-1">
                    <p class="font-bold flex items-center gap-1">⚠️ Login Failed:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- ── 3. Login Form ── --}}
                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" :value="activeRole" />
                    <input type="hidden" name="auth_type" value="email" />

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Password</label>
                                <a href="#" class="text-[11px] font-sans text-[var(--color-rose-antique)] hover:underline">Forgot password?</a>
                            </div>
                            <input type="password" name="password" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer text-[11px] font-sans text-[var(--color-ebony)]/70">
                            <input type="checkbox" name="remember" class="accent-[var(--color-rose-antique)]" />
                            Remember me on this device
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-[var(--color-ebony)] text-white font-sans text-[11px] sm:text-xs font-bold uppercase tracking-widest shadow-lg transition-all hover:opacity-90 active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <span>→</span>
                    </button>
                </form>

                {{-- Registration links --}}
                <div class="text-center text-[11px] sm:text-xs font-sans text-[var(--color-ebony)]/70 space-y-1.5 pt-4 border-t border-[var(--color-bisque)]/60">
                    <div>
                        New customer? <a href="/register" class="text-[var(--color-rose-antique)] font-bold hover:underline">Create Account</a>
                    </div>
                    <div>
                        Want to shop without an account? <a href="/checkout" class="text-[var(--color-thyme)] font-bold hover:underline">Guest Checkout →</a>
                    </div>
                </div>

            </div>{{-- end card body --}}
        </div>{{-- end card --}}

    </div>

</div>

{{-- Inline style for xs breakpoint (< 480px) --}}
<style>
    @media (min-width: 480px) {
        .xs\:flex-row { flex-direction: row; }
        .xs\:w-auto   { width: auto; }
    }
</style>

@endsection
