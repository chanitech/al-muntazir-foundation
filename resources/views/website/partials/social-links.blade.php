@if (count($site->socialLinks()))
    <div class="social-links">
        @foreach ($site->socialLinks() as $link)
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener" aria-label="{{ $link['label'] }}" title="{{ $link['label'] }}"><i class="bi {{ $link['icon'] }}"></i></a>
        @endforeach
    </div>
@endif
