@extends('layouts.app')

@section('title', 'Estilo HQ — Broadcast Storefront Announcement')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Navigation Breadcrumb --}}
        <div class="flex items-center justify-between">
            <a href="/estilo-hq-console?tab=announcements" class="inline-flex items-center gap-2 text-xs font-sans font-bold text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Announcements & Alerts
            </a>
            <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-rose-800 bg-rose-100 px-3 py-1 rounded-full">
                📢 Broadcast Suite
            </span>
        </div>

        {{-- Main Creation Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-xl p-6 sm:p-10 space-y-8"
             x-data="{
                announcement: {
                    title: 'Festive Royal Gala',
                    message: 'Enjoy 30% Off all royal Banarasi & Chikankari outfits with free express shipping across India!',
                    type: 'festive',
                    color: 'amber',
                    icon: '📢',
                    show_in_ticker: true,
                    show_as_banner: false
                }
             }">

            {{-- Header --}}
            <div class="border-b border-[var(--color-bisque)]/60 pb-5">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Broadcast Storefront Announcement</h1>
                <p class="text-xs font-sans text-gray-500 mt-1">Publish live notices, promotion alerts, and discount highlights directly across the storefront header marquee and floating banners.</p>
            </div>

            {{-- Live Marquee Ticker Preview --}}
            <div class="space-y-2">
                <label class="block text-[11px] font-sans font-bold text-[var(--color-ebony)]/70 uppercase tracking-wider">
                    ● Live Marquee Ticker Preview:
                </label>
                <div class="bg-[var(--color-ebony)] text-white p-3 rounded-2xl border border-amber-400/40 shadow-md overflow-hidden">
                    <div class="flex items-center gap-3">
                        <span class="text-xl" x-text="announcement.icon || '📢'"></span>
                        <div class="leading-tight">
                            <span class="text-amber-300 font-bold uppercase text-xs tracking-wider" x-text="(announcement.title || 'ANNOUNCEMENT TITLE') + ':'"></span>
                            <span class="text-xs text-white/90 ml-1" x-text="announcement.message || 'Your announcement broadcast message will scroll here across the customer store.'"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Body --}}
            <form action="/estilo-hq-console/announcements" method="POST" class="space-y-6">
                @csrf

                {{-- Emoji Icon & Title --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Badge Emoji</label>
                        <select name="icon" x-model="announcement.icon" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-base font-sans focus:outline-none focus:border-amber-400">
                            <option value="📢">📢 Loudspeaker</option>
                            <option value="🎉">🎉 Festive Gala</option>
                            <option value="✨">✨ Sparkle / Luxury</option>
                            <option value="🎟️">🎟️ Coupon Voucher</option>
                            <option value="🚚">🚚 Express Delivery</option>
                            <option value="🛍️">🛍️ Shopping Deal</option>
                            <option value="💎">💎 Diamond Edition</option>
                            <option value="🔥">🔥 Hot Trending</option>
                            <option value="⏳">⏳ Limited Hours</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Announcement Title / Event Label *</label>
                        <input type="text" name="title" x-model="announcement.title" placeholder="e.g. Navratri Special Gala, Free Express Shipping" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans font-bold focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                </div>

                {{-- Full Message --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">
                        Full Broadcast Message *
                    </label>
                    <textarea name="message" x-model="announcement.message" rows="3" required placeholder="e.g. Use code DIWALI25 for flat 25% off on all bridal sarees & pure Banarasi silk couture with complimentary gift packing!"
                              class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-4 text-xs font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner"></textarea>
                    <span class="text-[10px] text-gray-500 mt-1 block">This text will stream continuously in the storefront header marquee ticker.</span>
                </div>

                {{-- Category Type & Theme Color --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Category / Type</label>
                        <select name="type" x-model="announcement.type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400">
                            <option value="festive">Festival Special Campaign</option>
                            <option value="sale">Promotional Discount Sale</option>
                            <option value="coupon">Coupon Highlight</option>
                            <option value="shipping">Complimentary Shipping Alert</option>
                            <option value="alert">Boutique Notice</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Theme Style</label>
                        <select name="color" x-model="announcement.color" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400">
                            <option value="amber">Royal Gold / Amber</option>
                            <option value="rose">Rose Antique / Crimson</option>
                            <option value="emerald">Emerald Handloom Green</option>
                            <option value="ebony">Classic Atelier Ebony</option>
                        </select>
                    </div>
                </div>

                {{-- Display Placements --}}
                <div class="space-y-3 p-4 bg-gray-50 rounded-2xl border border-gray-200">
                    <span class="text-[11px] font-sans font-bold uppercase tracking-wider text-gray-700 block">Display Placements:</span>
                    <label class="flex items-center gap-3 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_in_ticker" checked value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span><strong>Marquee Ticker:</strong> Stream in the scrolling gold ticker directly beneath the navigation header.</span>
                    </label>
                    <label class="flex items-center gap-3 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_as_banner" value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span><strong>Top Notification Banner:</strong> Highlight as a prominent dismissible bar at the top of the storefront.</span>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4 pt-4 border-t border-[var(--color-bisque)]/60">
                    <a href="/estilo-hq-console?tab=announcements"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-3.5 rounded-xl transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit"
                            class="flex-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest py-3.5 px-8 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        📢 Broadcast Announcement Live
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
