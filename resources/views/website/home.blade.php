@extends('website.layouts.app')

@section('meta_image', optional($slides->first())->mediaUrl() ?? '')

@section('content')

@php
    // Alternate white / light-grey backgrounds for the sections that are shown.
    $shaded = false;
    $band = function () use (&$shaded) { $shaded = ! $shaded; return $shaded ? 'bg-soft' : ''; };
@endphp

{{-- ===== Hero slider ===== --}}
@if ($slides->isNotEmpty())
    <section class="hero" aria-label="Highlights">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500">
            <div class="carousel-inner">
                @foreach ($slides as $slide)
                    <div @class(['carousel-item', 'active' => $loop->first])>
                        <div class="hero-media">
                            @if ($slide->mediaUrl())
                                <img src="{{ $slide->mediaUrl() }}" alt="" @if(! $loop->first) loading="lazy" @endif>
                            @endif
                        </div>
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <div class="container">
                                <div class="inner">
                                    <span class="hero-kicker"><i class="bi bi-stars"></i>{{ $site->site_short_name ?: $site->site_name }}</span>
                                    @if ($loop->first)
                                        <h1>{{ $slide->title ?: $site->hero_title }}</h1>
                                    @else
                                        <h2 class="hero-title">{{ $slide->title ?: $site->hero_title }}</h2>
                                    @endif
                                    @if ($slide->subtitle)
                                        <p class="hero-text">{{ $slide->subtitle }}</p>
                                    @endif
                                    <div class="d-flex flex-wrap gap-2">
                                        @if ($slide->button_text && $slide->button_link)
                                            <a href="{{ $slide->button_link }}" class="btn btn-accent btn-lg">{{ $slide->button_text }}</a>
                                        @endif
                                        <a href="{{ route('site.about') }}" class="btn btn-ghost-light btn-lg">About us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($slides->count() > 1)
                {{-- Slide dots and arrows, aligned with the text --}}
                <div class="hero-nav">
                    <div class="container">
                        <div class="carousel-indicators">
                            @foreach ($slides as $slide)
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}"
                                        @class(['active' => $loop->first]) @if($loop->first) aria-current="true" @endif
                                        aria-label="Slide {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                        <div class="hero-arrows d-none d-md-flex">
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@else
    {{-- Shown until slides are added in Admin → Website → Homepage Slider --}}
    <section class="hero hero-static" aria-label="Welcome">
        @if ($site->image('hero_image'))
            <div class="hero-media"><img src="{{ $site->image('hero_image') }}" alt=""></div>
            <div class="hero-overlay"></div>
        @else
            <img src="{{ $site->logoUrl() }}" alt="" class="hero-watermark">
        @endif
        <div class="hero-content">
            <div class="container">
                <div class="inner">
                    <span class="hero-kicker"><i class="bi bi-stars"></i>{{ $site->tagline }}</span>
                    <h1>{{ $site->hero_title }}</h1>
                    <p class="hero-text">{{ $site->hero_subtitle }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ $site->get('cta_button_link') ?: route('site.membership.apply') }}" class="btn btn-accent btn-lg">{{ $site->get('cta_button_text', 'Join Us') }}</a>
                        <a href="{{ route('site.about') }}" class="btn btn-ghost-light btn-lg">Learn more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ===== Motto ===== --}}
@if ($site->motto)
    <div class="motto-strip">
        <div class="container inner">
            <i class="bi bi-quote fs-4" aria-hidden="true"></i>
            <blockquote>{{ $site->motto }}</blockquote>
            @if ($site->motto_source)<cite>— {{ $site->motto_source }}</cite>@endif
        </div>
    </div>
@endif

