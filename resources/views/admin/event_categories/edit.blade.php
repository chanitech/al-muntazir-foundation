@extends('adminlte::page')

@section('title', 'Edit Event Category')

@section('content_header')
    <h1>Edit Event Category</h1>
@stop

@section('content')

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Update Category</h3>
    </div>

    <form action="{{ route('event-categories.update', $eventCategory->id) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $eventCategory->name) }}"
                       class="form-control"
                       required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="3">{{ old('description', $eventCategory->description) }}</textarea>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           {{ old('is_active', $eventCategory->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Active
                    </label>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                Update
            </button>

            <a href="{{ route('event-categories.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@stop