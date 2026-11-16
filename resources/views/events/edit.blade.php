@extends('adminlte::page')

@section('title', 'Edit Event')

@section('content_header')
    <h1>Edit Event</h1>
@stop

@section('content')

<div class="card card-primary card-outline">
    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="event_category_id">Event Category</label>
                <select name="event_category_id" id="event_category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('event_category_id', $event->event_category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="event_date">Event Date</label>
                    <input type="date" name="event_date" id="event_date" class="form-control" required
                           value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" id="start_time" class="form-control"
                           value="{{ old('start_time', $event->start_time?->format('H:i')) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" id="end_time" class="form-control"
                           value="{{ old('end_time', $event->end_time?->format('H:i')) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 form-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" name="capacity" id="capacity" min="0" value="{{ old('capacity', $event->capacity) }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    @foreach(\App\Models\Event::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $event->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- The SMS template is intentionally not editable here: submitting it
                 makes the reminder SMS go out again (see EventController@update). --}}
            @include('events._website-fields', ['event' => $event])
        </div>

        <div class="card-footer">
            <button class="btn btn-success"><i class="fas fa-save mr-1"></i> Update Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
