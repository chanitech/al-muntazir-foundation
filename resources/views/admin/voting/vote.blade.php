@extends('adminlte::page')

@section('title','Vote')

@section('content')

<h3 class="mb-4">
    {{ $election->name }}
</h3>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
<div class="alert alert-danger">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

{{-- Voting Form --}}
<form action="{{ route('voting.store') }}" method="POST">

@csrf
@method('POST')

<input type="hidden" name="election_id" value="{{ $election->id }}">

<div class="card">

<div class="card-body">

@forelse($candidates as $positionId => $candidateGroup)

@php
$position = \App\Models\Position::find($positionId);
@endphp

<h5 class="text-primary mt-4 border-bottom pb-2">
    {{ $position->name ?? 'Position' }}
</h5>

<div class="row">

@foreach($candidateGroup as $candidate)

<div class="col-md-6">

<div class="card border p-3 mb-2 shadow-sm">

<div class="form-check">

<input class="form-check-input"
type="radio"
name="candidate_id"
value="{{ $candidate->id }}"
required>

<label class="form-check-label">
<strong>
{{ $candidate->member->full_name ?? 'Candidate' }}
</strong>
</label>

</div>

</div>

</div>

@endforeach

</div>

@empty

<div class="alert alert-info">
No candidates available for voting.
</div>

@endforelse

</div>

<div class="card-footer">
<button type="submit" class="btn btn-success">
<i class="fas fa-check"></i> Submit Vote
</button>
</div>

</div>

</form>

@endsection