@extends('adminlte::page')

@section('title', 'Homepage Slider')

@section('content_header')
    <x-admin.page-header title="Homepage Slider"
        subtitle="The large images that rotate at the top of the homepage."
        :create-route="route('admin.sliders.create')" create-label="Add Slide" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('slide', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search slides…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-images"></i>
                <p class="mb-3">{{ $search ? 'No slides match your search.' : 'No slides yet. Add your first homepage slide.' }}</p>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Slide</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 120px">Image</th>
                            <th>Title</th>
                            <th>Button</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $slide)
                            <tr>
                                <td>
                                    @if ($slide->mediaUrl())
                                        <img src="{{ $slide->mediaUrl() }}" alt="" class="admin-thumb">
                                    @else
                                        <span class="admin-thumb"><i class="far fa-image"></i></span>
                                    @endif
                                </td>
                                <td class="text-wrap-tight">
                                    <strong>{{ $slide->title ?: '(no title)' }}</strong>
                                    @if ($slide->subtitle)
                                        <div class="text-muted small">{{ Str::limit($slide->subtitle, 90) }}</div>
                                    @endif
                                </td>
                                <td class="small">
                                    @if ($slide->button_text)
                                        <span class="badge badge-light border">{{ $slide->button_text }}</span>
                                        <div class="text-muted">{{ Str::limit($slide->button_link, 40) }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $slide->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.sliders.toggle', $slide->id)" :active="$slide->is_active" /></td>
                                <td class="text-right">
                                    <x-admin.row-actions :edit="route('admin.sliders.edit', $slide->id)"
                                        :destroy="route('admin.sliders.destroy', $slide->id)" name="this slide" />
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
