<article class="card-x {{ $program->mediaUrl() ? '' : 'program-plain' }}">
    @if ($program->mediaUrl())
        <div class="media"><img src="{{ $program->mediaUrl() }}" alt="" loading="lazy"></div>
    @endif
    <div class="body">
        @unless ($program->mediaUrl())
            <div class="program-icon"><i class="bi {{ $program->icon ?: 'bi-stars' }}"></i></div>
        @endunless
        <h3><a href="{{ route('site.programs.show', $program) }}">{{ $program->title }}</a></h3>
        @if ($program->schedule)
            <div class="meta"><span><i class="bi bi-calendar3"></i>{{ $program->schedule }}</span></div>
        @endif
        @if ($program->summary)
            <p>{{ $program->summary }}</p>
        @endif
        <span class="link-arrow footer-link">Learn more <i class="bi bi-arrow-right"></i></span>
    </div>
</article>
