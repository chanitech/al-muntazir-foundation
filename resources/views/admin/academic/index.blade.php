@extends('adminlte::page')

@section('title','Academic Background')

@section('content')

<div class="card">

<div class="card-header bg-primary">
Academic Records
</div>

<div class="card-body">

<a href="{{ route('academic.create') }}"
class="btn btn-success mb-3">
Add Academic Record
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>Member</th>
<th>Institution</th>
<th>Level</th>
<th>Year</th>
<th>Certificate</th>
</tr>
</thead>

<tbody>

@foreach($records as $row)

<tr>
<td>{{ $row->member->full_name ?? '' }}</td>
<td>{{ $row->institution_name }}</td>
<td>{{ $row->education_level }}</td>
<td>
{{ $row->start_year }} -
{{ $row->end_year }}
</td>

<td>
@if($row->certificate_file)
<a href="{{ asset('storage/'.$row->certificate_file) }}"
target="_blank">
Download
</a>
@endif
</td>
</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection