@extends('layouts.app')

@section('title', 'Sign In | Estilo Wear Portal')

@section('content')
<div x-data="{
    activeRole: '{{ request('role', 'customer') }}',
    authMethod: 'phone',
    phoneInput: '9876543212',
    emailInput: 'test@example.com',
    passwordInput: 'Customer@123',
    otpSent: false,
    otpInput: '1234',
    otpTimer: 30,
    timerInterval: null,

    selectRole(role) {
        this.activeRole = role;
        this.otpSent = false;
        if (role === 'admin') {
            this.authMethod = 'email';
            this.emailInput = 'admin@estilo.com';
            this.phoneInput = '9000000001';
            this.passwordInput = 'Admin@123';
        } else if (role === 'sales_associate') {
            this.emailInput = 'associate@estilo.com';
            this.phoneInput = '9876543211';
            this.passwordInput = 'Partner@123';
        } else {
            this.emailInput = 'test@example.com';
            this.phoneInput = '9876543212';
            this.passwordInput = 'Customer@123';
        }
    },

    sendOtp() {
        if (!this.phoneInput || this.phoneInput.length < 10) {
            alert('Please enter a valid 10-digit mobile number.');
            return;
        }
        this.otpSent = true;
        this.otpInput = '1234';
        this.otpTimer = 30;
        clearInterval(this.timerInterval);
        this.timerInterval = setInterval(() => {
            if (this.otpTimer > 0) this.otpTimer--;
            else clearInterval(this.timerInterval);
        }, 1000);
    }
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

                {{-- ── 1. Customer Welcome Badge (No Role Selection) ── --}}
                <div class="py-2 px-3 rounded-xl text-[11px] sm:text-xs font-sans border flex items-center justify-between gap-2 bg-pink-50 border-pink-200 text-pink-900">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-bold">Customer — Orders & Account Hub</span>
                    </div>
                </div>

                {{-- ── 2. Auth Method Toggle ── --}}
                <div class="flex rounded-xl overflow-hidden border border-[var(--color-bisque)] text-[11px] sm:text-xs font-sans font-bold uppercase tracking-wide">
                    <button type="button" @click="authMethod = 'phone'"
                            :class="authMethod === 'phone' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                            class="flex-1 py-2.5 transition-colors flex items-center justify-center gap-1.5">
                        <span>📱</span> Phone OTP
                    </button>
                    <button type="button" @click="authMethod = 'email'"
                            :class="authMethod === 'email' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                            class="flex-1 py-2.5 border-l border-[var(--color-bisque)] transition-colors flex items-center justify-center gap-1.5">
                        <span>✉️</span> Email
                    </button>
                </div>

                {{-- ── 3. Login Form ── --}}
                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" :value="activeRole" />

                    {{-- ── PHONE OTP FIELDS ── --}}
                    <div x-show="authMethod === 'phone'" class="space-y-3">
                        <input type="hidden" name="auth_type" value="phone_otp" />

                        <div>
                            <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">Mobile Number</label>
                            {{-- Phone row: flag+code | number input | send OTP button — stacks to column on xs --}}
                            <div class="flex flex-col xs:flex-row gap-2">
                                <div class="flex gap-2 flex-1">
                                    <span class="shrink-0 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-xs font-sans font-bold text-[var(--color-ebony)] flex items-center">
                                        🇮🇳 +91
                                    </span>
                                    <input type="tel" name="phone" x-model="phoneInput"
                                           placeholder="9876543210" maxlength="10" required
                                           class="flex-1 min-w-0 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-sm font-bold tracking-wider focus:outline-none focus:border-[var(--color-rose-antique)]" />
                                </div>
                                <button type="button" @click="sendOtp()"
                                        class="w-full xs:w-auto shrink-0 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[11px] font-sans font-bold uppercase tracking-wider px-4 py-3 rounded-xl transition-colors whitespace-nowrap">
                                    <span x-text="otpSent ? 'Resend OTP' : 'Send OTP'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- OTP box --}}
                        <div x-show="otpSent" x-transition class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2">
                            <div class="flex items-center justify-between text-[11px] font-sans text-emerald-800">
                                <span class="font-bold">✨ OTP sent to +91 <span x-text="phoneInput"></span></span>
                                <span class="font-mono text-emerald-600" x-show="otpTimer > 0">Resend in <span x-text="otpTimer"></span>s</span>
                            </div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-emerald-900 mb-1">
                                Enter OTP <span class="font-normal normal-case">(Demo: 1234)</span>
                            </label>
                            <input type="text" name="otp" x-model="otpInput" maxlength="6"
                                   class="w-full bg-white border border-emerald-300 rounded-xl px-4 py-2.5 text-center text-2xl font-mono font-bold tracking-[0.5em] text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400" />
                        </div>
                    </div>

                    {{-- ── EMAIL + PASSWORD FIELDS ── --}}
                    <div x-show="authMethod === 'email'" class="space-y-3">
                        <input type="hidden" name="auth_type" value="email" />

                        <div>
                            <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" x-model="emailInput"
                                   placeholder="name@estilo.com" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Password</label>
                                <a href="#" class="text-[11px] font-sans text-[var(--color-rose-antique)] hover:underline">Forgot password?</a>
                            </div>
                            <input type="password" name="password" x-model="passwordInput"
                                   placeholder="••••••••" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer text-[11px] font-sans text-[var(--color-ebony)]/70">
                            <input type="checkbox" name="remember" class="accent-[var(--color-rose-antique)]" />
                            Remember me on this device
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3.5 rounded-2xl text-white font-sans text-[11px] sm:text-xs font-bold uppercase tracking-widest shadow-lg transition-all hover:opacity-90 active:scale-[0.98] flex items-center justify-center gap-2"
                            :class="{
                                'bg-[var(--color-ebony)]': activeRole === 'customer',
                                'bg-amber-700': activeRole === 'sales_associate',
                                'bg-slate-900': activeRole === 'admin'
                            }">
                        <span x-text="activeRole === 'customer' ? 'Sign In as Customer' : (activeRole === 'sales_associate' ? 'Access Sales Dashboard' : 'Open Admin Console')"></span>
                        <span>→</span>
                    </button>
                </form>

                {{-- ── 4. Demo Credentials (For Testing Only) ── --}}
                <div class="border-t border-[var(--color-bisque)]/60 pt-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 space-y-2">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm3 1a1 1 0 100-2 1 1 0 000 2zm2-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/></svg>
                            <div class="flex-1">
                                <p class="text-[10px] sm:text-[11px] font-sans font-bold text-blue-900">Demo Customer Account</p>
                                <p class="text-[9px] sm:text-[10px] font-sans text-blue-800 mt-0.5">
                                    Email: <span class="font-mono bg-white/50 px-1 py-0.5 rounded">test@example.com</span> • Password: <span class="font-mono bg-white/50 px-1 py-0.5 rounded">Customer@123</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="authMethod = 'email'; emailInput = 'test@example.com'; passwordInput = 'Customer@123'; $nextTick(() => $el.closest('.p-5').querySelector('form').submit())" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-sans font-bold uppercase tracking-wider py-2 rounded-xl transition-all shadow-xs flex items-center justify-center gap-1 cursor-pointer">
                            <span>⚡ 1-Click Demo Customer Login</span>
                        </button>
                    </div>
                </div>

                {{-- Registration links --}}
                <div class="text-center text-[11px] sm:text-xs font-sans text-[var(--color-ebony)]/70 space-y-1.5 pt-2 border-t border-[var(--color-bisque)]/60">
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
