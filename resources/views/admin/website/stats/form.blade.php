@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Number' : 'Add Number')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Impact Number' : 'Add Impact Number' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" action="{{ $item->exists ? route('admin.stats.update', $item->id) : route('admin.stats.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5"><x-admin.input name="value" label="Number" type="number" min="0" :value="$item->value" required /></div>
                            <div class="col-sm-3"><x-admin.input name="suffix" label="After number" :value="$item->suffix" maxlength="10" placeholder="e.g. +" /></div>
                        </div>
                        <x-admin.input name="label" label="Label" :value="$item->label" required maxlength="100" placeholder="e.g. Active members" />
                        <x-admin.icon-select name="icon" label="Icon" :value="$item->icon" />
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.stats.index')" />
            </div>
        </div>
    </form>
@stop
