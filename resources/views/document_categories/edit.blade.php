@extends('adminlte::page')

@section('title', 'Edit Document Category')

@section('content_header')
    <h1>Edit Document Category</h1>
@stop

@section('content')
    <x-admin.flash />

    <div class="card card-primary card-outline">
        <form action="{{ route('document-categories.update', $documentCategory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <x-admin.input name="name" label="Category name" :value="$documentCategory->name" required maxlength="255" />
                <x-admin.switch name="is_active" label="Active (can be chosen when uploading documents)" :checked="(bool) $documentCategory->is_active" />
            </div>

            <div class="card-footer">
                <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save changes</button>
                <a href="{{ route('document-categories.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
@stop
