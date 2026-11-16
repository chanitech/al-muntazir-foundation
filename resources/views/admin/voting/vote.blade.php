@extends('adminlte::page')

@section('title', 'Vote – '.$election->name)

@section('content_header')
    <h1>{{ $election->name }}</h1>
    <p class="text-muted mb-0">
        Voting {{ $election->start_time?->format('d M Y, H:i') }} – {{ $election->end_time?->format('d M Y, H:i') }}
    </p>
@stop

@section('content')

<x-admin.flash />

@if (! $election->isOpen())
    <div class="alert alert-warning">
        <i class="fas fa-clock mr-1"></i>
        @if (! $election->is_active)
            This election is closed.
        @elseif ($election->start_time && now()->lt($election->start_time))
            Voting has not started yet. It opens on {{ $election->start_time->format('d M Y \a\t H:i') }}.
        @else
            Voting has ended. Thank you for taking part.
        @endif
    </div>
@endif

<form action="{{ route('voting.store') }}" method="POST">
@csrf
<input type="hidden" name="election_id" value="{{ $election->id }}">

<div class="card">
<div class="card-body">

<p class="text-muted">Choose one candidate for each position, then press <strong>Submit my votes</strong>. You can only vote once per position.</p>

@forelse($candidates as $positionId => $candidateGroup)

@php
    $position = $positions[$positionId] ?? null;
    $voted = in_array($positionId, $votedPositions);
@endphp

<h5 class="text-primary mt-4 border-bottom pb-2 d-flex align-items-center">
    {{ $position->name ?? 'Position' }}
    @if ($voted)
        <span class="badge badge-success ml-2"><i class="fas fa-check mr-1"></i>You have voted</span>
    @endif
</h5>

<div class="row">
@foreach($candidateGroup as $candidate)
@php $member = $candidate->member; @endphp

<div class="col-md-4 mb-3">
<label class="card shadow-sm border h-100 mb-0 ballot-option {{ $voted ? 'is-disabled' : '' }}" for="candidate_{{ $candidate->id }}">
<div class="card-body text-center">

<img src="{{ $member->photo_url ?? asset('images/default.png') }}" alt=""
     class="img-fluid rounded-circle mb-3" style="width:160px;height:160px;object-fit:cover">

<h4 class="font-weight-bold">{{ $member->full_name ?? 'Candidate' }}</h4>

@if(!empty($candidate->manifesto))
<p class="text-muted">{{ \Illuminate\Support\Str::limit($candidate->manifesto, 160) }}</p>
@endif

<div class="custom-control custom-radio mt-2">
    <input class="custom-control-input" type="radio"
           id="candidate_{{ $candidate->id }}"
           name="votes[{{ $positionId }}]"
           value="{{ $candidate->id }}"
           @checked(old('votes.'.$positionId) == $candidate->id)
           @disabled($voted || ! $election->isOpen())>
    <span class="custom-control-label">Vote for this candidate</span>
</div>

</div>
</label>
</div>
@endforeach
</div>

@empty

<div class="alert alert-info">No candidates available for voting.</div>

@endforelse

</div>

@if ($candidates->isNotEmpty() && $election->isOpen() && count($votedPositions) < $candidates->count())
<div class="card-footer text-right">
<button type="submit" class="btn btn-success px-4">
<i class="fas fa-check"></i> Submit my votes
</button>
</div>
@endif

</div>
</form>

@endsection

@push('css')
<style>
    .ballot-option { cursor: pointer; transition: box-shadow .2s, border-color .2s; }
    .ballot-option:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important; }
    .ballot-option:has(input:checked) { border-color: #28a745 !important; box-shadow: 0 0 0 3px rgba(40,167,69,.25) !important; }
    .ballot-option.is-disabled { cursor: default; opacity: .65; }
</style>
@endpush
