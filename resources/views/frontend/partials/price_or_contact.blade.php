@php
    $hidePricing = ($settings['hide_pricing'] ?? '0') == '1';
    $waPhone = preg_replace('/[^0-9]/', '', $settings['invoice_company_phone'] ?? '27660684585');
    $waMessage = urlencode("Hi, I'm interested in {$product->name}");
    $waLink = "https://wa.me/{$waPhone}?text={$waMessage}";
@endphp
@if($hidePricing)
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
