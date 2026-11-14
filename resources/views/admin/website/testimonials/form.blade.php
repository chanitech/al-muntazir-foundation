@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Testimonial' : 'Add Testimonial')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Testimonial' : 'Add Testimonial' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.testimonials.update', $item->id) : route('admin.testimonials.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.textarea name="quote" label="Quote" :value="$item->quote" rows="4" required maxlength="1000" />
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="name" label="Name" :value="$item->name" required /></div>
                            <div class="col-md-6"><x-admin.input name="role" label="Who they are" :value="$item->role" placeholder="e.g. Member since 2022" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.testimonials.index')" />
                <div class="card">
                    <div class="card-body">
                        <x-admin.image name="photo" label="Photo" :current="$item->mediaUrl('photo')" help="Optional. A square portrait works best." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
