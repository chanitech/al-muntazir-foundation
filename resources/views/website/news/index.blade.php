@extends('website.layouts.app')

@section('title', $site->news_title)

@section('content')
    @include('website.partials.page-hero', ['title' => $site->news_title, 'subtitle' => 'News, stories and announcements from our community.'])

    <section class="section">
        <div class="container">
            <form method="GET" class="row justify-content-center mb-5" role="search">
                <div class="col-md-8 col-lg-6">
                    <div class="input-group input-group-lg">
                        <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search news…" aria-label="Search news">
                        <button class="btn btn-brand" type="submit" style="border-radius: 0 10px 10px 0"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>

            @if ($posts->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-newspaper"></i>
                    <p class="mb-0">{{ $search ? 'No articles match “'.$search.'”.' : 'News will be published here soon.' }}</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index % 3 }}">
                            @include('website.partials.post-card', ['post' => $post])
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $posts->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </section>
@endsection