{{-- ===== About ===== --}}
<section class="section" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <div class="about-media">
                    @if ($site->image('about_image'))
                        <img src="{{ $site->image('about_image') }}" alt="{{ $site->site_name }}" loading="lazy">
                    @else
                        <div class="about-placeholder"><img src="{{ $site->logoUrl() }}" alt="{{ $site->site_name }} logo"></div>
                    @endif
                    @if ($site->motto_source || $site->tagline)
                        <div class="about-badge">
                            <div class="icon"><i class="bi bi-people"></i></div>
                            <div><strong>{{ $site->tagline }}</strong><span>{{ $site->site_short_name }}</span></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 reveal reveal-delay-1">
                <span class="eyebrow">Who we are</span>
                <h2 class="mb-4" style="font-size: clamp(1.75rem, 3.2vw, 2.6rem)">{{ $site->about_title }}</h2>
                @foreach (array_slice($site->paragraphs('about_text'), 0, 2) as $paragraph)
                    <p class="{{ $loop->first ? 'lead-text' : 'text-muted' }}">{{ $paragraph }}</p>
                @endforeach
                <div class="row g-3 mt-2 mb-4">
                    @if ($site->mission)
                        <div class="col-sm-6">
                            <div class="mv-card">
                                <div class="icon"><i class="bi bi-bullseye"></i></div>
                                <h3>Our Mission</h3>
                                <p>{{ Str::limit($site->mission, 150) }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($site->vision)
                        <div class="col-sm-6">
                            <div class="mv-card accent">
                                <div class="icon"><i class="bi bi-eye"></i></div>
                                <h3>Our Vision</h3>
                                <p>{{ Str::limit($site->vision, 150) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <a href="{{ route('site.about') }}" class="btn btn-brand">More about us <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ===== Impact numbers ===== --}}
@include('website.partials.stats', ['stats' => $stats])

{{-- ===== Programs ===== --}}
@if ($programs->isNotEmpty())
    <section class="section {{ $band() }}" id="programs">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 section-heading mw-100 reveal">
                <div style="max-width: 640px">
                    <span class="eyebrow">What we do</span>
                    <h2>{{ $site->programs_title }}</h2>
                    <p>{{ $site->programs_intro }}</p>
                </div>
                <a href="{{ route('site.programs.index') }}" class="btn btn-outline-brand">All programs</a>
            </div>
            <div class="row g-4">
                @foreach ($programs as $program)
                    <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index % 3 }}">
                        @include('website.partials.program-card', ['program' => $program])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== Upcoming events ===== --}}
@if ($events->isNotEmpty())
    <section class="section {{ $band() }}" id="events">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 section-heading mw-100 reveal">
                <div>
                    <span class="eyebrow">Join us</span>
                    <h2>{{ $site->events_title }}</h2>
                </div>
                <a href="{{ route('site.events.index') }}" class="btn btn-outline-brand">All events</a>
            </div>
            <div class="row g-4">
                @foreach ($events as $event)
                    <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index }}">
                        @include('website.partials.event-card', ['event' => $event])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== Latest news ===== --}}
@if ($posts->isNotEmpty())
    <section class="section {{ $band() }}" id="news">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 section-heading mw-100 reveal">
                <div>
                    <span class="eyebrow">Stay informed</span>
                    <h2>{{ $site->news_title }}</h2>
                </div>
                <a href="{{ route('site.news.index') }}" class="btn btn-outline-brand">All news</a>
            </div>
            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index }}">
                        @include('website.partials.post-card', ['post' => $post])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== Testimonials ===== --}}
@if ($testimonials->isNotEmpty())
    <section class="section {{ $band() }}" id="testimonials">
        <div class="container">
            <div class="section-heading text-center reveal">
                <span class="eyebrow">Voices</span>
                <h2>What our community says</h2>
            </div>
            <div class="testimonial-track reveal" tabindex="0" aria-label="Testimonials">
                @foreach ($testimonials as $testimonial)
                    <figure class="testimonial m-0">
                        <div class="quote-icon"><i class="bi bi-quote"></i></div>
                        <blockquote class="m-0 mb-4">“{{ $testimonial->quote }}”</blockquote>
                        <figcaption class="person">
                            @if ($testimonial->mediaUrl('photo'))
                                <img src="{{ $testimonial->mediaUrl('photo') }}" alt="" class="avatar" loading="lazy">
                            @else
                                <span class="avatar avatar-initials">{{ mb_strtoupper(mb_substr($testimonial->name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <div class="name">{{ $testimonial->name }}</div>
                                @if ($testimonial->role)<div class="role">{{ $testimonial->role }}</div>@endif
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== Gallery preview ===== --}}
@if ($photos->isNotEmpty())
    <section class="section {{ $band() }}" id="gallery">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 section-heading mw-100 reveal">
                <div>
                    <span class="eyebrow">Gallery</span>
                    <h2>Moments from our community</h2>
                </div>
                <a href="{{ route('site.gallery') }}" class="btn btn-outline-brand">View gallery</a>
            </div>
            {{-- The large first tile only fills the grid evenly with 5 or 9 photos. --}}
            <div class="gallery-grid {{ in_array($photos->count(), [5, 9], true) ? 'featured' : '' }} reveal">
                @foreach ($photos as $photo)
                    <a href="{{ $photo->mediaUrl() }}" class="gallery-item glightbox" data-gallery="home"
                       @if($photo->title) data-title="{{ $photo->title }}" @endif aria-label="{{ $photo->title ?: 'Open photo' }}">
                        <img src="{{ $photo->mediaUrl() }}" alt="{{ $photo->title }}" loading="lazy">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== Partners ===== --}}
@include('website.partials.partners', ['partners' => $partners])

{{-- ===== Call to action ===== --}}
@include('website.partials.cta')

@endsection
