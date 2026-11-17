{{-- Image upload with a live preview and an optional "remove" checkbox --}}
@props(['name', 'label', 'current' => null, 'help' => null, 'required' => false, 'removable' => true, 'accept' => 'image/jpeg,image/png,image/webp,image/gif'])

<div class="form-group">
    <label for="f_{{ $name }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>

    <div class="d-flex align-items-start flex-wrap">
        <div class="admin-image-preview mr-3 mb-2 {{ $current ? '' : 'is-empty' }}" data-preview-for="f_{{ $name }}">
            <img src="{{ $current }}" alt="" @class(['d-none' => ! $current])>
            <span class="placeholder-icon @if($current) d-none @endif"><i class="far fa-image"></i></span>
        </div>

        <div class="flex-grow-1" style="min-width: 220px">
            <div class="custom-file">
                <input type="file" name="{{ $name }}" id="f_{{ $name }}" accept="{{ $accept }}" @required($required && ! $current)
                       class="custom-file-input admin-image-input @error($name) is-invalid @enderror">
                <label class="custom-file-label" for="f_{{ $name }}">{{ $current ? 'Choose a new image…' : 'Choose image…' }}</label>
            </div>
            <small class="form-text text-muted">{{ $help ?? 'JPG, PNG or WebP, up to 5 MB.' }}</small>
            @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

            @if ($current && $removable && ! $required)
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="remove_{{ $name }}" name="remove_{{ $name }}" value="1">
                    <label class="custom-control-label text-danger" for="remove_{{ $name }}">Remove current image</label>
                </div>
            @endif
        </div>
    </div>
</div>

@once
    @push('css')
        <style>
            .admin-image-preview { width: 160px; height: 110px; border-radius: .4rem; border: 1px dashed #ced4da; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; }
            .admin-image-preview img { width: 100%; height: 100%; object-fit: cover; }
            .admin-image-preview .placeholder-icon { font-size: 2rem; color: #adb5bd; }
        </style>
    @endpush
    @push('js')
        <script>
            document.addEventListener('change', function (e) {
                if (!e.target.classList.contains('admin-image-input')) return;
                var input = e.target, file = input.files && input.files[0];
                var label = input.parentElement.querySelector('.custom-file-label');
                if (label) label.textContent = file ? file.name : 'Choose image…';
                var box = document.querySelector('[data-preview-for="' + input.id + '"]');
                if (!box || !file) return;
                var img = box.querySelector('img');
                img.src = URL.createObjectURL(file);
                img.classList.remove('d-none');
                box.querySelector('.placeholder-icon').classList.add('d-none');
            });
        </script>
    @endpush
@endonce
