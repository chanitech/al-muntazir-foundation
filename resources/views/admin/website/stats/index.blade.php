@extends('adminlte::page')

@section('title', 'Impact Numbers')

@section('content_header')
    <x-admin.page-header title="Impact Numbers"
        subtitle="Headline figures shown in the “Our Impact” band (the first 4 visible ones are used)."
        :create-route="route('admin.stats.create')" create-label="Add Number" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-chart-bar"></i>
                <p class="mb-3">No numbers yet. Add figures such as members, programs run or communities reached.</p>
                <a href="{{ route('admin.stats.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Number</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px">Icon</th>
                            <th>Number</th>
                            <th>Label</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $stat)
                            <tr>
                                <td><i class="bi {{ $stat->icon ?: 'bi-bar-chart' }}" style="font-size: 1.4rem"></i></td>
                                <td><strong style="font-size: 1.15rem">{{ number_format($stat->value) }}{{ $stat->suffix }}</strong></td>
                                <td>{{ $stat->label }}</td>
                                <td class="text-center">{{ $stat->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.stats.toggle', $stat->id)" :active="$stat->is_active" /></td>
                                <td class="text-right">
                                    <x-admin.row-actions :edit="route('admin.stats.edit', $stat->id)"
                                        :destroy="route('admin.stats.destroy', $stat->id)" :name="'“'.$stat->label.'”'" />
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

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/website/bootstrap-icons/bootstrap-icons.min.css') }}">
@endpush
