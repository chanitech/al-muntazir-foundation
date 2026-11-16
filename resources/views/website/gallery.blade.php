@extends('website.layouts.app')

@section('title', $category ? $category.' – Gallery' : 'Gallery')

@section('content')
    @include('website.partials.page-hero', [
        'title' => $category ?: 'Photo Gallery',
        'subtitle' => 'Moments from our programs, events and community.',
        'crumbs' => $category ? ['Gallery' => route('site.gallery')] : [],
    ])

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <nav class="filter-pills" aria-label="Albums">
                    <a href="{{ route('site.gallery') }}" class="{{ $category ? '' : 'active' }}">All photos</a>
                    @foreach ($categories as $album)
                        <a href="{{ route('site.gallery', ['category' => $album]) }}" class="{{ $category === $album ? 'active' : '' }}">{{ $album }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($photos->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-images"></i>
                    <p class="mb-0">Photos will be added here soon.</p>
                </div>
            @else
                <div class="gallery-grid">
                    @foreach ($photos as $photo)
                        <a href="{{ $photo->mediaUrl() }}" class="gallery-item glightbox" data-gallery="gallery"
                           @if($photo->title) data-title="{{ $photo->title }}" @endif aria-label="{{ $photo->title ?: 'Open photo' }}">
                            <img src="{{ $photo->mediaUrl() }}" alt="{{ $photo->title }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
                <div class="mt-5">{{ $photos->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </section>
@endsection
