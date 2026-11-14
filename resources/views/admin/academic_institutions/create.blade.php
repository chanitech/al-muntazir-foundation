@extends('adminlte::page')

@section('title','Academic Institution')

@section('content')

<div class="card">

<div class="card-header bg-primary">
Institution Form
</div>

<div class="card-body">

<form method="POST"
action="{{ isset($academicInstitution)
? route('academic-institutions.update',$academicInstitution)
: route('academic-institutions.store') }}">

@csrf

@if(isset($academicInstitution))
@method('PUT')
@endif

<div class="form-group">
<label>Name</label>

<input name="name"
class="form-control"
value="{{ $academicInstitution->name ?? '' }}"
required>
</div>

<div class="form-group">
<label>
<input type="checkbox"
name="is_active"
{{ isset($academicInstitution) && $academicInstitution->is_active ? 'checked' : '' }}>
 Active
</label>
</div>

<button class="btn btn-primary">
Save
</button>

<a href="{{ route('academic-institutions.index') }}"
class="btn btn-secondary">
Back
</a>

</form>

</div>

</div>

@endsection