@extends('website.layouts.app')

@section('title', 'About Us')
@section('meta_description', Str::limit($site->get('about_text'), 160))

@section('content')
    @include('website.partials.page-hero', [
        'title' => 'About Us',
        'subtitle' => $site->tagline,
        'image' => $site->image('about_image'),
    ])

    <section class="section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 order-lg-2 reveal">
                    <div class="about-media">
                        @if ($site->image('about_image'))
                            <img src="{{ $site->image('about_image') }}" alt="{{ $site->site_name }}">
                        @else
                            <div class="about-placeholder"><img src="{{ $site->logoUrl() }}" alt="{{ $site->site_name }} logo"></div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 reveal reveal-delay-1">
                    <span class="eyebrow">Our story</span>
                    <h2 class="mb-4" style="font-size: clamp(1.75rem, 3.2vw, 2.5rem)">{{ $site->about_title }}</h2>
                    @foreach ($site->paragraphs('about_text') as $paragraph)
                        <p class="{{ $loop->first ? 'lead-text' : 'text-muted' }}">{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if ($site->mission || $site->vision || count($site->lines('core_values')))
        <section class="section bg-soft">
            <div class="container">
                <div class="row g-4">
                    @if ($site->mission)
                        <div class="col-md-6 reveal">
                            <div class="mv-card p-4 p-lg-5">
                                <div class="icon"><i class="bi bi-bullseye"></i></div>
                                <h3 class="h4">Our Mission</h3>
                                <p class="fs-6">{{ $site->mission }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($site->vision)
                        <div class="col-md-6 reveal reveal-delay-1">
                            <div class="mv-card accent p-4 p-lg-5">
                                <div class="icon"><i class="bi bi-eye"></i></div>
                                <h3 class="h4">Our Vision</h3>
                                <p class="fs-6">{{ $site->vision }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if (count($site->lines('core_values')))
                    <div class="section-heading text-center mt-5 pt-4 mb-4 reveal">
                        <span class="eyebrow">What guides us</span>
                        <h2>Our Core Values</h2>
                    </div>
                    <div class="row g-3 justify-content-center">
                        @foreach ($site->lines('core_values') as $value)
                            @php [$name, $detail] = array_pad(array_map('trim', explode(':', $value, 2)), 2, null); @endphp
                            <div class="col-sm-6 col-lg-4 reveal reveal-delay-{{ $loop->index % 3 }}">
                                <div class="mv-card d-flex gap-3 align-items-start">
                                    <div class="icon mb-0 flex-shrink-0"><i class="bi bi-check2-circle"></i></div>
                                    <div>
                                        <h3 class="mb-1">{{ $name }}</h3>
                                        @if ($detail)<p>{{ $detail }}</p>@endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('website.partials.stats', ['stats' => $stats])

    @if ($team->isNotEmpty())
        <section class="section" id="team">
            <div class="container">
                <div class="section-heading text-center reveal">
                    <span class="eyebrow">Our people</span>
                    <h2>Meet the Team</h2>
                    <p>The people who lead and serve our community.</p>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach ($team as $person)
                        <div class="col-sm-6 col-lg-3 reveal reveal-delay-{{ $loop->index % 4 }}">
                            <div class="team-card">
                                @if ($person->mediaUrl('photo'))
                                    <img src="{{ $person->mediaUrl('photo') }}" alt="{{ $person->name }}" class="photo" loading="lazy">
                                @else
                                    <div class="photo avatar-initials" aria-hidden="true">{{ $person->initials() }}</div>
                                @endif
                                <h3>{{ $person->name }}</h3>
                                @if ($person->position)<div class="position">{{ $person->position }}</div>@endif
                                @if ($person->bio)<p>{{ $person->bio }}</p>@endif
                                @php
                                    $links = array_filter([
                                        ['mailto:'.$person->email, 'bi-envelope', 'E-mail', $person->email],
                                        [$person->facebook_url, 'bi-facebook', 'Facebook', $person->facebook_url],
                                        [$person->instagram_url, 'bi-instagram', 'Instagram', $person->instagram_url],
                                        [$person->x_url, 'bi-twitter-x', 'X', $person->x_url],
                                        [$person->linkedin_url, 'bi-linkedin', 'LinkedIn', $person->linkedin_url],
                                    ], fn ($link) => filled($link[3]));
                                @endphp
                                @if (count($links))
                                    <div class="social-links justify-content-center">
                                        @foreach ($links as [$href, $icon, $label])
                                            <a href="{{ $href }}" @if(! str_starts_with($href, 'mailto:')) target="_blank" rel="noopener" @endif aria-label="{{ $person->name }} on {{ $label }}"><i class="bi {{ $icon }}"></i></a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('website.partials.partners', ['partners' => $partners])
    @include('website.partials.cta')
@endsection
