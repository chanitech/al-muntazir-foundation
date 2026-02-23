@extends('adminlte::page')

@section('title','Elections')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between">

<h5>Elections</h5>

<a href="{{ route('elections.create') }}"
class="btn btn-primary btn-sm">
Create Election
</a>

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Status</th>
</tr>
</thead>

<tbody>

@forelse($elections as $election)

<tr>
<td>{{ $election->name }}</td>
<td>{{ $election->start_time }}</td>
<td>{{ $election->end_time }}</td>
<td>
@if($election->is_active)
<span class="badge badge-success">Active</span>
@else
<span class="badge badge-danger">Inactive</span>
@endif
</td>
</tr>

@empty

<tr>
<td colspan="4" class="text-center text-muted">
No election found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection