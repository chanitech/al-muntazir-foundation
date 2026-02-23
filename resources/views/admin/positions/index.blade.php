@extends('adminlte::page')

@section('title','Positions')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between">
<h5>Positions</h5>

<a href="{{ route('positions.create') }}"
class="btn btn-primary btn-sm">
Add Position
</a>
</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>Name</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@forelse($positions as $position)

<tr>
<td>{{ $position->name }}</td>

<td>
<a href="{{ route('positions.edit',$position) }}"
class="btn btn-warning btn-sm">
Edit
</a>

<form action="{{ route('positions.destroy',$position) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm"
onclick="return confirm('Delete?')">
Delete
</button>

</form>
</td>

</tr>

@empty

<tr>
<td colspan="2" class="text-center">
No position found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>

@endsection