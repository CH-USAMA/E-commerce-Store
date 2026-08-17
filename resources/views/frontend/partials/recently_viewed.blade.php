{{--
    "Recently viewed" strip.

    Reuses product_card.blade.php, so it inherits price hiding, image_url() resolution
    and the WhatsApp/contact CTA automatically — nothing here needs to know whether
    inquiry mode is on.

    Never cache this: the HomeController Cache::remember keys are for content shared by
    every visitor, and this list is per-session.

    Usage:  @include('frontend.partials.recently_viewed', ['excludeId' => $product->id])
--}}
@php($recentlyViewed = \App\Support\RecentlyViewed::products($excludeId ?? null))

@if($recentlyViewed->isNotEmpty())
    <section class="py-20 bg-[#050505] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gold-400/60 mb-2 block">
                        Pick up where you left off
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tighter">
                        Recently Viewed
                    </h2>
                </div>
                @if($recentlyViewed->count() > 2)
                    <a href="{{ route('products') }}"
                       class="hidden sm:inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition">
                        Browse all <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($recentlyViewed as $product)
                    @include('frontend.partials.product_card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endif
