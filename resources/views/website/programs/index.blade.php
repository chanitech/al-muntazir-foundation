@extends('website.layouts.app')

@section('title', $site->programs_title)
@section('meta_description', $site->programs_intro)

@section('content')
    @include('website.partials.page-hero', ['title' => $site->programs_title, 'subtitle' => $site->programs_intro])

    <section class="section">
        <div class="container">
            @if ($programs->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-stars"></i>
                    <p class="mb-0">Our programs will be published here soon.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($programs as $program)
                        <div class="col-md-6 col-lg-4 reveal reveal-delay-{{ $loop->index % 3 }}">
                            @include('website.partials.program-card', ['program' => $program])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('website.partials.cta')
@endsection
