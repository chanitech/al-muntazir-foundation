@extends('adminlte::page')

@section('title','Document Details')

@section('content')

<div class="card">

<div class="card-header bg-info text-white">
    Document Details
</div>

<div class="card-body">

    <h4>{{ $document->title }}</h4>

    <hr>

    <p>
        <strong>Category:</strong>
        {{ $document->category->name ?? 'N/A' }}
    </p>

    <p>
        <strong>Description:</strong><br>
        {{ $document->description }}
    </p>

    <p>
        <strong>File:</strong><br>

        <a href="{{ Storage::url($document->file_path) }}"
           target="_blank"
           class="btn btn-sm btn-primary">

            <i class="fas fa-download"></i>
            Download File
        </a>

    </p>

</div>

<div class="card-footer">

    <a href="{{ route('documents.index') }}"
       class="btn btn-secondary">
        Back
    </a>

</div>

</div>

@endsection