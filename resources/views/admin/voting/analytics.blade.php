@extends('adminlte::page')

@section('title','Voting Analytics')

@section('content')

<h3>Voting Analytics Dashboard</h3>

<form method="GET" class="mb-4">

<select name="election_id" class="form-control" onchange="this.form.submit()">

<option value="">-- Select Election --</option>

@foreach($elections as $e)

<option value="{{ $e->id }}"
{{ request('election_id')==$e->id ? 'selected' : '' }}>
{{ $e->name }}
</option>

@endforeach

</select>

</form>

@if($election)

<div class="row">

<div class="col-md-3">
<div class="small-box bg-success">
<div class="inner">
<h3>{{ $totalVotes }}</h3>
<p>Total Votes</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-info">
<div class="inner">
<h3>{{ $turnoutRate }}%</h3>
<p>Turnout Rate</p>
</div>
</div>
</div>

</div>

<div class="card mt-4">

<div class="card-header bg-primary text-white">
Top Candidates Popularity
</div>

<div class="card-body">

<canvas id="candidateChart"></canvas>

</div>
</div>

@endif

@endsection

@section('js')

@if($candidateStats->count())

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById('candidateChart'), {
    type:'bar',
    data:{
        labels:[
            @foreach($candidateStats as $c)
            "{{ $c->member->full_name }}",
            @endforeach
        ],
        datasets:[{
            label:'Votes',
            data:[
                @foreach($candidateStats as $c)
                {{ $c->votes_count }},
                @endforeach
            ]
        }]
    }
});

</script>

@endif

@endsection