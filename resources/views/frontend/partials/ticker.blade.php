<div class="py-4 border-y border-dark-border bg-[#0a0a0a] overflow-hidden whitespace-nowrap flex"
    style="background: linear-gradient(90deg, #050505 0%, #1a1a1a 50%, #050505 100%);">
    <style>
        @keyframes slide-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee-sub {
            animation: slide-left 40s linear infinite;
        }

        .animate-marquee-sub:hover {
            animation-play-state: paused;
        }
    </style>
    <div class="flex animate-marquee-sub min-w-max">
        @for($i = 0; $i < 6; $i++)
            <div class="flex items-center">
                @foreach(['Blocks SABS', 'Aluminium & Glass', 'Doors & Windows', 'Tiles', 'Hardware Tools', 'Roofing Material', 'Boards', 'Plumbing Material', 'Electrical Appliances', 'Wall & Ceiling'] as $item)
                    <span
                        class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-10 italic">
                        <img src="{{ asset('images/asterisk-icon.svg') }}" alt="*" class="w-3 h-3 mr-4 opacity-50"
                            style="filter: brightness(0) saturate(100%) invert(80%) sepia(21%) saturate(2335%) hue-rotate(345deg) brightness(101%) contrast(106%);" />
                        {{ $item }}
                    </span>
                @endforeach
            </div>
        @endfor
    </div>
</div>