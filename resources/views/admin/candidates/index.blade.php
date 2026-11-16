@extends('adminlte::page')

@section('title','Candidates')

@section('content')

<x-admin.flash />

<div class="card">

<div class="card-header d-flex justify-content-between">
<h5>Candidates</h5>

<a href="{{ route('candidates.create') }}"
class="btn btn-primary btn-sm">
Nominate Candidate
</a>
</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>Member</th>
<th>Election</th>
<th>Position</th>
<th class="text-right">Actions</th>
</tr>
</thead>

<tbody>

@forelse($candidates as $candidate)

<tr>
<td>{{ $candidate->member->full_name ?? '' }}</td>
<td>{{ $candidate->election->name ?? '' }}</td>
<td>{{ $candidate->position->name ?? '' }}</td>
<td class="text-right">
@can('delete candidates')
<form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this nomination?')">
@csrf
@method('DELETE')
<button class="btn btn-sm btn-outline-danger" title="Remove nomination"><i class="fas fa-trash"></i></button>
</form>
@endcan
</td>
</tr>

@empty

<tr>
<td colspan="4" class="text-center text-muted">
No candidates nominated
</td>
</tr>

@endforelse

</tbody>

</table>

<div class="mt-3">{{ $candidates->links('pagination::bootstrap-4') }}</div>

</div>

</div>

@endsection