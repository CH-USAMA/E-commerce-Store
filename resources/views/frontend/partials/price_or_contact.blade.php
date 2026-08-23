@php
    $hidePricing = ($settings['hide_pricing'] ?? '0') == '1';
    $waPhone = preg_replace('/[^0-9]/', '', $settings['invoice_company_phone'] ?? '27660684585');
    $waMessage = urlencode("Hi, I'm interested in {$product->name}");
    $waLink = "https://wa.me/{$waPhone}?text={$waMessage}";
@endphp
@if($product->offersVariants())
    {{-- A product sold in sizes cannot be actioned from a listing card: adding it to
         the cart would have to guess a size and therefore a price, and a WhatsApp
         enquiry with no size just puts the customer back to typing "the 4.8m one".
         Send them to the product page to choose. --}}
    <a href="{{ route('product.detail', $product->slug) }}"
        class="bg-gold-400 text-dark font-black px-8 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-white transition transform translate-y-4 group-hover:translate-y-0 duration-500 shadow-xl flex items-center gap-2">
        <i class="fas fa-ruler-combined text-sm"></i>
        Choose Size ({{ $product->activeVariants->count() }})
    </a>
@elseif($hidePricing)
    <a href="{{ $waLink }}" target="_blank" rel="noopener"
        class="bg-gold-400 text-dark font-black px-8 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-white transition transform translate-y-4 group-hover:translate-y-0 duration-500 shadow-xl flex items-center gap-2">
        <i class="fab fa-whatsapp text-sm"></i> Contact Us
    </a>
@else
    <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
        class="bg-gold-400 text-dark font-black px-8 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-white transition transform translate-y-4 group-hover:translate-y-0 duration-500 shadow-xl flex items-center gap-2">
        <i class="fas fa-cart-plus text-sm"></i> Add to Cart
    </button>
@endif
