@if ($partners->isNotEmpty())
    <section class="section-sm border-top" aria-label="Partners">
        <div class="container">
            <p class="text-center text-muted fw-semibold mb-4 reveal">Proudly working with</p>
            <div class="partners-row reveal">
                @foreach ($partners as $partner)
                    @php $tag = $partner->url ? 'a' : 'div'; @endphp
                    <{{ $tag }} class="partner-logo" @if($partner->url) href="{{ $partner->url }}" target="_blank" rel="noopener" @endif title="{{ $partner->name }}">
                        @if ($partner->mediaUrl('logo'))
                            <img src="{{ $partner->mediaUrl('logo') }}" alt="{{ $partner->name }}" loading="lazy">
                        @else
                            <span class="partner-name">{{ $partner->name }}</span>
                        @endif
                    </{{ $tag }}>
                @endforeach
            </div>
        </div>
    </section>
@endif
