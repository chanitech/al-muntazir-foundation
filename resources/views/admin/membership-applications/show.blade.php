@extends('adminlte::page')

@section('title', 'Application '.$application->reference())

@section('content_header')
    <a href="{{ route('admin.applications.index') }}" class="btn btn-link px-0"><i class="fas fa-arrow-left mr-1"></i> Back to applications</a>
    <h1 class="mt-1">{{ $application->fullName() }} <small class="text-muted">{{ $application->reference() }}</small></h1>
@stop

@section('content')
    <x-admin.flash />

    <x-admin.new-login />

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-outline {{ $application->isPending() ? 'card-warning' : ($application->status === 'approved' ? 'card-success' : 'card-secondary') }}">
                <div class="card-header">
                    <h3 class="card-title">Application details</h3>
                    <div class="card-tools pt-1 small text-muted">Received {{ $application->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Full name</dt><dd class="col-sm-8">{{ $application->fullName() }}</dd>
                        <dt class="col-sm-4">Date of birth</dt><dd class="col-sm-8">{{ $application->date_of_birth?->format('d M Y') ?? '—' }}
                            @if ($application->date_of_birth)<span class="text-muted">({{ $application->date_of_birth->age }} years)</span>@endif</dd>
                        <dt class="col-sm-4">National ID</dt><dd class="col-sm-8">{{ $application->national_id ?: '—' }}</dd>
                        <dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $application->phone) }}">{{ $application->phone }}</a></dd>
                        <dt class="col-sm-4">E-mail</dt><dd class="col-sm-8">@if ($application->email)<a href="mailto:{{ $application->email }}">{{ $application->email }}</a>@else — @endif</dd>
                        <dt class="col-sm-4">Location</dt><dd class="col-sm-8">{{ collect([$application->current_village, $application->current_district, $application->current_region])->filter()->implode(', ') ?: '—' }}</dd>
                        <dt class="col-sm-4">Membership type</dt><dd class="col-sm-8">{{ $application->membershipType->name ?? 'Not chosen' }}</dd>
                        <dt class="col-sm-4">Education</dt><dd class="col-sm-8">{{ $application->education ?: '—' }}</dd>
                        <dt class="col-sm-4">Occupation</dt><dd class="col-sm-8">{{ $application->occupation ?: '—' }}</dd>
                    </dl>
                    @if ($application->motivation)
                        <hr>
                        <h6 class="font-weight-bold">Why they want to join</h6>
                        <p class="mb-0" style="white-space: pre-line">{{ $application->motivation }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if ($application->isPending())
                @if ($existingMember)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        A member with this phone number already exists:
                        <a href="{{ route('admin.members.profile', $existingMember) }}" class="alert-link">{{ $existingMember->full_name }} ({{ $existingMember->membership_number }})</a>.
                        This is probably a duplicate application.
                    </div>
                @endif

                @can('create members')
                    <div class="card card-success card-outline">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-user-check mr-1"></i> Approve</h3></div>
                        <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                            @csrf
                            <div class="card-body">
                                <p class="text-muted small">Creates the member with a new membership number.</p>
                                <div class="form-group">
                                    <label for="membership_type_id">Membership type</label>
                                    <select name="membership_type_id" id="membership_type_id" class="form-control @error('membership_type_id') is-invalid @enderror" required>
                                        @foreach ($membershipTypes as $type)
                                            <option value="{{ $type->id }}" @selected(old('membership_type_id', $application->membership_type_id) == $type->id)>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('membership_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="custom-control custom-switch mb-2">
                                    <input type="hidden" name="create_account" value="0">
                                    <input type="checkbox" class="custom-control-input" id="create_account" name="create_account" value="1"
                                           @checked(old('create_account', true)) onchange="document.getElementById('roleField').style.display = this.checked ? '' : 'none'">
                                    <label class="custom-control-label" for="create_account">Create a login account (to vote and see documents)</label>
                                </div>
                                <div class="form-group" id="roleField" @if(! old('create_account', true)) style="display: none" @endif>
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" @selected(old('role', 'Member') === $role)>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Login e-mail: {{ $application->email ?: preg_replace('/\D+/', '', $application->phone).'@member.local' }}</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" @disabled($existingMember)><i class="fas fa-check mr-1"></i> Approve and create member</button>
                            </div>
                        </form>
                    </div>

                    <div class="card card-outline card-secondary">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-user-times mr-1"></i> Reject</h3></div>
                        <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirm('Reject this application?')">
                            @csrf
                            <div class="card-body">
                                <x-admin.textarea name="review_note" label="Reason (internal note, optional)" rows="2" />
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-outline-danger"><i class="fas fa-times mr-1"></i> Reject application</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="callout callout-info">You can view this application. Approving or rejecting it needs the “create members” permission.</div>
                @endcan
            @else
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">
                            @if ($application->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-secondary">Rejected</span>
                            @endif
                        </h5>
                        <p class="mb-1">Reviewed {{ $application->reviewed_at?->format('d M Y, H:i') }}
                            @if ($application->reviewer) by {{ $application->reviewer->name }} @endif</p>
                        @if ($application->member)
                            <p class="mb-0">Member: <a href="{{ route('admin.members.profile', $application->member) }}">{{ $application->member->full_name }} ({{ $application->member->membership_number }})</a></p>
                        @endif
                        @if ($application->review_note)
                            <p class="mt-2 mb-0 text-muted">Note: {{ $application->review_note }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @can('delete members')
                <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" onsubmit="return confirm('Delete this application permanently?')" class="text-right">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger"><i class="fas fa-trash mr-1"></i> Delete application</button>
                </form>
            @endcan
        </div>
    </div>
@stop
