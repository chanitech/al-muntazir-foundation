@extends('adminlte::page')

@section('title','Elections')

@section('content')

<x-admin.flash />

<div class="card">

<div class="card-header d-flex justify-content-between">

<h5>Elections</h5>

@can('create elections')
<a href="{{ route('elections.create') }}"
class="btn btn-primary btn-sm">
Create Election
</a>
@endcan

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Status</th>
<th class="text-right">Actions</th>
</tr>
</thead>

<tbody>

@forelse($elections as $election)

<tr>
<td>{{ $election->name }}</td>
<td>{{ $election->start_time?->format('d M Y, H:i') }}</td>
<td>{{ $election->end_time?->format('d M Y, H:i') }}</td>
<td>
@if($election->isOpen())
<span class="badge badge-success">Voting open</span>
@elseif($election->is_active && $election->start_time?->isFuture())
<span class="badge badge-info">Not started</span>
@elseif($election->is_active)
<span class="badge badge-secondary">Ended</span>
@else
<span class="badge badge-danger">Inactive</span>
@endif
</td>
<td class="text-right">
@can('edit elections')
<a href="{{ route('elections.edit', $election) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-pen"></i></a>
@endcan
</td>
</tr>

@empty

<tr>
<td colspan="5" class="text-center text-muted">
No election found
</td>
</tr>

@endforelse

</tbody>

</table>

<div class="mt-3">{{ $elections->links('pagination::bootstrap-4') }}</div>

</div>

</div>

@endsection