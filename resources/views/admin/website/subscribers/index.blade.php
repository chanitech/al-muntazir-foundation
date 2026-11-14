@extends('adminlte::page')

@section('title', 'Newsletter Subscribers')

@section('content_header')
    <x-admin.page-header title="Newsletter Subscribers" subtitle="People who signed up with the newsletter box in the website footer.">
        @if ($total > 0)
            <a href="{{ route('admin.subscribers.export') }}" class="btn btn-success"><i class="fas fa-file-csv mr-1"></i> Download CSV</a>
        @endif
    </x-admin.page-header>
@stop

@section('content')
    <x-admin.flash />

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <h3 class="card-title mb-2 mb-sm-0">{{ number_format($total) }} {{ Str::plural('subscriber', $total) }}</h3>
            <div class="ml-auto"><x-admin.search :value="$search" placeholder="Search e-mail…" /></div>
        </div>

        @if ($subscribers->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-at"></i>
                <p class="mb-0">{{ $search ? 'No subscribers match your search.' : 'No subscribers yet.' }}</p>
            </div>
        @else
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>E-mail</th><th>Subscribed</th><th class="text-right">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr>
                                <td><a href="mailto:{{ $subscriber->email }}">{{ $subscriber->email }}</a></td>
                                <td class="small">{{ $subscriber->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('Remove this subscriber?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($subscribers->hasPages())
                <div class="card-footer">{{ $subscribers->links('pagination::bootstrap-4') }}</div>
            @endif
        @endif
    </div>
@stop
