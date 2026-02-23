@extends('adminlte::page')

@section('title','Candidates')

@section('content')

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
</tr>
</thead>

<tbody>

@forelse($candidates as $candidate)

<tr>
<td>{{ $candidate->member->full_name ?? '' }}</td>
<td>{{ $candidate->election->name ?? '' }}</td>
<td>{{ $candidate->position->name ?? '' }}</td>
</tr>

@empty

<tr>
<td colspan="3" class="text-center text-muted">
No candidates nominated
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection