@php
    $siteName = $site->get('site_name');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.$siteName : $siteName.' – '.$site->get('tagline');
    $description = trim($__env->yieldContent('meta_description')) ?: $site->get('meta_description');
    $shareImage = trim($__env->yieldContent('meta_image')) ?: $site->logoUrl();
    $version = fn (string $file) => asset($file).'?v='.(@filemtime(public_path($file)) ?: '1');
    $joinLink = $site->get('cta_button_link') ?: route('site.contact', ['subject' => 'Membership']);
    $nav = [
        ['Home', route('home'), request()->routeIs('home')],
        ['About', route('site.about'), request()->routeIs('site.about')],
        ['Programs', route('site.programs.index'), request()->routeIs('site.programs.*')],
        ['News', route('site.news.index'), request()->routeIs('site.news.*')],
        ['Events', route('site.events.index'), request()->routeIs('site.events.*')],
        ['Gallery', route('site.gallery'), request()->routeIs('site.gallery')],
        ['Contact', route('site.contact'), request()->routeIs('site.contact')],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($description), 300, '') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="{{ $site->get('dark_color') }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($description), 300, '') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ $site->image('favicon') ?? $site->logoUrl() }}">
    <link rel="apple-touch-icon" href="{{ $site->logoUrl() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap">

    <link rel="stylesheet" href="{{ asset('vendor/website/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/website/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/website/glightbox/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ $version('css/website.css') }}">
    <style>:root { {{ $site->cssVariables() }} }</style>
    @stack('head')
</head>
<body>
<a href="#main" class="skip-link">Skip to content</a>

{{-- Top bar --}}
@if ($site->contact_email || $site->contact_phone || $site->office_hours || count($site->socialLinks()))
    <div class="topbar d-none d-md-block">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex flex-wrap">
                @if ($site->contact_email)
                    <a class="topbar-item" href="mailto:{{ $site->contact_email }}"><i class="bi bi-envelope"></i>{{ $site->contact_email }}</a>
                @endif
                @if ($site->contact_phone)
                    <a class="topbar-item" href="tel:{{ preg_replace('/[^0-9+]/', '', $site->contact_phone) }}"><i class="bi bi-telephone"></i>{{ $site->contact_phone }}</a>
                @endif
                @if ($site->office_hours)
                    <span class="topbar-item d-none d-lg-inline-flex"><i class="bi bi-clock"></i>{{ $site->office_hours }}</span>
                @endif
            </div>
            @include('website.partials.social-links')
        </div>
    </div>
@endif

{{-- Main navigation --}}
<header class="site-header">
    <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ $site->logoUrl() }}" alt="{{ $siteName }} logo" width="52" height="52">
                <span class="brand-text">
                    <span class="brand-name">{{ $siteName }}</span>
                    @if ($site->tagline)
                        <span class="brand-tagline d-none d-sm-inline">{{ $site->tagline }}</span>
                    @endif
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    @foreach ($nav as [$label, $href, $active])
                        <li class="nav-item">
                            <a class="nav-link {{ $active ? 'active' : '' }}" href="{{ $href }}" @if($active) aria-current="page" @endif>{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="nav-actions d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="login-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="login-link"><i class="bi bi-person-circle"></i> Member Login</a>
                    @endauth
                    <a href="{{ $joinLink }}" class="btn btn-accent">{{ $site->get('cta_button_text', 'Join Us') }}</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main id="main">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <img src="{{ $site->logoUrl() }}" alt="" width="60" height="60">
                    <div>
                        <strong>{{ $siteName }}</strong>
                        @if ($site->site_short_name)<small>{{ $site->site_short_name }}</small>@endif
                    </div>
                </div>
                <p class="mb-4">{{ $site->footer_about }}</p>
                @include('website.partials.social-links')
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h4>Explore</h4>
                <ul class="footer-links">
                    @foreach ($nav as [$label, $href])
                        <li><a href="{{ $href }}"><i class="bi bi-chevron-right"></i>{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 col-6">
                <h4>Get in touch</h4>
                <ul class="footer-contact">
                    @if ($site->address)
                        <li><i class="bi bi-geo-alt"></i><span>{!! nl2br(e($site->address)) !!}</span></li>
                    @endif
                    @if ($site->contact_phone)
                        <li><i class="bi bi-telephone"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></li>
                    @endif
                    @if ($site->contact_email)
                        <li><i class="bi bi-envelope"></i><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></li>
                    @endif
                    @if ($site->office_hours)
                        <li><i class="bi bi-clock"></i><span>{{ $site->office_hours }}</span></li>
                    @endif
                    <li><i class="bi bi-chat-dots"></i><a href="{{ route('site.contact') }}">Send us a message</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h4>Newsletter</h4>
                <p>Get news about our programs and events.</p>
                @if (session('newsletter_success'))
                    <div class="alert alert-success-soft py-2 px-3 small mb-3" role="status"><i class="bi bi-check-circle me-1"></i>{{ session('newsletter_success') }}</div>
                @endif
                <form method="POST" action="{{ route('site.newsletter') }}" class="newsletter-form">
                    @csrf
                    <label for="newsletter-email" class="visually-hidden">E-mail address</label>
                    <div class="input-group">
                        <input type="email" name="email" id="newsletter-email" class="form-control" placeholder="Your e-mail" required
                               value="{{ old('email') && $errors->newsletter->any() ? old('email') : '' }}">
                        <button class="btn btn-accent" type="submit" aria-label="Subscribe"><i class="bi bi-send"></i></button>
                    </div>
                    @if ($errors->newsletter->any())
                        <div class="small mt-2 text-warning">{{ $errors->newsletter->first('email') }}</div>
                    @endif
                </form>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center">
            <div>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
            @if ($site->motto)
                <div class="footer-motto">“{{ $site->motto }}” @if($site->motto_source)<span>— {{ $site->motto_source }}</span>@endif</div>
            @endif
        </div>
    </div>
</footer>

<div class="float-actions">
    @if ($site->whatsappUrl())
        <a href="{{ $site->whatsappUrl() }}" class="whatsapp-btn" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><i class="bi bi-whatsapp"></i></a>
    @endif
    <button type="button" class="to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>
</div>

<script src="{{ asset('vendor/website/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/website/glightbox/glightbox.min.js') }}"></script>
<script src="{{ $version('js/website.js') }}"></script>
@stack('scripts')
</body>
</html>
