@extends('adminlte::page')

@section('title', 'Create Role')

@section('content_header')
    <h1>Create Role</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Role Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    @foreach($permissions as $group => $perms)
                        <div class="card card-outline card-secondary mb-2">
                            <div class="card-header">
                                <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                            </div>
                            <div class="card-body">
                                @foreach($perms as $perm)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm{{ $perm->id }}">
                                        <label class="form-check-label" for="perm{{ $perm->id }}">
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-success">Save Role</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop