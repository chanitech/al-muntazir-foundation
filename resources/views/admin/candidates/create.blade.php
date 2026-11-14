@extends('adminlte::page')

@section('title','Nominate Candidate')

@section('content')

<div class="card">

    <div class="card-header">
        <h5>Candidate Nomination</h5>
    </div>

    <form action="{{ route('candidates.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <!-- Member -->
            <div class="form-group">
                <label>Member</label>
                <select name="member_id" class="form-control" required>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Election -->
            <div class="form-group">
                <label>Election</label>
                <select name="election_id" class="form-control" required>
                    @foreach($elections as $election)
                        <option value="{{ $election->id }}">{{ $election->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Position -->
            <div class="form-group">
                <label>Position</label>
                <select name="position_id" class="form-control" required>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Manifesto -->
            <div class="form-group">
                <label>Manifesto</label>
                <textarea name="manifesto" class="form-control" rows="4"></textarea>
            </div>

            <!-- Is Approved -->
            <div class="form-group">
                <label>Approval Status</label>
                <select name="is_approved" class="form-control">
                    <option value="0" selected>Pending</option>
                    <option value="1">Approved</option>
                </select>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">
                Save Candidate
            </button>
        </div>

    </form>

</div>

@endsection