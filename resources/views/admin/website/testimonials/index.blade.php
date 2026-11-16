@extends('adminlte::page')

@section('title', 'Testimonials')

@section('content_header')
    <x-admin.page-header title="Testimonials"
        subtitle="Quotes from members, parents and partners shown on the homepage."
        :create-route="route('admin.testimonials.create')" create-label="Add Testimonial" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('testimonial', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search testimonials…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-quote-left"></i>
                <p class="mb-3">{{ $search ? 'No testimonials match your search.' : 'No testimonials yet.' }}</p>
                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Testimonial</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 70px">Photo</th>
                            <th>Quote</th>
                            <th>By</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $testimonial)
                            <tr>
                                <td>
                                    @if ($testimonial->mediaUrl('photo'))
                                        <img src="{{ $testimonial->mediaUrl('photo') }}" alt="" class="admin-thumb round">
                                    @else
                                        <span class="admin-thumb round"><i class="fas fa-user"></i></span>
                                    @endif
                                </td>
                                <td class="text-wrap-tight"><em>“{{ Str::limit($testimonial->quote, 120) }}”</em></td>
                                <td><strong>{{ $testimonial->name }}</strong><div class="text-muted small">{{ $testimonial->role }}</div></td>
                                <td class="text-center">{{ $testimonial->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.testimonials.toggle', $testimonial->id)" :active="$testimonial->is_active" /></td>
                                <td class="text-right">
                                    <x-admin.row-actions :edit="route('admin.testimonials.edit', $testimonial->id)"
                                        :destroy="route('admin.testimonials.destroy', $testimonial->id)" name="this testimonial" />
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
