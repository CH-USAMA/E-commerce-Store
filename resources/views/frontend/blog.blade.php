@extends('layouts.frontend')

@section('title', 'Latest Blog - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/meeting.webp') }}" class="w-full h-full object-cover" alt="Blog Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Industry <span
                    class="gradient-text">Insights</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Latest News & Expert Advice From
                Jabulani</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Blog</span>
            </nav>
        </div>
    </div>

    <!-- Blog Grid -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($posts as $post)
                        <article
                            class="group relative card-dark p-2 rounded-[2.5rem] border-white/5 hover:border-gold-400/30 transition-all duration-500 shadow-2xl">
                            <div class="relative h-72 overflow-hidden rounded-[2.2rem]">
                                <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('' . $post->feature_image)) : asset('images/placeholder.webp') }}"
                                    class="w-full h-full object-cover grayscale-0 transition-transform duration-700 group-hover:scale-110"
                                    alt="{{ $post->title }}">

                                <!-- Badges -->
                                <div class="absolute top-6 left-6 flex gap-2">
                                    <span
                                        class="bg-black/60 backdrop-blur-md text-gold-400 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-gold-400/20">
                                        {{ $post->category->name ?? 'News' }}
                                    </span>
                                </div>
                            </div>

                            <div class="px-6 py-8">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted mb-4">
                                    {{ $post->published_at ? $post->published_at->format('F d, Y') : 'Recently Published' }}
                                </p>
                                <h3
                                    class="text-xl font-bold text-white mb-6 group-hover:text-gold-400 transition-colors leading-tight">
                                    <a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a>
                                </h3>

                                <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                    <a href="{{ route('blog.detail', $post->slug) }}"
                                        class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gold-400 hover:gap-4 transition-all">
                                        Read Article <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <div class="flex -space-x-2">
                                        <div
                                            class="w-6 h-6 rounded-full border border-dark bg-gold-400 flex items-center justify-center text-[10px] font-black text-dark">
                                            J</div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($posts->hasPages())
                    <div class="mt-20 flex justify-center pagination-container">
                        <div class="bg-black/40 p-2 rounded-2xl border border-white/5 backdrop-blur-md">
                            {{ $posts->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-24 card-dark rounded-[3rem] border-white/5 max-w-2xl mx-auto">
                    <i class="fas fa-pencil-alt text-gray-700 text-4xl mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-300">No stories yet.</h3>
                    <p class="text-dark-muted text-sm mt-2 leading-relaxed">We're currently drafting some exciting updates about
                        our new product lines and store openings. Stay tuned!</p>
                </div>
            @endif

        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="card-dark rounded-[3rem] p-12 md:p-20 bg-gradient-to-br from-[#111] to-dark border-gold-400/10 flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight">Stay ahead of <span
                            class="gradient-text">the build.</span></h2>
                    <p class="text-gray-400 leading-relaxed max-w-xl">Get the latest hardware news, price alerts, and
                        construction tips delivered straight to your inbox every month.</p>
                </div>
                <div class="w-full md:w-auto flex-shrink-0">
                    <form action="#" class="relative max-w-md">
                        <input type="email" placeholder="Your email address"
                            class="w-full bg-[#050505] border border-dark-border rounded-full py-5 px-8 text-sm focus:outline-none focus:border-gold-400 transition shadow-2xl">
                        <button
                            class="absolute top-1.5 right-1.5 bg-gold-400 text-dark px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-gold-500 transition shadow-xl">Join</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection