@extends('adminlte::page')

@section('title', 'Programs')

@section('content_header')
    <x-admin.page-header title="Programs"
        subtitle="Your activities and initiatives, shown on the homepage and the Programs page."
        :create-route="route('admin.programs.create')" create-label="Add Program" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('program', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search programs…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-hands-helping"></i>
                <p class="mb-3">{{ $search ? 'No programs match your search.' : 'No programs yet.' }}</p>
                <a href="{{ route('admin.programs.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Program</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 120px">Image</th>
                            <th>Program</th>
                            <th>Schedule</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $program)
                            <tr>
                                <td>
                                    @if ($program->mediaUrl())
                                        <img src="{{ $program->mediaUrl() }}" alt="" class="admin-thumb">
                                    @else
                                        <span class="admin-thumb"><i class="fas fa-hands-helping"></i></span>
                                    @endif
                                </td>
                                <td class="text-wrap-tight">
                                    <strong>{{ $program->title }}</strong>
                                    <div class="text-muted small">{{ Str::limit($program->summary, 100) }}</div>
                                </td>
                                <td class="small">{{ $program->schedule ?: '—' }}</td>
                                <td class="text-center">{{ $program->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.programs.toggle', $program->id)" :active="$program->is_active" /></td>
                                <td class="text-right text-nowrap">
                                    @if ($program->is_active)
                                        <a href="{{ route('site.programs.show', $program) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View on website"><i class="fas fa-external-link-alt"></i></a>
                                    @endif
                                    <x-admin.row-actions :edit="route('admin.programs.edit', $program->id)"
                                        :destroy="route('admin.programs.destroy', $program->id)" :name="'“'.$program->title.'”'" />
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
