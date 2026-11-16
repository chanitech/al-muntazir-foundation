@extends('adminlte::page')

@section('title', $item->exists ? 'Edit Photo' : 'Upload Photos')

@section('content_header')
    <h1>{{ $item->exists ? 'Edit Photo' : 'Upload Photos' }}</h1>
@stop

@section('content')
    <x-admin.flash />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.gallery.update', $item->id) : route('admin.gallery.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if ($item->exists)
                            <x-admin.image name="image" label="Photo" :current="$item->mediaUrl()" :removable="false" />
                        @else
                            <div class="form-group">
                                <label for="f_images">Photos <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="images[]" id="f_images" multiple required
                                           accept="image/jpeg,image/png,image/webp,image/gif"
                                           class="custom-file-input @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                                    <label class="custom-file-label" for="f_images">Choose one or more photos…</label>
                                </div>
                                <small class="form-text text-muted">You can select up to 20 photos at once (each up to 5 MB).</small>
                                <div id="upload-previews" class="d-flex flex-wrap mt-3"></div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input name="title" label="Caption" :value="$item->title"
                                    :help="$item->exists ? null : 'Optional. Applied to all photos in this upload.'" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input name="category" label="Album / category" :value="$item->category" list="gallery-albums"
                                    placeholder="e.g. Youth Camp 2026" help="Visitors can filter the gallery by album." />
                                <datalist id="gallery-albums">
                                    @foreach (\App\Models\GalleryItem::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category') as $album)
                                        <option value="{{ $album }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-admin.publish-card :item="$item" :back="route('admin.gallery.index')" :sortable="$item->exists" />
            </div>
        </div>
    </form>
@stop

@push('js')
    <script>
        (function () {
            var input = document.getElementById('f_images');
            if (!input) return;
            input.addEventListener('change', function () {
                var box = document.getElementById('upload-previews');
                box.innerHTML = '';
                var label = input.parentElement.querySelector('.custom-file-label');
                label.textContent = input.files.length + (input.files.length === 1 ? ' photo selected' : ' photos selected');
                Array.prototype.slice.call(input.files, 0, 20).forEach(function (file) {
                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'admin-thumb mr-2 mb-2';
                    box.appendChild(img);
                });
            });
        })();
    </script>
@endpush
