@extends('layouts.adminlte')

@section('title','Document Library')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">
            <i class="fas fa-folder"></i>
            Document Library
        </h3>

        <a href="{{ route('documents.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-upload"></i>
            Upload Document
        </a>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>File</th>
                    <th>Downloads</th>
                    <th>Access</th>
                    <th width="180">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($documents as $document)

                <tr>
                    <td>{{ $document->title }}</td>

                    <td>
                        {{ $document->category->name ?? '-' }}
                    </td>

                    <td>
                        {{ $document->file_name }}
                    </td>

                    <td>
                        <span class="badge bg-info">
                            {{ $document->download_count }}
                        </span>
                    </td>

                    <td>
                        @if($document->is_public)
                            <span class="badge bg-success">Public</span>
                        @else
                            <span class="badge bg-warning">Private</span>
                        @endif
                    </td>

                    <td>

                        <a href="{{ route('documents.secure-download',$document->id) }}"
                           class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i>
                        </a>

                        <a href="{{ route('documents.show',$document->id) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>

                        <form action="{{ route('documents.destroy',$document->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete document?')">
                                <i class="fas fa-trash"></i>
                            </button>

                        </form>

                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No documents found
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>

    </div>

    <div class="card-footer">
        {{ $documents->links() }}
    </div>

</div>

@endsection