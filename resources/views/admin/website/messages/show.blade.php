@extends('adminlte::page')

@section('title', 'Message from '.$message->name)

@section('content_header')
    <a href="{{ route('admin.messages.index') }}" class="btn btn-link px-0"><i class="fas fa-arrow-left mr-1"></i> Back to messages</a>
@stop

@section('content')
    <x-admin.flash />

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $message->subject ?: 'Website message' }}</h3>
                    <div class="card-tools text-muted small pt-1">{{ $message->created_at->format('l, d M Y \a\t H:i') }}</div>
                </div>
                <div class="card-body">
                    <div class="message-body">{{ $message->message }}</div>
                </div>
                <div class="card-footer d-flex flex-wrap">
                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Your message')) }}" class="btn btn-primary mr-2 mb-1">
                        <i class="fas fa-reply mr-1"></i> Reply by e-mail
                    </a>
                    @if ($message->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $message->phone) }}" class="btn btn-outline-secondary mr-2 mb-1"><i class="fas fa-phone mr-1"></i> Call</a>
                    @endif
                    <form method="POST" action="{{ route('admin.messages.unread', $message) }}" class="mr-2 mb-1">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-outline-secondary"><i class="fas fa-envelope mr-1"></i> Mark as unread</button>
                    </form>
                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="ml-auto mb-1" onsubmit="return confirm('Delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger"><i class="fas fa-trash mr-1"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Sender</h3></div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Name</dt>
                        <dd>{{ $message->name }}</dd>
                        <dt>E-mail</dt>
                        <dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
                        @if ($message->phone)
                            <dt>Phone</dt>
                            <dd>{{ $message->phone }}</dd>
                        @endif
                        @if ($message->subject)
                            <dt>Topic</dt>
                            <dd>{{ $message->subject }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@stop
