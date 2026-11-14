@extends('website.layouts.app')

@section('title', 'Events')

@section('content')
    @include('website.partials.page-hero', ['title' => 'Events', 'subtitle' => 'Gatherings, trainings and activities. Come and be part of them.'])

    <section class="section">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">Coming up</span>
                <h2>{{ $site->events_title }}</h2>
            </div>

            @if ($upcoming->isEmpty())
                <div class="empty-state bg-soft rounded-4">
                    <i class="bi bi-calendar-event"></i>
                    <p class="mb-3">There are no upcoming events at the moment. Follow us to hear about the next one.</p>
                    @include('website.partials.social-links')
                </div>
            @else
                <div class="d-grid gap-3">
                    @foreach ($upcoming as $event)
                        @include('website.events._row', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($past->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="section-heading reveal">
                    <span class="eyebrow">Looking back</span>
                    <h2>Past Events</h2>
                </div>
                <div class="row g-4">
                    @foreach ($past as $event)
                        <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index % 3 }}">
                            @include('website.partials.event-card', ['event' => $event])
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $past->links('pagination::bootstrap-5') }}</div>
            </div>
        </section>
    @endif
@endsection
