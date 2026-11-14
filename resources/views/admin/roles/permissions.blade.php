@extends('adminlte::page')

@section('title', 'Manage Permissions for ' . ucfirst($role->name))

@section('content_header')
    <h1>Manage Permissions: {{ ucfirst($role->name) }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.permissions.update', $role) }}" method="POST">
                @csrf @method('PUT')
                @foreach($permissions as $perm)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-check-input"
                            {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $perm->name }}</label>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-3">Back to Roles</a>
            </form>
        </div>
    </div>
@endsection