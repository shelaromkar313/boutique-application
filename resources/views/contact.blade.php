@extends('layouts.app')

@section('title', 'Contact Us | ESTILO WEAR')

@section('content')

@php
$faqs = [
    [
        'q' => 'Do you offer custom tailoring & bespoke fitting?',
        'a' => 'Yes! We offer complimentary alteration services for all Kurtis, Anarkalis, and Co-Ord sets purchased from Estilo Wear. You can visit our boutique atelier or request custom measurements during checkout.'
    ],
    [
        'q' => 'What is the estimated delivery timeframe?',
        'a' => 'Standard express shipping takes 3 to 5 business days across India. Handcrafted custom bridal couture orders take 10 to 14 business days.'
    ],
    [
        'q' => 'How do I care for pure Silk & Chikankari outfits?',
        'a' => 'We strongly recommend professional Dry Cleaning for all pure Silk sarees, Chikankari sets, and Zardozi garments to preserve fabric luster and hand embroidery.'
    ],
    [
        'q' => 'What is your return & exchange policy?',
        'a' => 'We offer a hassle-free 7-day exchange policy. If an item does not fit or you wish to switch colors, our courier partner will pick up the item from your doorstep.'
    ]
];
@endphp

<div class="pb-16 sm:pb-24 pt-4 sm:pt-8">
    <!-- Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-12 text-center">
        <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">
            Boutique Concierge
        </span>
        <h1 class="font-serif text-2xl sm:text-5xl font-bold text-[var(--color-ebony)] mt-2">
            Contact & Styling Appointments
        </h1>
        <p class="text-[11px] sm:text-sm font-sans text-[var(--color-ebony)]/70 mt-2 max-w-lg mx-auto">
            Have a question about fabric, custom fit, or bridal couture? Our personal stylists are at your service.
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">
            
            <!-- Contact Details Card -->
            <div class="space-y-6">
                <div class="bg-[var(--color-ebony)] text-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl shadow-[var(--shadow-floating)] space-y-4 sm:space-y-6">
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-champagne)]">
                        Visit Our Flagship Store
                    </h3>

                    <div class="space-y-4 text-xs font-sans text-white/80">
                        <div class="flex items-start gap-3">
                            <svg class="text-[var(--color-blush)] text-lg flex-shrink-0 mt-0.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>
                                <strong>Estilo Wear Atelier</strong><br />
                                Plot 45, Designer Boulevard, Jubilee Hills, Hyderabad - 500033
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="text-[var(--color-blush)] text-lg flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>Stylist Line: +91 98765 43210</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="text-[var(--color-blush)] text-lg flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Concierge: concierge@estilowear.com</span>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="text-[var(--color-blush)] text-lg flex-shrink-0 mt-0.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>
                                Monday – Saturday: 10:30 AM – 8:30 PM<br />
                                Sunday: By Appointment Only
                            </span>
                        </div>
                    </div>

                    <!-- WhatsApp Direct Connect Button -->
                    <div class="pt-4 border-t border-white/10">
                        <a href="https://wa.me/919876543210" target="_blank" rel="noreferrer" class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-sans text-xs font-bold uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md">
                            <svg class="text-base w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <form action="#" method="POST" class="bg-white p-5 sm:p-10 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm space-y-5 sm:space-y-6">
                    @csrf
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">
                        Send Us a Message
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Your Name *</label>
                            <input type="text" required placeholder="Ananya Sharma" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <div>
                            <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Phone Number *</label>
                            <input type="tel" required placeholder="+91 98765 43210" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Email Address *</label>
                            <input type="email" required placeholder="ananya@example.com" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>

                        <div>
                            <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Inquiry Type</label>
                            <select class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Bridal Consultation">Bridal Consultation Appointment</option>
                                <option value="Bespoke Tailoring">Custom Fitting & Alterations</option>
                                <option value="Order Status">Order Tracking Request</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Your Message / Requirement *</label>
                        <textarea rows="4" required placeholder="Tell us about the outfit you are looking for..." class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]"></textarea>
                    </div>

                    <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest px-8 py-4 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> Submit Request
                    </button>
                </form>
            </div>
        </div>

        <!-- FAQ Accordion using AlpineJS -->
        <div class="mt-12 sm:mt-20 pt-8 sm:pt-12 border-t border-[var(--color-bisque)]/60 max-w-3xl mx-auto space-y-6 sm:space-y-8" x-data="{ activeFaq: null }">
            <div class="text-center space-y-2">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Got Questions?</span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4">
                @foreach($faqs as $index => $faq)
                <div class="bg-white border border-[var(--color-bisque)]/60 rounded-2xl overflow-hidden shadow-sm transition-all">
                    <button @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" class="w-full p-5 text-left font-serif text-base font-bold text-[var(--color-ebony)] flex items-center justify-between hover:text-[var(--color-rose-antique)]">
                        <span>{{ $faq['q'] }}</span>
                        <svg :class="activeFaq === {{ $index }} ? 'rotate-180 text-[var(--color-rose-antique)]' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="activeFaq === {{ $index }}" style="display: none;" x-collapse class="p-5 pt-0 text-xs font-sans text-[var(--color-ebony)]/75 leading-relaxed border-t border-[var(--color-bisque)]/30">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection
