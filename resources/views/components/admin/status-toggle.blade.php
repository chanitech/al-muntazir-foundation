{{-- Clickable "Visible / Hidden" badge that flips the item's status --}}
@props(['action', 'active', 'on' => 'Visible', 'off' => 'Hidden'])

<form action="{{ $action }}" method="POST" class="d-inline">
    @csrf
    @method('PATCH')
    <button type="submit" class="btn btn-xs {{ $active ? 'btn-success' : 'btn-outline-secondary' }}"
            title="Click to {{ $active ? 'hide from' : 'show on' }} the website">
        <i class="fas {{ $active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>{{ $active ? $on : $off }}
    </button>
</form>
