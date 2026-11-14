@extends('adminlte::page')

@section('content')

<div class="card">

<div class="card-header">
<h3>Edit Event</h3>
</div>

<div class="card-body">

<form action="{{ route('events.update',$event) }}" method="POST">
@csrf
@method('PUT')

<input type="hidden" name="event_category_id"
value="{{ $event->event_category_id }}">

<div class="form-group">
<label>Title</label>
<input type="text"
name="title"
value="{{ $event->title }}"
class="form-control">
</div>

<div class="form-group">
<label>Event Date</label>
<input type="date"
name="event_date"
value="{{ $event->event_date->format('Y-m-d') }}"
class="form-control">
</div>

<div class="form-group">
<label>Status</label>
<select name="status" class="form-control">

@foreach(\App\Models\Event::STATUSES as $status)

<option value="{{ $status }}"
@if($event->status==$status) selected @endif>
{{ ucfirst($status) }}
</option>

@endforeach

</select>
</div>

<br>

<button class="btn btn-success">
Update Event
</button>

</form>

</div>
</div>

@endsection