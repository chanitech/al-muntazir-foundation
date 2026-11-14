<article class="card-x">
    <div class="media">
        <div class="date-badge">
            <span class="day">{{ $event->event_date->format('d') }}</span>
            <span class="month">{{ $event->event_date->format('M') }}</span>
        </div>
        @if ($event->imageUrl())
            <img src="{{ $event->imageUrl() }}" alt="" loading="lazy">
        @else
            <div class="media-placeholder"><i class="bi bi-calendar-event"></i></div>
        @endif
    </div>
    <div class="body">
        <div class="meta">
            @if ($event->timeRange())<span><i class="bi bi-clock"></i>{{ $event->timeRange() }}</span>@endif
            @if ($event->location)<span><i class="bi bi-geo-alt"></i>{{ $event->location }}</span>@endif
        </div>
        <h3><a href="{{ route('site.events.show', $event) }}">{{ $event->title }}</a></h3>
        @if ($event->description)
            <p>{{ Str::limit($event->description, 120) }}</p>
        @endif
        <span class="link-arrow footer-link">Event details <i class="bi bi-arrow-right"></i></span>
    </div>
</article>
