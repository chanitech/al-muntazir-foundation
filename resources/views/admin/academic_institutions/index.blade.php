@extends('adminlte::page')

@section('title','Academic Institutions')

@section('content')

<div class="card">

<div class="card-header bg-primary">
Academic Institutions
</div>

<div class="card-body">

<a href="{{ route('academic-institutions.create') }}"
class="btn btn-success mb-3">
+ Add Institution
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>Name</th>
<th>Status</th>
<th width="160">Action</th>
</tr>
</thead>

<tbody>

@foreach($institutions as $inst)

<tr>
<td>{{ $inst->name }}</td>

<td>
@if($inst->is_active)
<span class="badge badge-success">Active</span>
@else
<span class="badge badge-danger">Inactive</span>
@endif
</td>

<td>

<a class="btn btn-sm btn-info"
href="{{ route('academic-institutions.edit',$inst) }}">
Edit
</a>

<form method="POST"
action="{{ route('academic-institutions.destroy',$inst) }}"
style="display:inline">

@csrf
@method('DELETE')

<button class="btn btn-sm btn-danger"
onclick="return confirm('Delete?')">
Delete
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

{{ $institutions->links() }}

</div>

</div>

@endsection