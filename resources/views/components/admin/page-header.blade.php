{{-- Title row for admin list pages with an optional "Add" button --}}
@props(['title', 'subtitle' => null, 'createRoute' => null, 'createLabel' => 'Add New'])

<div class="d-flex flex-wrap justify-content-between align-items-center">
    <div class="mb-2">
        <h1 class="m-0">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-muted mb-0 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="mb-2">
        {{ $slot }}
        @if ($createRoute)
            <a href="{{ $createRoute }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> {{ $createLabel }}</a>
        @endif
    </div>
</div>
