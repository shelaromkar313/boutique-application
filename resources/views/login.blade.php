@extends('layouts.app')

@section('title', 'Sign In | Estilo Wear Portal')

@section('content')
<div x-data="{
    activeRole: '{{ request('role', 'customer') }}',
    authMethod: 'phone',
    phoneLoginMethod: 'otp',
    emailLoginMethod: 'password',
    phoneInput: '9876543212',
    phonePassword: 'Customer@123',
    emailInput: 'test@example.com',
    emailPassword: 'Customer@123',
    otpSent: false,
    otpInput: '1234',
    otpTimer: 30,
    emailOtpSent: false,
    emailOtpInput: '1234',
    emailOtpTimer: 30,
    timerInterval: null,
    emailTimerInterval: null,

    selectRole(role) {
        this.activeRole = role;
        this.otpSent = false;
        this.emailOtpSent = false;
        if (role === 'admin') {
            this.authMethod = 'email';
            this.emailInput = 'admin@estilo.com';
            this.phoneInput = '9000000001';
            this.phonePassword = 'Admin@123';
            this.emailPassword = 'Admin@123';
        } else if (role === 'sales_associate') {
            this.emailInput = 'associate@estilo.com';
            this.phoneInput = '9876543211';
            this.phonePassword = 'Partner@123';
            this.emailPassword = 'Partner@123';
        } else {
            this.emailInput = 'test@example.com';
            this.phoneInput = '9876543212';
            this.phonePassword = 'Customer@123';
            this.emailPassword = 'Customer@123';
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
    },

    sendEmailOtp() {
        if (!this.emailInput || !this.emailInput.includes('@')) {
            alert('Please enter a valid email address.');
            return;
        }
        this.emailOtpSent = true;
        this.emailOtpInput = '1234';
        this.emailOtpTimer = 30;
        clearInterval(this.emailTimerInterval);
        this.emailTimerInterval = setInterval(() => {
            if (this.emailOtpTimer > 0) this.emailOtpTimer--;
            else clearInterval(this.emailTimerInterval);
        }, 1000);
    }
}"
class="min-h-[85vh] flex items-center justify-center py-8 px-3 sm:px-6 bg-gradient-to-b from-[var(--color-champagne-light)]/40 to-[var(--color-offwhite)]">

    <div class="w-full max-w-md">



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
                <div class="text-center">
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Sign In</h1>
                </div>

                <input type="hidden" name="role" value="customer" />

                {{-- ── 2. Auth Method Toggle ── --}}
                <div class="flex rounded-xl overflow-hidden border border-[var(--color-bisque)] text-[11px] sm:text-xs font-sans font-bold uppercase tracking-wide">
                    <button type="button" @click="authMethod = 'phone'"
                            :class="authMethod === 'phone' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                            class="flex-1 py-2.5 transition-colors flex items-center justify-center gap-1.5">
                        <span>📱</span> Mobile Number
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

                    {{-- ── PHONE LOGIN FIELDS ── --}}
                    <div x-show="authMethod === 'phone'" class="space-y-3">
                        <input type="hidden" name="auth_type" :value="phoneLoginMethod === 'otp' ? 'phone_otp' : 'phone_password'" />

                        <div>
                            <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">Mobile Number</label>
                            <div class="flex gap-2 flex-1">
                                <span class="shrink-0 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-xs font-sans font-bold text-[var(--color-ebony)] flex items-center">
                                    🇮🇳 +91
                                </span>
                                <input type="tel" name="phone" x-model="phoneInput"
                                       placeholder="9876543210" maxlength="10" required
                                       class="flex-1 min-w-0 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-sm font-bold tracking-wider focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>
                        </div>

                        {{-- Login method toggle: OTP or Password --}}
                        <div class="flex rounded-xl overflow-hidden border border-[var(--color-bisque)] text-[11px] sm:text-xs font-sans font-bold uppercase tracking-wide">
                            <button type="button" @click="phoneLoginMethod = 'otp'; otpSent = false"
                                    :class="phoneLoginMethod === 'otp' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                                    class="flex-1 py-2.5 transition-colors flex items-center justify-center gap-1.5">
                                <span>📱</span> Send OTP
                            </button>
                            <button type="button" @click="phoneLoginMethod = 'password'"
                                    :class="phoneLoginMethod === 'password' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                                    class="flex-1 py-2.5 border-l border-[var(--color-bisque)] transition-colors flex items-center justify-center gap-1.5">
                                <span>🔐</span> Password
                            </button>
                        </div>

                        {{-- OTP section --}}
                        <div x-show="phoneLoginMethod === 'otp'" class="space-y-3" x-transition>
                            <button type="button" @click="sendOtp()"
                                    class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[11px] font-sans font-bold uppercase tracking-wider px-4 py-3 rounded-xl transition-colors">
                                <span x-text="otpSent ? 'Resend OTP' : 'Send OTP'"></span>
                            </button>

                            {{-- OTP input box --}}
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

                        {{-- Password section --}}
                        <div x-show="phoneLoginMethod === 'password'" class="space-y-3" x-transition>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Password</label>
                                    <a href="#" class="text-[11px] font-sans text-[var(--color-rose-antique)] hover:underline">Forgot password?</a>
                                </div>
                                <input type="password" name="phone_password" x-model="phonePassword"
                                       placeholder="••••••••" required
                                       class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>

                            <label class="flex items-center gap-2 cursor-pointer text-[11px] font-sans text-[var(--color-ebony)]/70">
                                <input type="checkbox" name="remember" class="accent-[var(--color-rose-antique)]" />
                                Remember me on this device
                            </label>
                        </div>
                    </div>

                    {{-- ── EMAIL LOGIN FIELDS ── --}}
                    <div x-show="authMethod === 'email'" class="space-y-3">
                        <input type="hidden" name="auth_type" :value="emailLoginMethod === 'otp' ? 'email_otp' : 'email_password'" />

                        <div>
                            <label class="block text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" x-model="emailInput"
                                   placeholder="name@estilo.com" required
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        {{-- Login method toggle: OTP or Password --}}
                        <div class="flex rounded-xl overflow-hidden border border-[var(--color-bisque)] text-[11px] sm:text-xs font-sans font-bold uppercase tracking-wide">
                            <button type="button" @click="emailLoginMethod = 'otp'; emailOtpSent = false"
                                    :class="emailLoginMethod === 'otp' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                                    class="flex-1 py-2.5 transition-colors flex items-center justify-center gap-1.5">
                                <span>📧</span> Send OTP
                            </button>
                            <button type="button" @click="emailLoginMethod = 'password'"
                                    :class="emailLoginMethod === 'password' ? 'bg-[var(--color-ebony)] text-white' : 'bg-white text-[var(--color-ebony)]/60 hover:bg-[var(--color-offwhite)]'"
                                    class="flex-1 py-2.5 border-l border-[var(--color-bisque)] transition-colors flex items-center justify-center gap-1.5">
                                <span>🔐</span> Password
                            </button>
                        </div>

                        {{-- OTP section --}}
                        <div x-show="emailLoginMethod === 'otp'" class="space-y-3" x-transition>
                            <button type="button" @click="sendEmailOtp()"
                                    class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[11px] font-sans font-bold uppercase tracking-wider px-4 py-3 rounded-xl transition-colors">
                                <span x-text="emailOtpSent ? 'Resend OTP' : 'Send OTP'"></span>
                            </button>

                            {{-- OTP input box --}}
                            <div x-show="emailOtpSent" x-transition class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2">
                                <div class="flex items-center justify-between text-[11px] font-sans text-emerald-800">
                                    <span class="font-bold">✨ OTP sent to <span x-text="emailInput"></span></span>
                                    <span class="font-mono text-emerald-600" x-show="emailOtpTimer > 0">Resend in <span x-text="emailOtpTimer"></span>s</span>
                                </div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-emerald-900 mb-1">
                                    Enter OTP <span class="font-normal normal-case">(Demo: 1234)</span>
                                </label>
                                <input type="text" name="email_otp" x-model="emailOtpInput" maxlength="6"
                                       class="w-full bg-white border border-emerald-300 rounded-xl px-4 py-2.5 text-center text-2xl font-mono font-bold tracking-[0.5em] text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400" />
                            </div>
                        </div>

                        {{-- Password section --}}
                        <div x-show="emailLoginMethod === 'password'" class="space-y-3" x-transition>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Password</label>
                                    <a href="#" class="text-[11px] font-sans text-[var(--color-rose-antique)] hover:underline">Forgot password?</a>
                                </div>
                                <input type="password" name="email_password" x-model="emailPassword"
                                       placeholder="••••••••" required
                                       class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>

                            <label class="flex items-center gap-2 cursor-pointer text-[11px] font-sans text-[var(--color-ebony)]/70">
                                <input type="checkbox" name="remember" class="accent-[var(--color-rose-antique)]" />
                                Remember me on this device
                            </label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3.5 rounded-2xl text-white font-sans text-[12px] sm:text-sm font-bold uppercase tracking-widest shadow-lg transition-all hover:opacity-90 active:scale-[0.98] bg-[var(--color-ebony)]">
                        Sign In
                    </button>
                </form>



                {{-- Registration links --}}
                <div class="text-center pt-4">
                    <p class="text-sm sm:text-base font-sans text-[var(--color-ebony)]/70">
                        New customer? <a href="/register" class="text-[var(--color-rose-antique)] font-bold hover:underline">Create Account</a>
                    </p>
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
