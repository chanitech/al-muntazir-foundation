@extends('adminlte::page')

@section('title', 'Partners')

@section('content_header')
    <x-admin.page-header title="Partners"
        subtitle="Logos of partners and supporters shown on the homepage and About page."
        :create-route="route('admin.partners.create')" create-label="Add Partner" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('partner', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search partners…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-handshake"></i>
                <p class="mb-3">{{ $search ? 'No partners match your search.' : 'No partners yet.' }}</p>
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Partner</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 120px">Logo</th>
                            <th>Name</th>
                            <th>Website</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $partner)
                            <tr>
                                <td>
                                    @if ($partner->mediaUrl('logo'))
                                        <img src="{{ $partner->mediaUrl('logo') }}" alt="" class="admin-thumb contain">
                                    @else
                                        <span class="admin-thumb"><i class="fas fa-handshake"></i></span>
                                    @endif
                                </td>
                                <td><strong>{{ $partner->name }}</strong></td>
                                <td class="small">
                                    @if ($partner->url)
                                        <a href="{{ $partner->url }}" target="_blank" rel="noopener">{{ Str::limit(preg_replace('#^https?://(www\.)?#', '', $partner->url), 40) }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">{{ $partner->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.partners.toggle', $partner->id)" :active="$partner->is_active" /></td>
                                <td class="text-right">
                                    <x-admin.row-actions :edit="route('admin.partners.edit', $partner->id)"
                                        :destroy="route('admin.partners.destroy', $partner->id)" :name="$partner->name" />
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
