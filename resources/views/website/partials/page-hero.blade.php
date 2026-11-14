{{-- Header band for inner pages. Variables: $title, $subtitle (optional), $crumbs (optional [label => url]), $image (optional) --}}
<section class="page-hero {{ !empty($image) ? 'has-image' : '' }}">
    @if (!empty($image))
        <div class="bg"><img src="{{ $image }}" alt=""></div>
    @endif
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @foreach (($crumbs ?? []) as $label => $url)
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($title, 60) }}</li>
            </ol>
        </nav>
        <h1>{{ $title }}</h1>
        @if (!empty($subtitle))
            <p>{{ $subtitle }}</p>
        @endif
    </div>
</section>
