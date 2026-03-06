{{-- CTA Contact Section --}}
<section class="py-24 bg-gold-400 relative overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute inset-0 opacity-10">
        <div
            class="absolute top-0 left-0 w-64 h-64 border-[40px] border-dark rounded-full -translate-x-1/2 -translate-y-1/2">
        </div>
        <div
            class="absolute bottom-0 right-0 w-96 h-96 border-[60px] border-dark rounded-full translate-x-1/3 translate-y-1/3">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-dark mb-8 uppercase italic tracking-tighter leading-tight">
            Ready to start your <br> <span class="bg-dark text-gold-400 px-4 py-1">next project?</span>
        </h2>
        <p class="text-dark/70 text-lg md:text-xl font-bold mb-12 max-w-2xl mx-auto uppercase tracking-widest">
            Get a professional quote for any product — listed or not.
        </p>

        <div class="flex flex-wrap justify-center items-center gap-6">
            <a href="{{ route('contact') }}"
                class="px-12 py-6 bg-dark text-gold-400 rounded-full font-black uppercase tracking-[0.2em] text-sm hover:scale-105 transition-all duration-300 shadow-2xl">
                Get Free Quote <i class="fas fa-file-invoice-dollar ml-3"></i>
            </a>
            <a href="https://wa.me/27660684585" target="_blank"
                class="px-12 py-6 border-2 border-dark/20 text-dark rounded-full font-black uppercase tracking-[0.2em] text-sm hover:bg-dark hover:text-white transition-all duration-300">
                Chat on WhatsApp <i class="fab fa-whatsapp ml-3"></i>
            </a>
        </div>

        <div class="mt-16 pt-16 border-t border-dark/10">
            <div class="flex flex-wrap justify-center gap-12">
                <div class="text-center">
                    <span class="block text-3xl font-black text-dark mb-1">24/7</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-dark/40">Response</span>
                </div>
                <div class="text-center">
                    <span class="block text-3xl font-black text-dark mb-1">Direct</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-dark/40">Delivery</span>
                </div>
                <div class="text-center">
                    <span class="block text-3xl font-black text-dark mb-1">Bulk</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-dark/40">Discounts</span>
                </div>
            </div>
        </div>
    </div>
</section>