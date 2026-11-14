<article class="card-x">
    <div class="media">
        @if ($post->mediaUrl())
            <img src="{{ $post->mediaUrl() }}" alt="" loading="lazy">
        @else
            <div class="media-placeholder alt"><i class="bi bi-newspaper"></i></div>
        @endif
    </div>
    <div class="body">
        <div class="meta">
            <span><i class="bi bi-calendar3"></i>{{ $post->displayDate()->format('d M Y') }}</span>
            <span><i class="bi bi-clock"></i>{{ $post->readingMinutes() }} min read</span>
        </div>
        <h3><a href="{{ route('site.news.show', $post) }}">{{ $post->title }}</a></h3>
        <p>{{ Str::limit($post->summary(), 140) }}</p>
        <span class="link-arrow footer-link">Read article <i class="bi bi-arrow-right"></i></span>
    </div>
</article>
