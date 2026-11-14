@extends('adminlte::page')

@section('title', 'Members')

@section('content_header')
<h1>Members</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card shadow">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @can('create members')
                    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Member
                    </a>
                @endcan
            </div>
            <div>
                @can('export members')
                    <a href="{{ route('admin.members.export', ['type' => 'csv']) }}?{{ http_build_query(request()->query()) }}" class="btn btn-success btn-sm">CSV</a>
                    <a href="{{ route('admin.members.export', ['type' => 'excel']) }}?{{ http_build_query(request()->query()) }}" class="btn btn-info btn-sm">Excel</a>
                    <a href="{{ route('admin.members.export', ['type' => 'pdf']) }}?{{ http_build_query(request()->query()) }}" class="btn btn-danger btn-sm">PDF</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- FILTER FORM --}}
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search name, number or phone" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status')=='Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status')=='Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">Member Type</option>
                        @foreach($memberTypes ?? [] as $type)
                            <option value="{{ $type->id }}" {{ request('type')==$type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="marital_status" class="form-control">
                        <option value="">Marital Status</option>
                        @foreach($maritalStatuses ?? [] as $status)
                            <option value="{{ $status->id }}" {{ request('marital_status')==$status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>

            {{-- Second row: employment & location filters --}}
            <div class="row mt-2">
                <div class="col-md-2">
                    <select name="employed" class="form-control">
                        <option value="">Employment</option>
                        <option value="1" {{ request('employed')=='1' ? 'selected' : '' }}>Employed</option>
                        <option value="0" {{ request('employed')=='0' ? 'selected' : '' }}>Not Employed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="employment_status" class="form-control">
                        <option value="">Employment Status</option>
                        <option value="full time" {{ request('employment_status')=='full time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part time" {{ request('employment_status')=='part time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ request('employment_status')=='contract' ? 'selected' : '' }}>Contract</option>
                        <option value="intern" {{ request('employment_status')=='intern' ? 'selected' : '' }}>Intern</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="region" class="form-control" placeholder="Region" value="{{ request('region') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="district" class="form-control" placeholder="District" value="{{ request('district') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="village" class="form-control" placeholder="Village/Street" value="{{ request('village') }}">
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Member No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Marital Status</th>
                        <th>Employment</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->membership_number }}</td>
                        <td>
                            @can('view members')
                                <a href="{{ route('admin.members.profile', $member) }}">
                                    {{ $member->full_name }}
                                </a>
                            @else
                                {{ $member->full_name }}
                            @endcan
                        </td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->membershipType->name ?? '-' }}</td>
                        <td>{{ $member->maritalStatus->name ?? '-' }}</td>
                        <td>
                            @if($member->is_employed)
                                <span class="badge badge-info">{{ ucfirst($member->employment_status) }}</span>
                                <small>{{ $member->employer_name }}</small>
                            @else
                                <span class="badge badge-secondary">Not employed</span>
                            @endif
                        </td>
                        <td>
                            @if($member->current_region || $member->current_district || $member->current_village)
                                {{ $member->current_village ?? '' }} {{ $member->current_district ?? '' }} {{ $member->current_region ?? '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $member->status == 'Active' ? 'success' : 'danger' }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                @can('view members')
                                    <a href="{{ route('admin.members.profile', $member) }}" class="btn btn-sm btn-info" title="Profile">
                                        <i class="fas fa-user"></i>
                                    </a>
                                @endcan
                                @can('edit members')
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete members')
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Delete this member?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted p-4">
                            No members found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@stop