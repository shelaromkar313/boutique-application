@extends('layouts.app')

@section('title', 'Sales Partner Registration | ESTILO WEAR')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 bg-gradient-to-b from-[var(--color-champagne-light)]/30 to-[var(--color-offwhite)]">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-2xl p-8 sm:p-10 space-y-6">
            
            <div class="text-center space-y-1">
                <span class="text-[10px] font-sans font-bold text-amber-800 uppercase tracking-[0.3em] bg-amber-50 px-3 py-1 rounded-full border border-amber-200 inline-block">Partner Affiliate Program</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Join as a Sales Associate</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">Share handloom luxury couture and earn 10% - 15% commission on every customer order!</p>
            </div>

            <form action="/sales/register" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Full Legal Name</label>
                    <input type="text" name="name" placeholder="Pooja Verma" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" placeholder="pooja@example.com" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Mobile Phone</label>
                        <input type="tel" name="phone" placeholder="9876543211" required maxlength="10" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)] font-bold tracking-wider" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">UPI ID for Payouts (e.g. GooglePay, PhonePe, Paytm)</label>
                    <input type="text" name="upi_id" placeholder="yourname@okaxis" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Create Password</label>
                    <input type="password" name="password" placeholder="••••••••" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)] font-mono" />
                </div>

                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-[11px] font-sans text-amber-900 space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <span>✨ Automatic Referral Code Generation:</span>
                    </div>
                    <p class="text-[10px] text-amber-800">You will receive a unique partner link with marketing banners and real-time social sharing widgets.</p>
                </div>

                <button type="submit" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-2xl transition-all shadow-lg">
                    Register as Sales Partner →
                </button>
            </form>

            <div class="text-center text-xs font-sans text-[var(--color-ebony)]/60">
                Already registered as a partner? <a href="/login?role=sales_associate" class="text-amber-800 font-bold hover:underline">Log in here</a>
            </div>

        </div>
    </div>
</div>
@endsection
