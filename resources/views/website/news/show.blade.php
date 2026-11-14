@extends('website.layouts.app')

@section('title', $post->title)
@section('meta_description', $post->summary(160))
@section('meta_image', $post->mediaUrl() ?? '')
@section('og_type', 'article')

@section('content')
    @include('website.partials.page-hero', [
        'title' => $post->title,
        'crumbs' => [$site->news_title => route('site.news.index')],
    ])

    <section class="section pt-0">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($post->mediaUrl())
                        <div class="article-cover mb-4"><img src="{{ $post->mediaUrl() }}" alt=""></div>
                    @endif
                    <div class="d-flex flex-wrap gap-3 text-muted small mb-4 {{ $post->mediaUrl() ? '' : 'pt-5' }}">
                        <span><i class="bi bi-calendar3 text-brand me-1"></i>{{ $post->displayDate()->format('d F Y') }}</span>
                        <span><i class="bi bi-clock text-brand me-1"></i>{{ $post->readingMinutes() }} min read</span>
                        @if ($post->author)
                            <span><i class="bi bi-person text-brand me-1"></i>{{ $post->author->name }}</span>
                        @endif
                    </div>
                    <article class="prose">{!! $post->body !!}</article>

                    <div class="d-flex align-items-center gap-2 mt-5 pt-4 border-top share-links">
                        <strong class="me-2">Share:</strong>
                        <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" aria-label="Share on X"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                <aside class="col-lg-4 {{ $post->mediaUrl() ? 'pt-lg-5' : 'pt-5' }}">
                    @if ($recent->isNotEmpty())
                        <div class="side-card mb-4">
                            <h3>Recent news</h3>
                            @foreach ($recent as $item)
                                <div class="mini-post">
                                    @if ($item->mediaUrl())
                                        <img src="{{ $item->mediaUrl() }}" alt="" loading="lazy">
                                    @else
                                        <span class="mini-placeholder"><i class="bi bi-newspaper"></i></span>
                                    @endif
                                    <div>
                                        <a href="{{ route('site.news.show', $item) }}">{{ $item->title }}</a>
                                        <small class="d-block">{{ $item->displayDate()->format('d M Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="side-card text-center">
                        <img src="{{ $site->logoUrl() }}" alt="" width="72" height="72" class="rounded-circle mb-3">
                        <h3 class="mb-2">{{ $site->site_name }}</h3>
                        <p class="text-muted small">{{ $site->footer_about }}</p>
                        <a href="{{ route('site.about') }}" class="btn btn-outline-brand btn-sm">About us</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
