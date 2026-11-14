@extends('adminlte::page')

@section('title','Membership Type')

@section('content')

<div class="card">

<div class="card-header bg-primary">
Membership Type Form
</div>

<div class="card-body">

<form method="POST"
action="{{ isset($membershipType)
? route('membership-types.update',$membershipType)
: route('membership-types.store') }}">

@csrf

@if(isset($membershipType))
@method('PUT')
@endif

<div class="form-group">
<label>Type Name</label>

<input name="name"
class="form-control"
value="{{ $membershipType->name ?? '' }}"
required>
</div>

<div class="form-group">
<label>Description</label>

<textarea name="description"
class="form-control">{{ $membershipType->description ?? '' }}</textarea>
</div>

<div class="form-group">

<label>
<input type="checkbox"
name="is_active"
{{ isset($membershipType) && $membershipType->is_active ? 'checked' : '' }}>
 Active
</label>

</div>

<button class="btn btn-primary">
Save
</button>

<a href="{{ route('membership-types.index') }}"
class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>

@endsection