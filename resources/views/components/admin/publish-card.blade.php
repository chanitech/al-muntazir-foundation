{{-- Right-hand "Publish" card on admin forms: visibility, order and Save --}}
@props([
    'item',
    'back',
    'toggle' => 'is_active',
    'toggleLabel' => 'Visible on the website',
    'sortable' => true,
])

<div class="card card-primary card-outline">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-paper-plane mr-1"></i> Publish</h3></div>
    <div class="card-body">
        @if ($toggle)
            <x-admin.switch :name="$toggle" :label="$toggleLabel" :checked="(bool) $item->{$toggle}" />
        @endif

        {{ $slot }}

        @if ($sortable)
            <x-admin.input name="sort_order" label="Display order" type="number" min="0" :value="$item->sort_order ?? 0"
                           help="Lower numbers are shown first." />
        @endif
    </div>
    <div class="card-footer d-flex">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ $item->exists ? 'Save changes' : 'Create' }}</button>
        <a href="{{ $back }}" class="btn btn-link ml-auto">Cancel</a>
    </div>
</div>
