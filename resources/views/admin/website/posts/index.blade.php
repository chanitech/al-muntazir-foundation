@extends('adminlte::page')

@section('title', 'News & Articles')

@section('content_header')
    <x-admin.page-header title="News & Articles"
        subtitle="News, announcements and stories shown on the website's News page."
        :create-route="route('admin.posts.create')" create-label="Write Article" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('article', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search articles…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-newspaper"></i>
                <p class="mb-3">{{ $search ? 'No articles match your search.' : 'No articles yet. Share your first news update.' }}</p>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="fas fa-pen mr-1"></i> Write Article</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 120px">Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $post)
                            <tr>
                                <td>
                                    @if ($post->mediaUrl())
                                        <img src="{{ $post->mediaUrl() }}" alt="" class="admin-thumb">
                                    @else
                                        <span class="admin-thumb"><i class="far fa-newspaper"></i></span>
                                    @endif
                                </td>
                                <td class="text-wrap-tight">
                                    <strong>{{ $post->title }}</strong>
                                    <div class="text-muted small">{{ Str::limit($post->summary(), 100) }}</div>
                                </td>
                                <td class="small text-nowrap">
                                    {{ $post->displayDate()->format('d M Y') }}
                                    @if ($post->is_published && $post->published_at?->isFuture())
                                        <div><span class="badge badge-info">Scheduled</span></div>
                                    @endif
                                </td>
                                <td>
                                    <x-admin.status-toggle :action="route('admin.posts.toggle', $post->id)" :active="$post->is_published" on="Published" off="Draft" />
                                </td>
                                <td class="text-right text-nowrap">
                                    @if ($post->isLive())
                                        <a href="{{ route('site.news.show', $post) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View on website"><i class="fas fa-external-link-alt"></i></a>
                                    @endif
                                    <x-admin.row-actions :edit="route('admin.posts.edit', $post->id)"
                                        :destroy="route('admin.posts.destroy', $post->id)" :name="'“'.$post->title.'”'" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($items->hasPages())
                <div class="card-footer">{{ $items->links('pagination::bootstrap-4') }}</div>
            @endif
        @endif
    </div>
@stop
