@extends('adminlte::page')

@section('content')

<div class="card">

<div class="card-header">

<h3 class="card-title">Event List</h3>

<a href="{{ route('events.create') }}"
class="btn btn-primary btn-sm float-right">
Add Event
</a>

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>Title</th>
<th>Date</th>
<th>Category</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@foreach($events as $event)

<tr>
<td>{{ $event->title }}</td>
<td>{{ $event->event_date->format('Y-m-d') }}</td>
<td>{{ $event->category->name ?? '' }}</td>
<td>{{ ucfirst($event->status) }}</td>

<td>

<a class="btn btn-info btn-sm"
href="{{ route('events.edit',$event) }}">
Edit
</a>

<form style="display:inline"
action="{{ route('events.destroy',$event) }}"
method="POST">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm"
onclick="return confirm('Delete event?')">
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