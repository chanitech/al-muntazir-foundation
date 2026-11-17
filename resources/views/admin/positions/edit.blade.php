@extends('adminlte::page')

@section('title', 'Edit Position')

@section('content_header')
    <h1>Edit Position</h1>
@stop

@section('content')
    <x-admin.flash />

    <div class="card card-primary card-outline">
        <form method="POST" action="{{ route('positions.update', $position) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
                <x-admin.input name="name" label="Position name" :value="$position->name" required maxlength="255" />
            </div>

            <div class="card-footer">
                <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save changes</button>
                <a href="{{ route('positions.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
@stop
