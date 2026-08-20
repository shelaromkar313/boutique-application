@extends('layouts.app')

@section('title', 'Join the Atelier | ESTILO WEAR')

@section('content')

<div class="py-12 sm:py-20 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white p-8 sm:p-10 rounded-3xl border border-[var(--color-bisque)]/80 shadow-[var(--shadow-floating)] space-y-6">
        
        <div class="text-center space-y-2">
            <span class="text-[10px] font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Exclusive Membership</span>
            <h1 class="font-serif text-3xl font-bold text-[var(--color-ebony)]">Create Your Account</h1>
            <p class="text-xs font-sans text-[var(--color-ebony)]/60">Unlock curated boutique previews, couture sizing assistance & VIP rewards.</p>
        </div>

        <form action="/register" method="POST" class="space-y-4 text-xs font-sans">
            @csrf
            <div>
                <label class="block font-bold text-[var(--color-ebony)] mb-1">Full Name</label>
                <input type="text" name="name" placeholder="Priyanka Sharma" required class="w-full px-4 py-3 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
            </div>

            <div>
                <label class="block font-bold text-[var(--color-ebony)] mb-1">Mobile Phone Number</label>
                <input type="tel" name="phone" placeholder="9876543212" maxlength="10" class="w-full px-4 py-3 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors font-bold tracking-wider" />
            </div>

            <div>
                <label class="block font-bold text-[var(--color-ebony)] mb-1">Email Address</label>
                <input type="email" name="email" placeholder="priyanka@example.com" required class="w-full px-4 py-3 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
            </div>

            <div>
                <label class="block font-bold text-[var(--color-ebony)] mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" required class="w-full px-4 py-3 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
            </div>

            <div>
                <label class="block font-bold text-[var(--color-ebony)] mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required class="w-full px-4 py-3 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full focus:outline-none focus:border-[var(--color-rose-antique)] transition-colors" />
            </div>

            <div class="flex items-start gap-2 pt-1 text-[11px] text-[var(--color-ebony)]/70">
                <input type="checkbox" required class="accent-[#C87D87] mt-0.5" />
                <span>I agree to the Terms of Service & Privacy Policy of Estilo Wear.</span>
            </div>

            <button type="submit" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-3.5 rounded-full shadow-lg transition-all hover:scale-[1.02]">
                Join Estilo Atelier ✦
            </button>
        </form>

        <div class="text-center text-xs font-sans text-[var(--color-ebony)]/60 pt-2 border-t border-[var(--color-bisque)]/40">
            Already have an account? <a href="/login" class="font-bold text-[var(--color-rose-antique)] hover:underline">Sign In</a>
        </div>

    </div>
</div>

@endsection
