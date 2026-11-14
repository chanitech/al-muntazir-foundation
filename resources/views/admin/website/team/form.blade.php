@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Team Member' : 'Add Team Member')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Team Member' : 'Add Team Member' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.team.update', $item->id) : route('admin.team.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="name" label="Full name" :value="$item->name" required /></div>
                            <div class="col-md-6"><x-admin.input name="position" label="Position / role" :value="$item->position" placeholder="e.g. Chairperson" /></div>
                        </div>
                        <x-admin.textarea name="bio" label="Short bio" :value="$item->bio" rows="3" maxlength="1000" />
                        <x-admin.input name="email" label="E-mail" type="email" :value="$item->email" help="Optional. Shown as a contact icon." />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-share-alt mr-1"></i> Social media <span class="text-muted font-weight-normal">(optional)</span></h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="facebook_url" label="Facebook" type="url" :value="$item->facebook_url" placeholder="https://facebook.com/…" /></div>
                            <div class="col-md-6"><x-admin.input name="instagram_url" label="Instagram" type="url" :value="$item->instagram_url" placeholder="https://instagram.com/…" /></div>
                            <div class="col-md-6"><x-admin.input name="x_url" label="X (Twitter)" type="url" :value="$item->x_url" placeholder="https://x.com/…" /></div>
                            <div class="col-md-6"><x-admin.input name="linkedin_url" label="LinkedIn" type="url" :value="$item->linkedin_url" placeholder="https://linkedin.com/in/…" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.team.index')" />
                <div class="card">
                    <div class="card-body">
                        <x-admin.image name="photo" label="Photo" :current="$item->mediaUrl('photo')" help="A square portrait works best." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
