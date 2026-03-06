@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center w-full">
        <ul class="flex flex-row flex-wrap items-center justify-center gap-2 m-0 p-0 list-none">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span
                        class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-500 bg-white/2 border border-white/5 rounded-xl cursor-not-allowed transition-all"
                        aria-hidden="true">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-400 bg-white/2 border border-white/5 rounded-xl hover:bg-white/5 hover:text-white hover:border-white/10 transition-all"
                        aria-label="{{ __('pagination.previous') }}">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span
                            class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-500 bg-white/2 border border-white/5 rounded-xl cursor-default transition-all">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span
                                    class="inline-flex items-center justify-center h-10 px-4 text-sm font-black text-dark bg-gold-400 border border-gold-400 rounded-xl transition-all shadow-[0_0_15px_rgba(251,191,36,0.3)]">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-400 bg-white/2 border border-white/5 rounded-xl hover:bg-white/5 hover:text-white hover:border-white/10 transition-all"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-400 bg-white/2 border border-white/5 rounded-xl hover:bg-white/5 hover:text-white hover:border-white/10 transition-all"
                        aria-label="{{ __('pagination.next') }}">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span
                        class="inline-flex items-center justify-center h-10 px-4 text-xs font-black text-gray-500 bg-white/2 border border-white/5 rounded-xl cursor-not-allowed transition-all"
                        aria-hidden="true">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif