@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Program' : 'Add Program')
@section('plugins.Summernote', true)

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Program' : 'Add Program' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.programs.update', $item->id) : route('admin.programs.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Program name" :value="$item->title" required maxlength="255" />

                        <x-admin.textarea name="summary" label="Short summary" :value="$item->summary" rows="2" maxlength="500"
                            help="One or two sentences shown on the program card." />

                        <x-admin.editor name="body" label="Full description" :value="$item->body"
                            help="Shown on the program's own page. You can add headings, lists, images and YouTube videos." />
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.programs.index')" />

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Details</h3></div>
                    <div class="card-body">
                        <x-admin.image name="image" label="Cover image" :current="$item->mediaUrl()" />
                        <x-admin.icon-select name="icon" label="Icon" :value="$item->icon" help="Shown when the program has no image." />
                        <x-admin.input name="schedule" label="Schedule" :value="$item->schedule" placeholder="e.g. Saturdays, 10 AM – 1 PM" />
                        <x-admin.input name="slug" label="Web address (slug)" :value="$item->slug"
                            help="Optional. Generated from the name when left empty." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
