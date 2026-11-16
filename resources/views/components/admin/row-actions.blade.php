@props(['edit', 'destroy', 'name' => 'this item'])

<div class="btn-group btn-group-sm">
    <a href="{{ $edit }}" class="btn btn-outline-primary" title="Edit"><i class="fas fa-pen"></i></a>
    <form action="{{ $destroy }}" method="POST" class="d-inline"
          onsubmit="return confirm(@js('Delete '.$name.'? This cannot be undone.'))">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-top-left-radius:0;border-bottom-left-radius:0">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
