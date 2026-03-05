@extends('layouts.frontend')

@section('title', 'Jabulani Team - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Jabulani <span>team</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">team</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Team Start -->
    <div class="page-team" style="margin-top: -60px;">
        <div class="container">
            <!-- CEO Section (Manual for highlight) -->
            <div class="row mb-5">
                <div class="col-lg-7 order-lg-1 order-2">
                    <div class="team-single-content">
                        <div class="team-info-box">
                            <div class="team-info-header">
                                <div class="team-info-title">
                                    <p class="wow fadeInUp">CEO Jabulani Group of Companies</p>
                                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">Naeem Ahmed</h2>
                                </div>
                            </div>
                            <div class="team-contect-list">
                                <p style="text-align: justify;"><span style="color: #ffe507;">Naeem Ahmad</span>, born on
                                    May 2, 1978, is a visionary entrepreneur with over 32 years of experience in the
                                    hardware and building materials industry. Before founding <span
                                        style="color: #ffe507;">Jabulani Group of Companies</span> in 2002, he spent 10
                                    years working as a retailer and salesperson mastering the trade.</p>
                                <p>From the very beginning, his mission was clear—to give back to the community by making
                                    high-quality building materials affordable for everyone. Today through his leadership,
                                    Jabulani Group of Companies is a leading supplier across the Eastern Cape and beyond.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="team-single-sidebar">
                        <div class="team-single-image">
                            <figure class="image-anime reveal">
                                <img src="{{ asset('images/CEO2.png') }}" alt="Naeem Ahmed">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <h4 class="mb-4">Top Team Members</h4>
                <div class="row">
                    @foreach($teamMembers as $member)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="team-item wow fadeInUp">
                                <div class="team-image">
                                    <a class="image-anime">
                                        <figure>
                                            @php
                                                $imageSrc = $member->image;
                                                if ($imageSrc && !Str::startsWith($imageSrc, ['http', 'https'])) {
                                                    if (Str::contains($imageSrc, 'images/')) {
                                                        $imageSrc = asset($imageSrc);
                                                    } else {
                                                        $imageSrc = asset('' . $imageSrc);
                                                    }
                                                } elseif (!$imageSrc) {
                                                    $imageSrc = asset('images/placeholder-team.webp');
                                                }
                                            @endphp
                                            <img src="{{ $imageSrc }}" alt="{{ $member->name }}">
                                        </figure>
                                    </a>
                                </div>
                                <div class="team-body">
                                    <div class="team-content">
                                        <h3>{{ $member->name }}</h3>
                                        <p>{{ $member->designation }}</p>
                                    </div>
                                    <div class="team-social-list">
                                        <p style="color: #FFE507;">@ {{ $member->location ?? 'Jabulani Group' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Page Team End -->
@endsection