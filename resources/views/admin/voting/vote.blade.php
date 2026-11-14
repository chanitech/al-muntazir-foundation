@extends('adminlte::page')

@section('title','Vote')

@section('content')

<h3 class="mb-4">
    {{ $election->name }}
</h3>

{{-- Messages --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('voting.store') }}" method="POST">
@csrf

<input type="hidden" name="election_id" value="{{ $election->id }}">

<div class="card">

<div class="card-body">

@forelse($candidates as $positionId => $candidateGroup)

@php
$position = \App\Models\Position::find($positionId);
@endphp

{{-- Position Header --}}
<h5 class="text-primary mt-4 border-bottom pb-2">
    {{ $position->name ?? 'Position' }}
</h5>

<div class="row">

@foreach($candidateGroup as $candidate)

@php
$member = $candidate->member;
@endphp

<div class="col-md-4 mb-3">

<div class="card shadow-sm border h-100">

<div class="card-body text-center">

{{-- Candidate Photo --}}
<img
src="{{ $member->photo_url ?? asset('images/default.png') }}"
class="img-fluid rounded-circle mb-3"
style="width:200px;height:200px;object-fit:cover">

{{-- Candidate Name --}}
<h2 class="font-weight-bold">
{{ $member->full_name ?? 'Candidate' }}
</h2>

{{-- Manifesto Preview --}}
@if(!empty($candidate->manifesto))
<p class="text-muted medium">
{{ \Illuminate\Support\Str::limit($candidate->manifesto, 120) }}
</p>
@endif

{{-- Vote Percentage --}}
@if(isset($candidate->vote_percentage))
<div class="progress mb-2">
<div class="progress-bar"
style="width: {{ $candidate->vote_percentage }}%">
{{ $candidate->vote_percentage }}%
</div>
</div>
@endif

{{-- Vote Selection --}}
<div class="form-check mt-2">

<input class="form-check-input"
type="radio"
name="candidate_id"
value="{{ $candidate->id }}"
required>

<label class="form-check-label">
Select Candidate
</label>

</div>

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

<div class="card-footer text-right">
<button type="submit" class="btn btn-success px-4">
<i class="fas fa-check"></i> Submit Vote
</button>
</div>

</div>

</form>

@endsection