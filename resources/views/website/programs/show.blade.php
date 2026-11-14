@extends('website.layouts.app')

@section('title', $program->title)
@section('meta_description', $program->summary ?: Str::limit(strip_tags((string) $program->body), 160))
@section('meta_image', $program->mediaUrl() ?? '')

@section('content')
    @include('website.partials.page-hero', [
        'title' => $program->title,
        'subtitle' => $program->summary,
        'crumbs' => [$site->programs_title => route('site.programs.index')],
        'image' => $program->mediaUrl(),
    ])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($program->body)
                        <div class="prose">{!! $program->body !!}</div>
                    @elseif ($program->summary)
                        <p class="lead-text">{{ $program->summary }}</p>
                    @endif
                </div>
                <aside class="col-lg-4">
                    <div class="side-card mb-4">
                        <h3>{{ $program->schedule ? 'When we meet' : 'Get involved' }}</h3>
                        @if ($program->schedule)
                            <ul class="info-list mb-4">
                                <li><i class="bi bi-calendar3"></i><div><span>Schedule</span><strong>{{ $program->schedule }}</strong></div></li>
                            </ul>
                        @else
                            <p class="text-muted">Would you like to take part in this program? Send us a message.</p>
                        @endif
                        <a href="{{ route('site.contact', ['subject' => 'Programs']) }}" class="btn btn-brand w-100">I'm interested</a>
                    </div>
                    @if ($others->isNotEmpty())
                        <div class="side-card">
                            <h3>Other programs</h3>
                            @foreach ($others as $other)
                                <div class="mini-post">
                                    @if ($other->mediaUrl())
                                        <img src="{{ $other->mediaUrl() }}" alt="" loading="lazy">
                                    @else
                                        <span class="mini-placeholder"><i class="bi {{ $other->icon ?: 'bi-stars' }}"></i></span>
                                    @endif
                                    <div><a href="{{ route('site.programs.show', $other) }}">{{ $other->title }}</a></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection
