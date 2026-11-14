@extends('adminlte::page')

@section('title','Upload Document')

@section('content')

<div class="card">

<div class="card-header bg-primary text-white">
    <h5 class="mb-0">
        <i class="fas fa-upload"></i>
        Upload New Document
    </h5>
</div>

<form action="{{ route('documents.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="card-body">

{{-- ================= GLOBAL ERROR DISPLAY ================= --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Title --}}
<div class="form-group mb-3">
    <label>Document Title</label>

    <input type="text"
           name="title"
           class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title') }}"
           required>

    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Category --}}
<div class="form-group mb-3">
    <label>Category</label>

    <select name="document_category_id"
            class="form-control @error('document_category_id') is-invalid @enderror"
            required>

        <option value="">-- Select Category --</option>

        @foreach(\App\Models\DocumentCategory::where('is_active',1)->get() as $category)

            <option value="{{ $category->id }}"
                {{ old('document_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>

        @endforeach

    </select>

    @error('document_category_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Description --}}
<div class="form-group mb-3">
    <label>Description</label>

    <textarea name="description"
              class="form-control"
              rows="3">{{ old('description') }}</textarea>
</div>

{{-- File Upload --}}
<div class="form-group mb-3">
    <label>Document File</label>

    <input type="file"
           name="file"
           class="form-control @error('file') is-invalid @enderror"
           required>

    @error('file')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <small class="text-muted">
        Allowed: PDF, Word, Excel, Image (Max 10MB)
    </small>
</div>

{{-- Access Control --}}
<div class="form-check mb-3">

    <input type="checkbox"
           name="is_public"
           value="1"
           class="form-check-input"
           {{ old('is_public') ? 'checked' : '' }}>

    <label class="form-check-label">
        Make document public
    </label>

</div>

</div>

<div class="card-footer">

<button class="btn btn-primary">
    <i class="fas fa-save"></i>
    Upload Document
</button>

<a href="{{ route('documents.index') }}"
   class="btn btn-secondary">
    Cancel
</a>

</div>

</form>

</div>

@endsection