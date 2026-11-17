@extends('adminlte::page')

@section('title', 'Photo Gallery')

@section('content_header')
    <x-admin.page-header title="Photo Gallery"
        subtitle="Photos for the website's Gallery page and the homepage preview."
        :create-route="route('admin.gallery.create')" create-label="Upload Photos" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('photo', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search title or album…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-camera"></i>
                <p class="mb-3">{{ $search ? 'No photos match your search.' : 'No photos yet. You can upload several at once.' }}</p>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary"><i class="fas fa-upload mr-1"></i> Upload Photos</a>
            </div>
        @else
            <div class="card-body">
                <div class="row gallery-admin-grid">
                    @foreach ($items as $photo)
                        <div class="col-6 col-md-4 col-xl-3 mb-4">
                            <div class="card h-100 mb-0 shadow-sm" @style(['opacity: .6' => ! $photo->is_active])>
                                <img src="{{ $photo->mediaUrl() }}" alt="{{ $photo->title }}" class="card-img-top" loading="lazy">
                                <div class="card-body p-2">
                                    <div class="small font-weight-bold text-truncate">{{ $photo->title ?: 'Untitled' }}</div>
                                    <div class="small text-muted text-truncate">{{ $photo->category ?: 'No album' }} · #{{ $photo->sort_order }}</div>
                                </div>
                                <div class="card-footer p-2 d-flex align-items-center bg-white">
                                    <x-admin.status-toggle :action="route('admin.gallery.toggle', $photo->id)" :active="$photo->is_active" />
                                    <div class="ml-auto">
                                        <x-admin.row-actions :edit="route('admin.gallery.edit', $photo->id)"
                                            :destroy="route('admin.gallery.destroy', $photo->id)" name="this photo" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($items->hasPages())
                <div class="card-footer">{{ $items->links('pagination::bootstrap-4') }}</div>
            @endif
        @endif
    </div>
@stop
