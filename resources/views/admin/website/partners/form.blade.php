@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Partner' : 'Add Partner')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Partner' : 'Add Partner' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.partners.update', $item->id) : route('admin.partners.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="name" label="Organisation name" :value="$item->name" required />
                        <x-admin.input name="url" label="Website" type="url" :value="$item->url" placeholder="https://…" help="Optional. The logo links here." />
                        <x-admin.image name="logo" label="Logo" :current="$item->mediaUrl('logo')"
                            help="PNG with a transparent background looks best. Without a logo, the name is shown." />
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.partners.index')" />
            </div>
        </div>
    </form>
@stop
