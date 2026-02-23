@extends('adminlte::page')

@section('title','Create Election')

@section('content')

<div class="card">

<div class="card-header">
<h5>Create Election</h5>
</div>

<form action="{{ route('elections.store') }}" method="POST">
@csrf

<div class="card-body">

<div class="form-group">
<label>Election Name</label>
<input name="name" class="form-control" required>
</div>

<div class="form-group">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="form-group">
<label>Start Time</label>
<input type="datetime-local" name="start_time" class="form-control" required>
</div>

<div class="form-group">
<label>End Time</label>
<input type="datetime-local" name="end_time" class="form-control" required>
</div>

</div>

<div class="card-footer">
<button class="btn btn-success">
Save Election
</button>
</div>

</form>

</div>

@endsection