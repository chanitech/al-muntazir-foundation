@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Slide' : 'Add Slide')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Slide' : 'Add Slide' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.sliders.update', $item->id) : route('admin.sliders.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.image name="image" label="Slide image" :current="$item->mediaUrl()" :required="! $item->image"
                            help="Use a wide landscape photo (at least 1600 × 800 px). JPG, PNG or WebP, up to 5 MB." />

                        <x-admin.input name="title" label="Heading" :value="$item->title" maxlength="255"
                            placeholder="e.g. Empowering young minds" />

                        <x-admin.textarea name="subtitle" label="Text under the heading" :value="$item->subtitle" rows="2" maxlength="500" />

                        <div class="row">
                            <div class="col-md-5">
                                <x-admin.input name="button_text" label="Button text" :value="$item->button_text" maxlength="60"
                                    placeholder="e.g. Join Us" help="Leave empty for no button." />
                            </div>
                            <div class="col-md-7">
                                <x-admin.input name="button_link" label="Button link" :value="$item->button_link"
                                    placeholder="/contact or https://…" help="A page on this site (e.g. /programs) or a full web address." />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.sliders.index')" toggle-label="Show this slide" />
            </div>
        </div>
    </form>
@stop
