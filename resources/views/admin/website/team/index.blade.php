@extends('adminlte::page')

@section('title', 'Team')

@section('content_header')
    <x-admin.page-header title="Team"
        subtitle="Leaders, mentors and coordinators shown on the About page."
        :create-route="route('admin.team.create')" create-label="Add Person" />
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ $items->total() }} {{ Str::plural('person', $items->total()) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search team…" /></div>
        </div>

        @if ($items->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-user-tie"></i>
                <p class="mb-3">{{ $search ? 'Nobody matches your search.' : 'No team members yet.' }}</p>
                <a href="{{ route('admin.team.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Person</a>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 70px">Photo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th class="text-center">Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $person)
                            <tr>
                                <td>
                                    @if ($person->mediaUrl('photo'))
                                        <img src="{{ $person->mediaUrl('photo') }}" alt="" class="admin-thumb round">
                                    @else
                                        <span class="admin-thumb round font-weight-bold text-secondary">{{ $person->initials() }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $person->name }}</strong></td>
                                <td>{{ $person->position ?: '—' }}</td>
                                <td class="text-center">{{ $person->sort_order }}</td>
                                <td><x-admin.status-toggle :action="route('admin.team.toggle', $person->id)" :active="$person->is_active" /></td>
                                <td class="text-right">
                                    <x-admin.row-actions :edit="route('admin.team.edit', $person->id)"
                                        :destroy="route('admin.team.destroy', $person->id)" :name="$person->name" />
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
