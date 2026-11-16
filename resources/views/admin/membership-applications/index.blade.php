@extends('adminlte::page')

@section('title', 'Membership Applications')

@section('content_header')
    <x-admin.page-header title="Membership Applications"
        subtitle="Applications sent from the “Become a Member” form on the website.">
        <a href="{{ route('site.membership.apply') }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-external-link-alt mr-1"></i> View the form</a>
    </x-admin.page-header>
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <ul class="nav nav-pills mb-2 mb-sm-0">
                @foreach (['pending' => 'Waiting for review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                    <li class="nav-item">
                        <a class="nav-link {{ $status === $key ? 'active' : '' }}" href="{{ route('admin.applications.index', ['status' => $key]) }}">
                            {{ $label }}
                            @php $n = $key === 'all' ? $counts->sum() : ($counts[$key] ?? 0); @endphp
                            @if ($n)
                                <span class="badge {{ $key === 'pending' ? 'badge-danger' : 'badge-light' }} ml-1">{{ $n }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="ml-auto">
                <form method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group input-group-sm" style="width: 260px">
                        <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Search name, phone, e-mail…" aria-label="Search">
                        <div class="input-group-append"><button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button></div>
                    </div>
                </form>
            </div>
        </div>

        @if ($applications->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-user-plus"></i>
                <p class="mb-0">
                    @if ($search)
                        No applications match your search.
                    @elseif ($status === 'pending')
                        No applications are waiting for review.
                    @else
                        No applications here yet.
                    @endif
                </p>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Reference</th>
                            <th>Applicant</th>
                            <th>Phone</th>
                            <th>Region</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr class="{{ $application->isPending() ? 'message-unread' : '' }}">
                                <td class="text-nowrap"><a href="{{ route('admin.applications.show', $application) }}">{{ $application->reference() }}</a></td>
                                <td>
                                    {{ $application->fullName() }}
                                    @if ($application->email)<div class="small text-muted font-weight-normal">{{ $application->email }}</div>@endif
                                </td>
                                <td class="text-nowrap">{{ $application->phone }}</td>
                                <td>{{ $application->current_region ?: '—' }}</td>
                                <td class="small text-nowrap" title="{{ $application->created_at->format('d M Y H:i') }}">{{ $application->created_at->diffForHumans() }}</td>
                                <td>
                                    @switch($application->status)
                                        @case('pending') <span class="badge badge-warning">Waiting</span> @break
                                        @case('approved') <span class="badge badge-success">Approved</span> @break
                                        @default <span class="badge badge-secondary">Rejected</span>
                                    @endswitch
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">
                                        {{ $application->isPending() ? 'Review' : 'Open' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($applications->hasPages())
                <div class="card-footer">{{ $applications->links('pagination::bootstrap-4') }}</div>
            @endif
        @endif
    </div>
@stop
