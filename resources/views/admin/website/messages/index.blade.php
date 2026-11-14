@extends('adminlte::page')

@section('title', 'Website Messages')

@section('content_header')
    <x-admin.page-header title="Website Messages" subtitle="Messages sent through the Contact page of the website.">
        @if ($unreadCount > 0)
            <form method="POST" action="{{ route('admin.messages.mark-all-read') }}" class="d-inline">
                @csrf
                <button class="btn btn-outline-secondary"><i class="fas fa-check-double mr-1"></i> Mark all as read</button>
            </form>
        @endif
    </x-admin.page-header>
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <ul class="nav nav-pills mb-2 mb-sm-0">
                <li class="nav-item"><a class="nav-link {{ $filter !== 'unread' ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">All</a></li>
                <li class="nav-item">
                    <a class="nav-link {{ $filter === 'unread' ? 'active' : '' }}" href="{{ route('admin.messages.index', ['filter' => 'unread']) }}">
                        Unread @if ($unreadCount)<span class="badge badge-danger ml-1">{{ $unreadCount }}</span>@endif
                    </a>
                </li>
            </ul>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search messages…" /></div>
        </div>

        @if ($messages->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-inbox"></i>
                <p class="mb-0">{{ $search || $filter ? 'No messages found.' : 'No messages yet. Messages from the Contact page will appear here.' }}</p>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 30px"></th>
                            <th>From</th>
                            <th>Subject &amp; message</th>
                            <th>Received</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr class="{{ $message->isRead() ? '' : 'message-unread' }}">
                                <td>
                                    @unless ($message->isRead())
                                        <i class="fas fa-circle text-primary" style="font-size: .55rem" title="Unread"></i>
                                    @endunless
                                </td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}" class="text-dark">{{ $message->name }}</a>
                                    <div class="small text-muted">{{ $message->email }}</div>
                                </td>
                                <td class="text-wrap-tight">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="text-dark">
                                        @if ($message->subject)<span class="badge badge-light border mr-1">{{ $message->subject }}</span>@endif
                                        <span class="font-weight-normal text-muted">{{ Str::limit($message->message, 90) }}</span>
                                    </a>
                                </td>
                                <td class="small text-nowrap" title="{{ $message->created_at->format('d M Y H:i') }}">{{ $message->created_at->diffForHumans() }}</td>
                                <td class="text-right text-nowrap">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-outline-primary" title="Open"><i class="fas fa-envelope-open"></i></a>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="card-footer">{{ $messages->links('pagination::bootstrap-4') }}</div>
            @endif
        @endif
    </div>
@stop
