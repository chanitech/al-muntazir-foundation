@extends('adminlte::page')

@section('title','Nominate Candidate')

@section('content')

<div class="card">

<div class="card-header">
<h5>Candidate Nomination</h5>
</div>

<form action="{{ route('candidates.store') }}" method="POST">
@csrf

<div class="card-body">

<div class="form-group">
<label>Member</label>
<select name="member_id" class="form-control">
@foreach($members as $member)
<option value="{{ $member->id }}">
{{ $member->full_name }}
</option>
@endforeach
</select>
</div>

<div class="form-group">
<label>Election</label>
<select name="election_id" class="form-control">
@foreach($elections as $election)
<option value="{{ $election->id }}">
{{ $election->name }}
</option>
@endforeach
</select>
</div>

<div class="form-group">
<label>Position</label>
<select name="position_id" class="form-control">
@foreach($positions as $position)
<option value="{{ $position->id }}">
{{ $position->name }}
</option>
@endforeach
</select>
</div>

<div class="form-group">
<label>Manifesto</label>
<textarea name="manifesto" class="form-control"></textarea>
</div>

</div>

<div class="card-footer">
<button class="btn btn-success">
Save Candidate
</button>
</div>

</form>

</div>

@endsection