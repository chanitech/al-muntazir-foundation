@extends('adminlte::page')

@section('title','Voting')

@section('content')

<div class="card">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Election List</h5>
</div>

<div class="card-body">

@if($elections->count())

@foreach($elections as $election)

<div class="border rounded p-3 mb-3">

<div class="d-flex justify-content-between align-items-center">

<div>

<h5 class="mb-1">
{{ $election->name }}
</h5>

<small class="text-muted d-block">
Start: {{ $election->start_time }}
</small>

<small class="text-muted d-block">
End: {{ $election->end_time }}
</small>

</div>

<div>

<a href="{{ route('voting.election',$election->id) }}"
class="btn btn-sm btn-primary">
<i class="fas fa-vote-yea"></i> Vote Now
</a>

<a href="{{ route('voting.results',$election->id) }}"
class="btn btn-sm btn-info">
<i class="fas fa-chart-bar"></i> Results
</a>

</div>

</div>

</div>

@endforeach

@else

<div class="text-center p-5 border rounded bg-light">

<h5 class="text-muted">
⚠️ No election found.
</h5>

<p class="text-secondary">
Please contact administrator to proceed with election setup.
</p>

</div>





@endif

</div>

</div>

@endsection