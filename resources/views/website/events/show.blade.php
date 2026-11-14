@extends('website.layouts.app')

@section('title', $event->title)
@section('meta_description', Str::limit((string) $event->description, 160) ?: $event->title.' – '.$event->event_date->format('d F Y'))
@section('meta_image', $event->imageUrl() ?? '')

@section('content')
    @include('website.partials.page-hero', [
        'title' => $event->title,
        'subtitle' => $event->event_date->format('l, d F Y').($event->location ? ' · '.$event->location : ''),
        'crumbs' => ['Events' => route('site.events.index')],
        'image' => $event->imageUrl(),
    ])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($event->status === 'cancelled')
                        <div class="alert alert-warning rounded-3"><i class="bi bi-exclamation-triangle me-1"></i> This event has been cancelled.</div>
                    @endif
                    @if ($event->imageUrl())
                        <img src="{{ $event->imageUrl() }}" alt="" class="w-100 rounded-4 shadow-sm mb-4" style="max-height: 460px; object-fit: cover">
                    @endif
                    <h2 class="h3 mb-3">About this event</h2>
                    <div class="prose">
                        @if ($event->description)
                            {!! nl2br(e($event->description)) !!}
                        @else
                            <p class="text-muted">More details will be shared soon.</p>
                        @endif
                    </div>
                </div>
                <aside class="col-lg-4">
                    <div class="side-card mb-4">
                        <h3>Event details</h3>
                        <ul class="info-list mb-4">
                            <li><i class="bi bi-calendar3"></i><div><span>Date</span><strong>{{ $event->event_date->format('l, d F Y') }}</strong></div></li>
                            @if ($event->timeRange())
                                <li><i class="bi bi-clock"></i><div><span>Time</span><strong>{{ $event->timeRange() }}</strong></div></li>
                            @endif
                            @if ($event->location)
                                <li><i class="bi bi-geo-alt"></i><div><span>Venue</span><strong>{{ $event->location }}</strong></div></li>
                            @endif
                            @if ($event->category)
                                <li><i class="bi bi-tag"></i><div><span>Category</span><strong>{{ $event->category->name }}</strong></div></li>
                            @endif
                        </ul>
                        <a href="{{ route('site.contact', ['subject' => 'Events']) }}" class="btn btn-brand w-100">Ask about this event</a>
                    </div>
                    @if ($more->isNotEmpty())
                        <div class="side-card">
                            <h3>More upcoming events</h3>
                            @foreach ($more as $item)
                                <div class="mini-post">
                                    <span class="mini-placeholder flex-column lh-1" style="font-size: .8rem"><strong style="font-size: 1.2rem">{{ $item->event_date->format('d') }}</strong>{{ $item->event_date->format('M') }}</span>
                                    <div><a href="{{ route('site.events.show', $item) }}">{{ $item->title }}</a>
                                        @if ($item->location)<small class="d-block">{{ $item->location }}</small>@endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection
