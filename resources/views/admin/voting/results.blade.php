@extends('adminlte::page')

@section('title','Election Results')

@section('content')

<h3>{{ $election->name }} - Results</h3>

<hr>

<p>
Total Votes: <b>{{ $totalVotes }}</b>
</p>

@if($results->count())

@foreach($results as $positionId => $candidates)

@php
$position = \App\Models\Position::find($positionId);
@endphp

<div class="card mt-4">

<div class="card-header bg-primary text-white">
{{ $position->name ?? 'Position' }}
</div>

<div class="card-body">

<table class="table table-bordered">

<thead>
<tr>
<th>Candidate</th>
<th>Votes</th>
<th>Percentage</th>
</tr>
</thead>

<tbody>

@foreach($candidates as $candidate)

@php
$percentage = $totalVotes > 0
? round(($candidate->votes_count / $totalVotes) * 100, 2)
: 0;
@endphp

<tr>
<td>
{{ $candidate->member->full_name ?? 'Candidate' }}
</td>

<td>
{{ $candidate->votes_count }}
</td>

<td>
{{ $percentage }} %
</td>
</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endforeach

@else

<p class="text-muted">No results yet.</p>

@endif

@endsection