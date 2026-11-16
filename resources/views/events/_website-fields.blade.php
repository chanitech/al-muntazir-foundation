{{-- Public website options (shared by the create and edit forms) --}}
<div class="card card-outline card-info mt-4 mb-0">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-globe mr-1"></i> Public website</h3>
    </div>
    <div class="card-body">
        <div class="custom-control custom-switch mb-3">
            <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1"
                   @checked(old('is_public', $event->is_public ?? false))>
            <label class="custom-control-label" for="is_public">Show this event on the public website</label>
            <small class="form-text text-muted">Leave off for internal / members-only events.</small>
        </div>

        <div class="form-group mb-0">
            <label for="image">Cover image <span class="text-muted font-weight-normal">(optional)</span></label>
            @if(!empty($event?->image))
                <div class="mb-2">
                    <img src="{{ $event->imageUrl() }}" alt="" class="img-thumbnail" style="max-height: 120px">
                    <div class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input" id="remove_image" name="remove_image" value="1">
                        <label class="custom-control-label" for="remove_image">Remove current image</label>
                    </div>
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                   class="form-control-file @error('image') is-invalid @enderror">
            <small class="form-text text-muted">JPG, PNG or WebP, up to 5 MB. A landscape photo works best.</small>
            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
