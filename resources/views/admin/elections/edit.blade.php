@extends('adminlte::page')

@section('title', 'Edit Election')

@section('content_header')
    <h1>Edit Election</h1>
@stop

@section('content')
    <x-admin.flash />

    <div class="card card-primary card-outline">
        <form action="{{ route('elections.update', $election) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <x-admin.input name="name" label="Election name" :value="$election->name" required />
                <x-admin.textarea name="description" label="Description" :value="$election->description" rows="3" />

                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="start_time" label="Voting starts" type="datetime-local" required
                            :value="$election->start_time?->format('Y-m-d\TH:i')" />
                    </div>
                    <div class="col-md-6">
                        <x-admin.input name="end_time" label="Voting ends" type="datetime-local" required
                            :value="$election->end_time?->format('Y-m-d\TH:i')" />
                    </div>
                </div>

                <x-admin.switch name="is_active" label="Active (members can vote during the voting period)" :checked="$election->is_active"
                    help="Switch off to close the election early or hide it from members." />
            </div>

            <div class="card-footer">
                <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save changes</button>
                <a href="{{ route('elections.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
@stop
