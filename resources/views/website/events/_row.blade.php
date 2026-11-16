<article class="event-row reveal">
    <div class="when">
        <span class="day">{{ $event->event_date->format('d') }}</span>
        <span class="month">{{ $event->event_date->format('M') }}</span>
        <span class="year">{{ $event->event_date->format('Y') }}</span>
    </div>
    <div class="flex-grow-1 d-flex flex-column justify-content-center">
        @if ($event->category || $event->status === 'cancelled')
            <div class="mb-2">
                @if ($event->status === 'cancelled')
                    <span class="chip muted">Cancelled</span>
                @elseif ($event->category)
                    <span class="chip">{{ $event->category->name }}</span>
                @endif
            </div>
        @endif
        <h3><a href="{{ route('site.events.show', $event) }}">{{ $event->title }}</a></h3>
        <div class="meta">
            <span><i class="bi bi-calendar3"></i>{{ $event->event_date->format('l, d F Y') }}</span>
            @if ($event->timeRange())<span><i class="bi bi-clock"></i>{{ $event->timeRange() }}</span>@endif
            @if ($event->location)<span><i class="bi bi-geo-alt"></i>{{ $event->location }}</span>@endif
        </div>
    </div>
    @if ($event->imageUrl())
        <div class="thumb"><img src="{{ $event->imageUrl() }}" alt="" loading="lazy"></div>
    @endif
</article>
