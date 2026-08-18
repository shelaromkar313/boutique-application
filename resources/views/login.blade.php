@extends('layouts.app')

@section('title', 'Login | ESTILO WEAR')

@section('content')
<div x-data="{ showGooglePrompt: false, testEmail: '' }" class="min-h-[80vh] flex items-center justify-center py-12 px-4 relative">
    <div class="w-full max-w-md relative z-10">
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

            <div class="relative flex items-center justify-center my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[var(--color-bisque)]"></div>
                </div>
                <div class="relative bg-white px-4 text-[10px] font-sans font-bold text-[var(--color-ebony)]/50 uppercase tracking-widest">
                    Or
                </div>
            </div>

            <!-- Owner / Admin Login via Gmail Mockup -->
            <button @click.prevent="showGooglePrompt = true" class="w-full flex items-center justify-center gap-3 bg-white border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-widest py-3.5 rounded-full transition-all shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Sign in with Google
            </button>

            <div class="text-center text-xs font-sans text-[var(--color-ebony)]/60 pt-6 mt-4">
                Don't have an account? <a href="/register" class="text-[var(--color-rose-antique)] font-bold hover:underline">Create Account</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Google Sign-In Mockup Modal -->
    <div x-show="showGooglePrompt" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="showGooglePrompt" x-transition.opacity class="absolute inset-0 bg-[var(--color-ebony)]/60 backdrop-blur-sm" @click="showGooglePrompt = false"></div>
        
        <!-- Modal Content -->
        <div x-show="showGooglePrompt" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-sm bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)]/60">
             
             <div class="text-center mb-6">
                 <svg class="w-8 h-8 mx-auto mb-3" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                 <h2 class="font-sans font-medium text-2xl text-[var(--color-ebony)]">Sign in</h2>
                 <p class="text-sm font-sans text-[var(--color-ebony)]/80 mt-1">to continue to Estilo Wear Admin</p>
             </div>
             
             <div class="space-y-4">
                 <input x-model="testEmail" type="email" placeholder="Email or phone" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3.5 text-base font-sans focus:outline-none focus:border-[#4285F4] focus:ring-1 focus:ring-[#4285F4] transition-colors" />
                 
                 <div class="flex items-center justify-between mt-8 pt-4">
                     <button @click="showGooglePrompt = false" class="text-sm font-sans font-bold text-[#4285F4] hover:text-[#3367D6]">Cancel</button>
                     <button @click="if(testEmail === 'admin@gmail.com') { window.location.href = '/admin' } else { window.location.href = '/' }" class="bg-[#4285F4] hover:bg-[#3367D6] text-white text-sm font-sans font-bold px-6 py-2.5 rounded transition-colors">Next</button>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection
