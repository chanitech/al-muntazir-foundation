@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

@can('view members')
    @php $pendingApplications = \App\Models\MembershipApplication::pending()->count(); @endphp
    @if ($pendingApplications > 0)
        <div class="callout callout-warning d-flex flex-wrap align-items-center">
            <div class="mr-3 mb-1">
                <i class="fas fa-user-plus text-warning mr-1"></i>
                <strong>{{ $pendingApplications }} membership {{ Str::plural('application', $pendingApplications) }}</strong> from the website {{ $pendingApplications === 1 ? 'is' : 'are' }} waiting for review.
            </div>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-warning ml-auto">Review now</a>
        </div>
    @endif
@endcan

<div class="row">

    <!-- Members -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $membersCount }}</h3>
                <p>Members</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Events -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $eventsCount }}</h3>
                <p>Events</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $documentsCount }}</h3>
                <p>Documents</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder"></i>
            </div>
        </div>
    </div>

    <!-- Elections -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $electionsCount }}</h3>
                <p>Elections</p>
            </div>
            <div class="icon">
                <i class="fas fa-vote-yea"></i>
            </div>
        </div>
    </div>

</div>

@can('manage website')
    @include('admin.website._dashboard')
@endcan

<!-- Latest Members Table -->
<div class="card shadow mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Latest Members</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestMembers as $member)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->phone }}</td>
                    <td>{{ $member->membershipType->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $member->status=='Active' ? 'badge-success' : 'badge-danger' }}">
                            {{ $member->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted p-4">
                        No members found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Upcoming Events Table -->
<div class="card shadow mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Upcoming Events</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $upcomingEvents = \App\Models\Event::where('event_date', '>=', now()->toDateString())
                        ->orderBy('event_date', 'asc')
                        ->limit(5)
                        ->get();
                @endphp

                @forelse($upcomingEvents as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</td>
                    <td>
                        {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('h:i A') : '-' }}
                        -
                        {{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('h:i A') : '-' }}
                    </td>
                    <td>{{ $event->location ?? '-' }}</td>
                    <td>
                        <span class="badge 
                            {{ $event->status == 'upcoming' ? 'badge-success' : ($event->status == 'ongoing' ? 'badge-primary' : 'badge-secondary') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">
                        No upcoming events.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Documents Table -->
<div class="card shadow mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Recent Documents</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $recentDocuments = \App\Models\Document::orderBy('created_at', 'desc')->limit(5)->get();
                @endphp

                @forelse($recentDocuments as $doc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $doc->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d M, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('documents.secure-download', $doc->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted p-4">
                        No documents uploaded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop