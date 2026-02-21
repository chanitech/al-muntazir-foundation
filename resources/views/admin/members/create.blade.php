@extends('adminlte::page')

@section('title','Create Member')

@section('content_header')
<h1>Create Member</h1>
@stop

@section('content')

<div class="card card-primary">

<div class="card-header">
<h3 class="card-title">Member Registration</h3>
</div>

<form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="card-body">

{{-- Tabs Navigation --}}
<ul class="nav nav-tabs" id="memberTab">

<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#personal">Personal Info</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#family">Family Info</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#academic">Academic</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#professional">Professional</a>
</li>

</ul>

<div class="tab-content mt-3">

{{-- ================= PERSONAL TAB ================= --}}
<div class="tab-pane fade show active" id="personal">

<div class="row">

<div class="col-md-6 form-group">
<label>Full Name *</label>
<input name="full_name" class="form-control" required>
</div>

<div class="col-md-6 form-group">
<label>Phone *</label>
<input name="phone" class="form-control" required>
</div>

<div class="col-md-6 form-group">
<label>Email</label>
<input name="email" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Date of Birth</label>
<input type="date" name="date_of_birth" class="form-control">
</div>

<div class="col-md-12 form-group">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

<div class="col-md-6 form-group">
<label>Photo</label>
<input type="file" name="photo" class="form-control">
</div>

</div>

</div>

{{-- ================= FAMILY TAB ================= --}}
<div class="tab-pane fade" id="family">

<div class="row">

<div class="col-md-6 form-group">
<label>Father Name</label>
<input name="father_name" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Father Occupation</label>
<input name="father_occupation" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Mother Name</label>
<input name="mother_name" class="form-control">
</div>

<div class="col-md-6 form-group">
<label>Mother Occupation</label>
<input name="mother_occupation" class="form-control">
</div>

</div>

</div>

{{-- ================= ACADEMIC TAB ================= --}}
<div class="tab-pane fade" id="academic">

<p class="text-muted">You can add academic records later from profile page.</p>

</div>

{{-- ================= PROFESSIONAL TAB ================= --}}
<div class="tab-pane fade" id="professional">

<p class="text-muted">Professional experience can be added later.</p>

</div>

</div>

</div>

<div class="card-footer">
<button class="btn btn-primary">
Save Member
</button>

<a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
Cancel
</a>
</div>

</form>

</div>

@stop

@section('js')
<script>
$(function(){
    $('#memberTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
})
</script>
@stop