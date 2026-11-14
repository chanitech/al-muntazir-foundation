@extends('adminlte::page')

@section('title','Add Academic Background')

@section('content')

<div class="card">

<div class="card-header bg-primary">
Add Academic Record
</div>

<form action="{{ route('academic.store') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<div class="card-body">

<div class="form-group">
<label>Member</label>

<select name="member_id" class="form-control" required>
<option value="">Select Member</option>

@foreach($members as $member)
<option value="{{ $member->id }}">
{{ $member->full_name }}
</option>
@endforeach

</select>
</div>

<div class="form-group">
<label>Institution Name</label>
<input name="institution_name" class="form-control" required>
</div>

<div class="form-group">
<label>Education Level</label>
<input name="education_level" class="form-control" required>
</div>

<div class="row">

<div class="col-md-6">
<label>Start Year</label>
<input name="start_year" class="form-control">
</div>

<div class="col-md-6">
<label>End Year</label>
<input name="end_year" class="form-control">
</div>

</div>

<div class="form-group mt-2">
<label>Certificate File</label>
<input type="file" name="certificate_file" class="form-control">
</div>

</div>

<div class="card-footer">

<button class="btn btn-primary">
Save
</button>

</div>

</form>

</div>

@endsection