{{-- Picker for a Bootstrap Icons class (used by programs and impact numbers) --}}
@props(['name' => 'icon', 'label' => 'Icon', 'value' => null, 'help' => null])

@php
    $icons = [
        'bi-people' => 'People', 'bi-person-raised-hand' => 'Volunteer', 'bi-mortarboard' => 'Education',
        'bi-book' => 'Book', 'bi-journal-text' => 'Journal', 'bi-lightbulb' => 'Ideas', 'bi-chat-dots' => 'Dialogue',
        'bi-megaphone' => 'Advocacy', 'bi-heart' => 'Charity', 'bi-hand-thumbs-up' => 'Support', 'bi-trophy' => 'Achievement',
        'bi-award' => 'Award', 'bi-star' => 'Star', 'bi-globe2' => 'Community', 'bi-house-heart' => 'Family',
        'bi-briefcase' => 'Career', 'bi-laptop' => 'Technology', 'bi-palette' => 'Arts', 'bi-music-note-beamed' => 'Music',
        'bi-camera' => 'Media', 'bi-calendar-event' => 'Events', 'bi-geo-alt' => 'Location', 'bi-tree' => 'Environment',
        'bi-heart-pulse' => 'Health', 'bi-moon-stars' => 'Faith', 'bi-shield-check' => 'Safety', 'bi-bullseye' => 'Goals',
        'bi-graph-up-arrow' => 'Growth', 'bi-cash-coin' => 'Finance', 'bi-diagram-3' => 'Network', 'bi-flag' => 'Leadership',
    ];
    $current = old($name, $value);
@endphp

<div class="form-group">
    <label for="f_{{ $name }}">{{ $label }}</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width: 46px; justify-content: center">
                <i class="bi {{ $current ?: 'bi-question' }}" id="f_{{ $name }}_preview" style="font-size: 1.15rem"></i>
            </span>
        </div>
        <select name="{{ $name }}" id="f_{{ $name }}" class="form-control @error($name) is-invalid @enderror"
                onchange="document.getElementById('f_{{ $name }}_preview').className = 'bi ' + (this.value || 'bi-question')">
            <option value="">— No icon —</option>
            @foreach ($icons as $class => $iconLabel)
                <option value="{{ $class }}" @selected($current === $class)>{{ $iconLabel }}</option>
            @endforeach
            @if ($current && ! array_key_exists($current, $icons))
                <option value="{{ $current }}" selected>{{ $current }}</option>
            @endif
        </select>
    </div>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

@once
    @push('css')
        <link rel="stylesheet" href="{{ asset('vendor/website/bootstrap-icons/bootstrap-icons.min.css') }}">
    @endpush
@endonce
