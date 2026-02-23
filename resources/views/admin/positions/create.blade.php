@extends('adminlte::page')

@section('title','Create Position')

@section('content')

<div class="card">

<div class="card-header">
<h5>Create Position</h5>
</div>

<form method="POST" action="{{ route('positions.store') }}">
@csrf

<div class="card-body">

<div class="form-group">
<label>Position Name</label>
<input name="name" class="form-control" required>
</div>

</div>

<div class="card-footer">
<button class="btn btn-success">
Save
</button>
</div>

</form>

</div>

@endsection