{{-- Website overview on the admin dashboard (users with "manage website") --}}
@php
    $websiteStats = [
        ['Unread messages', \App\Models\ContactMessage::unread()->count(), 'fa-envelope', 'bg-danger', route('admin.messages.index', ['filter' => 'unread'])],
        ['Published articles', \App\Models\Post::published()->count(), 'fa-newspaper', 'bg-info', route('admin.posts.index')],
        ['Active slides', \App\Models\Slider::active()->count(), 'fa-images', 'bg-indigo', route('admin.sliders.index')],
        ['Newsletter subscribers', \App\Models\NewsletterSubscriber::count(), 'fa-at', 'bg-teal', route('admin.subscribers.index')],
    ];
@endphp

<div class="d-flex flex-wrap align-items-center mt-2 mb-2">
    <h5 class="mb-2 mr-3"><i class="fas fa-globe-africa mr-1 text-primary"></i> Website</h5>
    <div class="mb-2">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-sm btn-outline-primary mr-1 mb-1"><i class="fas fa-plus mr-1"></i> Slide</a>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-outline-primary mr-1 mb-1"><i class="fas fa-pen mr-1"></i> Article</a>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm btn-outline-primary mr-1 mb-1"><i class="fas fa-upload mr-1"></i> Photos</a>
        <a href="{{ route('admin.settings.edit') }}" class="btn btn-sm btn-outline-secondary mr-1 mb-1"><i class="fas fa-sliders-h mr-1"></i> Site settings</a>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-1"><i class="fas fa-external-link-alt mr-1"></i> View website</a>
    </div>
</div>

<div class="row">
    @foreach ($websiteStats as [$label, $count, $icon, $color, $link])
        <div class="col-lg-3 col-6">
            <div class="small-box {{ $color }}">
                <div class="inner">
                    <h3>{{ number_format($count) }}</h3>
                    <p>{{ $label }}</p>
                </div>
                <div class="icon"><i class="fas {{ $icon }}"></i></div>
                <a href="{{ $link }}" class="small-box-footer">Open <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    @endforeach
</div>
