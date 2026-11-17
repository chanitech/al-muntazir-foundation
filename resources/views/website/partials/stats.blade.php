@if ($stats->isNotEmpty())
    <section class="stats-band section-sm" aria-label="{{ $site->stats_title }}">
        <div class="container">
            <div class="text-center mb-4 reveal">
                <span class="eyebrow" style="color: var(--accent)">{{ $site->stats_title }}</span>
            </div>
            <div class="row stats-row justify-content-center">
                @foreach ($stats as $stat)
                    <div class="col-6 col-md-3 reveal reveal-delay-{{ min($loop->index, 3) }}">
                        <div class="stat">
                            @if ($stat->icon)<div class="icon"><i class="bi {{ $stat->icon }}"></i></div>@endif
                            <div class="number"><span data-count="{{ $stat->value }}">{{ number_format($stat->value) }}</span>{{ $stat->suffix }}</div>
                            <div class="label">{{ $stat->label }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
