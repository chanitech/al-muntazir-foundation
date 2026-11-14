@extends('adminlte::page')

@section('title','Membership Types')

@section('content')

<div class="card">

<div class="card-header bg-primary">
<h5 class="mb-0">Membership Types</h5>
</div>

<div class="card-body">

<a href="{{ route('membership-types.create') }}"
class="btn btn-success mb-3">
+ Add Membership Type
</a>

<table class="table table-bordered">

<thead class="bg-light">
<tr>
<th>Name</th>
<th>Status</th>
<th width="180">Action</th>
</tr>
</thead>

<tbody>

@foreach($types as $type)

<tr>

<td>{{ $type->name }}</td>

<td>
@if($type->is_active)
<span class="badge badge-success">Active</span>
@else
<span class="badge badge-danger">Inactive</span>
@endif
</td>

<td>

<a href="{{ route('membership-types.edit',$type) }}"
class="btn btn-sm btn-info">
Edit
</a>

<form action="{{ route('membership-types.destroy',$type) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button class="btn btn-sm btn-danger"
onclick="return confirm('Delete this record?')">
Delete
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection