@extends('adminlte::page')

@section('title','Members')

@section('content_header')
    <h1>Members</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

<div class="card-header d-flex justify-content-between align-items-center">

<a href="{{ route('admin.members.create') }}"
class="btn btn-primary">
<i class="fas fa-plus"></i> Add Member
</a>

</div>

<div class="card-body table-responsive">

<table class="table table-hover table-bordered">

<thead class="bg-light">
<tr>
<th width="40">#</th>
<th>Member No</th>
<th>Name</th>
<th>Phone</th>
<th>Type</th>
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
<a href="{{ route('admin.members.profile',$member) }}">
{{ $member->full_name }}
</a>
</td>

<td>{{ $member->phone }}</td>

<td>{{ $member->membershipType->name ?? '-' }}</td>

<td>
<span class="badge badge-success">
{{ $member->status }}
</span>
</td>

<td>

<div class="btn-group">

<a href="{{ route('admin.members.profile',$member) }}"
class="btn btn-sm btn-info">
<i class="fas fa-user"></i>
</a>

<a href="{{ route('admin.members.edit',$member) }}"
class="btn btn-sm btn-warning">
<i class="fas fa-edit"></i>
</a>

<form action="{{ route('admin.members.destroy',$member) }}"
method="POST"
onsubmit="return confirm('Delete this member?')">

@csrf
@method('DELETE')

<button class="btn btn-sm btn-danger">
<i class="fas fa-trash"></i>
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="7" class="text-center text-muted p-4">
No members found.
</td>
</tr>

@endforelse

</tbody>

</table>

<div class="mt-3">
{{ $members->links() }}
</div>

</div>
</div>

@stop