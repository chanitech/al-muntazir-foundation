{{-- Rich-text editor (Summernote). Requires @section('plugins.Summernote', true) on the page. --}}
@props(['name', 'label', 'value' => null, 'help' => null, 'required' => false, 'height' => 360])

<div class="form-group">
    <label for="f_{{ $name }}">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <textarea name="{{ $name }}" id="f_{{ $name }}" class="form-control admin-editor @error($name) is-invalid @enderror"
              data-height="{{ $height }}">{{ old($name, $value) }}</textarea>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

@once
    @push('js')
        <script>
            $(function () {
                var uploadUrl = @json(route('admin.editor.upload'));
                var token = $('meta[name="csrf-token"]').attr('content') || @json(csrf_token());

                $('.admin-editor').each(function () {
                    var $el = $(this);
                    $el.summernote({
                        height: parseInt($el.data('height'), 10) || 360,
                        placeholder: 'Write here…',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link', 'picture', 'video', 'table', 'hr']],
                            ['view', ['fullscreen', 'codeview']]
                        ],
                        styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
                        callbacks: {
                            // Upload pasted / inserted images to the server instead of embedding them.
                            onImageUpload: function (files) {
                                Array.prototype.forEach.call(files, function (file) {
                                    var data = new FormData();
                                    data.append('image', file);
                                    data.append('_token', token);
                                    $.ajax({ url: uploadUrl, method: 'POST', data: data, processData: false, contentType: false })
                                        .done(function (res) { $el.summernote('insertImage', res.url); })
                                        .fail(function (xhr) {
                                            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Image upload failed.';
                                            alert(msg);
                                        });
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
