@extends('layouts.frontend')

@section('meta_title', $post->title . ' — Jabulani Group')
@section('meta_description', Str::limit(strip_tags($post->content), 160))
@section('og_type', 'article')
@if($post->feature_image)
    @section('og_image', asset($post->feature_image))
@endif

@section('content')

    <!-- Post Header -->
    <div class="relative py-24 md:py-32 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-20">
            <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('' . $post->feature_image)) : asset('images/placeholder.webp') }}"
                class="w-full h-full object-cover blur-sm scale-110" alt="Background">
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="flex justify-center items-center gap-3 mb-8">
                <span
                    class="bg-gold-400 text-dark text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full">
                    {{ $post->category->name ?? 'Company Update' }}
                </span>
                <span class="text-dark-muted font-black text-[10px] uppercase tracking-widest">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-10 tracking-tight">{{ $post->title }}
            </h1>
            <div class="flex justify-center items-center gap-4">
                <div class="w-12 h-12 rounded-full border-2 border-gold-400 p-0.5">
                    <div
                        class="w-full h-full rounded-full bg-white/10 flex items-center justify-center font-black text-gold-400">
                        J</div>
                </div>
                <div class="text-left">
                    <p class="text-white font-bold text-sm">Jabulani Editorial</p>
                    <p class="text-dark-muted text-[10px] uppercase tracking-widest">Official Publication</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <article class="bg-[#050505] py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Featured Image -->
            <div class="mb-20 -mt-40 relative z-20">
                <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('' . $post->feature_image)) : asset('images/placeholder.webp') }}"
                    class="w-full rounded-[2.5rem] shadow-[0_30px_100px_rgba(0,0,0,0.5)] border border-white/5"
                    alt="{{ $post->title }}">
            </div>

            <!-- Content -->
            <div class="prose prose-invert prose-gold max-w-none 
                            prose-headings:font-black prose-headings:tracking-tight
                            prose-p:text-gray-400 prose-p:leading-relaxed prose-p:text-lg
                            prose-strong:text-gold-400
                            prose-a:text-gold-400 prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-3xl prose-img:border prose-img:border-dark-border">
                {!! $post->content !!}
            </div>

            <!-- Footer Meta -->
            <div class="mt-24 pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-4">
                    <span class="text-xs font-black uppercase tracking-widest text-dark-muted">Share This Story</span>
                    <div class="flex gap-3">
                        @foreach(['facebook-f', 'twitter', 'linkedin-in', 'whatsapp'] as $social)
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition-all">
                                <i class="fab fa-{{ $social }} text-sm"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('blog') }}" class="btn-outline-gold group px-8 py-3.5 text-[11px] rounded-full">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Back to Newsroom
                </a>
            </div>

        </div>
    </article>

    <!-- Author Bio / Final CTA -->
    <section class="py-24 bg-dark border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="card-dark p-12 rounded-[3rem] border-white/5 bg-gradient-to-b from-white/5 to-transparent">
                <h4 class="text-gold-400 font-black uppercase tracking-[.3em] text-xs mb-6">About Jabulani Group</h4>
                <p class="text-gray-400 leading-relaxed max-w-2xl mx-auto mb-10 italic">"Supplying top-quality building
                    materials and hardware since 2002. Our mission is to make construction accessible to all through
                    innovation and fair pricing."</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('about') }}"
                        class="text-xs font-black uppercase tracking-widest text-white hover:text-gold-400 transition">Our
                        History</a>
                    <span class="text-dark-muted">/</span>
                    <a href="{{ route('products') }}"
                        class="text-xs font-black uppercase tracking-widest text-white hover:text-gold-400 transition">Browse
                        Catalog</a>
                    <span class="text-dark-muted">/</span>
                    <a href="{{ route('contact') }}"
                        class="text-xs font-black uppercase tracking-widest text-white hover:text-gold-400 transition">Get
                        In Touch</a>
                </div>
            </div>
        </div>
    </section>

@endsection