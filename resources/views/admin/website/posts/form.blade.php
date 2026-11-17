@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Article' : 'Write Article')
@section('plugins.Summernote', true)

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Article' : 'Write Article' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.posts.update', $item->id) : route('admin.posts.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Headline" :value="$item->title" required maxlength="255" class="form-control-lg" />

                        <x-admin.editor name="body" label="Article" :value="$item->body" required :height="420" />

                        <x-admin.textarea name="excerpt" label="Short summary" :value="$item->excerpt" rows="2" maxlength="500"
                            help="Optional. Shown on news cards and when shared on social media. If empty, the start of the article is used." />
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.posts.index')" toggle="is_published"
                    toggle-label="Published (visible on the website)" :sortable="false">
                    <x-admin.input name="published_at" label="Publish date" type="datetime-local"
                        :value="$item->published_at?->format('Y-m-d\TH:i')"
                        help="Set a future date to schedule the article." />
                </x-admin.publish-card>

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Details</h3></div>
                    <div class="card-body">
                        <x-admin.image name="image" label="Featured image" :current="$item->mediaUrl()" />
                        <x-admin.input name="slug" label="Web address (slug)" :value="$item->slug"
                            help="Optional. Generated from the headline when left empty." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
