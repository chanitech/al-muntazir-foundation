@extends('adminlte::page')

@section('title','Change Password')

@section('content_header')
<h1>Change Password</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->updatePassword->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->updatePassword->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card shadow">

    <div class="card-header">
        <h5 class="card-title mb-0">Update Your Password</h5>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="current_password">Current Password</label>
                <input id="current_password" type="password" 
                       name="current_password" 
                       class="form-control" 
                       required autofocus>
            </div>

            <div class="form-group mb-3">
                <label for="password">New Password</label>
                <input id="password" type="password" 
                       name="password" 
                       class="form-control" 
                       required>
                <small class="form-text text-muted">
                    Minimum 8 characters, mix of letters and numbers.
                </small>
            </div>

            <div class="form-group mb-4">
                <label for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" type="password" 
                       name="password_confirmation" 
                       class="form-control" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Update Password
            </button>

        </form>

    </div>
</div>

@stop