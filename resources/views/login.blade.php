@extends('layouts.app')

@section('title', 'Login | ESTILO WEAR')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/60 shadow-[var(--shadow-floating)] p-8 sm:p-10 space-y-6">
            <div class="text-center space-y-1">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Welcome Back</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Sign In to Estilo Wear</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">Access your orders, wishlist & exclusive member offers.</p>
            </div>

            <form class="space-y-5">
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" placeholder="ananya@example.com" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Password</label>
                    <input type="password" placeholder="••••••••" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
                </div>
                <div class="flex items-center justify-between text-xs font-sans">
                    <label class="flex items-center gap-2 cursor-pointer text-[var(--color-ebony)]/70">
                        <input type="checkbox" class="accent-[var(--color-rose-antique)]" /> Remember me
                    </label>
                    <a href="#" class="text-[var(--color-rose-antique)] hover:underline font-semibold">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full transition-all shadow-md">
                    Sign In
                </button>
            </form>

            <div class="text-center text-xs font-sans text-[var(--color-ebony)]/60 border-t border-[var(--color-bisque)]/60 pt-6">
                Don't have an account? <a href="#" class="text-[var(--color-rose-antique)] font-bold hover:underline">Create Account</a>
            </div>
        </div>
    </div>
</div>
@endsection
