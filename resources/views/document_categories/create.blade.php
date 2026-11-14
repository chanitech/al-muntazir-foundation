@extends('adminlte::page')

@section('title','Create Category')

@section('content')

<div class="card">

<div class="card-header bg-primary text-white">
    Create Document Category
</div>

<form action="{{ route('document-categories.store') }}"
      method="POST">

@csrf

<div class="card-body">

    <div class="form-group">
        <label>Category Name</label>

        <input type="text"
               name="name"
               class="form-control"
               required>
    </div>

</div>

<div class="card-footer">

    <button class="btn btn-primary">
        Save Category
    </button>

</div>

</form>

</div>

@endsection