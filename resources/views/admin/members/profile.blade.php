@extends('adminlte::page')

@section('title','Member Profile')

@section('content')

<div class="card card-primary card-outline">

<div class="card-body box-profile">

<div class="text-center">

@if($member->photo)
<img class="profile-user-img img-fluid img-circle"
src="{{ $member->photo_url }}">
@endif

</div>

<h3 class="profile-username text-center mt-2">
{{ $member->full_name }}
</h3>

<p class="text-muted text-center">
{{ $member->membership_number }}
</p>

<hr>

<div class="row">

<div class="col-md-6">
<strong>Phone</strong>
<p>{{ $member->phone }}</p>
</div>

<div class="col-md-6">
<strong>Email</strong>
<p>{{ $member->email ?? '-' }}</p>
</div>

<div class="col-md-6">
<strong>Membership Type</strong>
<p>{{ $member->membershipType->name ?? '-' }}</p>
</div>

<div class="col-md-6">
<strong>Status</strong>
<p>{{ $member->status }}</p>
</div>

</div>

<hr>

<h5>Family Information</h5>

@if($member->familyInfo)
<ul>
<li>Father: {{ $member->familyInfo->father_name }}</li>
<li>Mother: {{ $member->familyInfo->mother_name }}</li>
</ul>
@else
<p class="text-muted">No family information.</p>
@endif

<hr>

<h5>Academic Background</h5>

@if($member->academicBackgrounds->count())

<table class="table table-bordered">
<tr>
<th>Institution</th>
<th>Level</th>
<th>Year</th>
</tr>

@foreach($member->academicBackgrounds as $academic)

<tr>
<td>{{ $academic->institution_name }}</td>
<td>{{ $academic->education_level }}</td>
<td>{{ $academic->start_year }} - {{ $academic->end_year }}</td>
</tr>

@endforeach

</table>

@else
<p class="text-muted">No academic records.</p>
@endif

<hr>

<h5>Professional Experience</h5>

@if($member->professionalInfos->count())

<ul>
@foreach($member->professionalInfos as $work)
<li>
{{ $work->occupation }}
( {{ $work->employer }} )
</li>
@endforeach
</ul>

@else
<p class="text-muted">No professional experience.</p>
@endif

</div>

</div>

</div>

@endsection