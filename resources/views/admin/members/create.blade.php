@extends('adminlte::page')

@section('title','Create Member')

@section('content_header')
<h1>Member Registration</h1>
@stop

@section('content')

<div class="card card-primary">

<div class="card-header">
<h3 class="card-title">New Member Form</h3>
</div>

<form action="{{ route('admin.members.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="card-body">

{{-- ============================= --}}
{{-- Membership Section --}}
{{-- ============================= --}}
<h5 class="text-primary mb-3">Membership Information</h5>

<div class="row">

<div class="col-md-6 form-group">
<label>Membership Number</label>
<input name="membership_number"
       class="form-control"
       value="{{ \App\Models\Member::generateMembershipNumber() }}"
       readonly>
</div>

<div class="col-md-6 form-group">
<label>Join Date *</label>
<input type="date" name="join_date"
       class="form-control" required>
</div>

<div class="col-md-6 form-group">
<label>Membership Type *</label>

<select name="membership_type_id"
        class="form-control" required>

<option value="">Select Type</option>

@foreach($membershipTypes as $type)
<option value="{{ $type->id }}">
{{ $type->name }}
</option>
@endforeach

</select>
</div>

<div class="col-md-6 form-group">
<label>Status</label>

<select name="status" class="form-control">
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>

</div>

<hr>

{{-- ============================= --}}
{{-- Personal Information --}}
{{-- ============================= --}}
<h5 class="text-primary mb-3">Personal Information</h5>

<div class="row">

<div class="col-md-6 form-group">
<label>Full Name *</label>
<input name="full_name" class="form-control" required>
</div>

<div class="col-md-6 form-group">
<label>Date of Birth</label>
<input type="date" name="date_of_birth" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Place of Birth</label>
<input name="place_of_birth" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Nationality</label>
<input name="nationality" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Religion</label>
<input name="religion" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Tribe</label>
<input name="tribe" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>National ID</label>
<input name="national_id" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Phone *</label>
<input name="phone" class="form-control" required>
</div>

<div class="col-md-6 form-group">
<label>Email</label>
<input name="email" class="form-control">
</div>

<div class="col-md-12 form-group">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

</div>

<hr>

{{-- ============================= --}}
{{-- Marital & Family Section --}}
{{-- ============================= --}}
<h5 class="text-primary mb-3">Family Information</h5>

<div class="row">

<div class="col-md-6 form-group">
<label>Marital Status</label>

<select name="marital_status_id" class="form-control">
<option value="">None</option>

@foreach($maritalStatuses as $status)
<option value="{{ $status->id }}">
{{ $status->name }}
</option>
@endforeach

</select>
</div>

<div class="col-md-6 form-group">
<label>Spouse Name</label>
<input name="spouse_name" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Spouse Phone</label>
<input name="spouse_phone" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Children Count</label>
<input type="number"
       name="children_count"
       class="form-control"
       value="0">
</div>

</div>

<hr>

{{-- ============================= --}}
{{-- Photo Upload --}}
{{-- ============================= --}}
<h5 class="text-primary mb-3">Profile Photo</h5>

<div class="row">

<div class="col-md-6 form-group">
<label>Photo</label>
<input type="file"
       name="photo"
       class="form-control">
</div>

</div>

</div>

<div class="card-footer">
<button class="btn btn-primary">
Save Member
</button>

<a href="{{ route('admin.members.index') }}"
   class="btn btn-secondary">
Cancel
</a>
</div>

</form>

</div>

@stop