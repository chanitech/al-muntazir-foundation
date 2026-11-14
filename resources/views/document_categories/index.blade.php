@extends('adminlte::page')

@section('title','Document Categories')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">

        <h5 class="mb-0">
            <i class="fas fa-folder"></i>
            Document Categories
        </h5>

        <a href="{{ route('document-categories.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New Category
        </a>

    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th width="160">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($categories as $category)

                <tr>
                    <td>{{ $category->name }}</td>

                    <td>
                        <code>{{ $category->slug }}</code>
                    </td>

                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>

                    <td>

                        <a href="{{ route('document-categories.edit',$category->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('document-categories.destroy',$category->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete category?')">
                                <i class="fas fa-trash"></i>
                            </button>

                        </form>

                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No categories found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $categories->links() }}
    </div>

</div>

@endsection