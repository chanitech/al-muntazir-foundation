@extends('adminlte::page')

@section('title','Create Event')

@section('content')

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4>Create Event</h4>
</div>

<form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
@csrf

<div class="card-body">

{{-- Category --}}
<div class="form-group">
<label>Event Category</label>

<select name="event_category_id" class="form-control" required>
<option value="">Select Category</option>

@foreach($categories as $category)
<option value="{{ $category->id }}">
{{ $category->name }}
</option>
@endforeach

</select>
</div>

{{-- Title --}}
<div class="form-group mt-3">
<label>Event Title</label>
<input type="text" name="title" class="form-control" required>
</div>

{{-- Description --}}
<div class="form-group mt-3">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

{{-- Date & Time --}}
<div class="row mt-3">

<div class="col-md-4">
<label>Event Date</label>
<input type="date" name="event_date" class="form-control" required>
</div>

<div class="col-md-4">
<label>Start Time</label>
<input type="time" name="start_time" class="form-control">
</div>

<div class="col-md-4">
<label>End Time</label>
<input type="time" name="end_time" class="form-control">
</div>

</div>

{{-- Location --}}
<div class="form-group mt-3">
<label>Location</label>
<input type="text" name="location" class="form-control">
</div>

{{-- Capacity --}}
<div class="form-group mt-3">
<label>Capacity</label>
<input type="number" name="capacity" class="form-control">
</div>

{{-- Status --}}
<div class="form-group mt-3">
<label>Status</label>

<select name="status" class="form-control" required>
<option value="upcoming">Upcoming</option>
<option value="draft">Draft</option>
<option value="completed">Completed</option>
<option value="cancelled">Cancelled</option>
</select>

</div>

@include('events._website-fields', ['event' => null])

<hr>

{{-- SMS Template --}}
<div class="form-group mt-3">
<label>SMS Engagement Template</label>

<p class="text-muted small">
Use placeholders like:<br>
{title} | {event_date} | {location}
</p>

<textarea
name="sms_template"
class="form-control"
rows="6"
placeholder="Write SMS message for members..."
></textarea>

</div>

</div>

<div class="card-footer">
<button class="btn btn-primary">
<i class="fas fa-save"></i>
Save Event
</button>
</div>

</form>

</div>

@endsection