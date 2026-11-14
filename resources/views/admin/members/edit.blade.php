@extends('adminlte::page')

@section('title', 'Edit Member: ' . $member->full_name)

@section('content')
<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0">Edit Member: {{ $member->full_name }}</h5>
    </div>

    <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <div class="card-body">
            {{-- Progress Bar --}}
            <div class="progress mb-3">
                <div class="progress-bar" id="editProgress" style="width:0%"></div>
            </div>

            <ul class="nav nav-tabs" id="editTab">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#step1">Membership</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step2">Personal</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step3">Family</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step4">Academic</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step5">Professional</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step6">Employment</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step7">Attachments</a></li>
            </ul>

            <br>

            @php
                // Prepare academic and professional data, prioritizing old() input from failed validation
                $oldAcademics = old('academics', is_array($member->academic_history) ? $member->academic_history : json_decode($member->academic_history, true) ?? []);
                $oldProfessionals = old('professionals', is_array($member->professional_history) ? $member->professional_history : json_decode($member->professional_history, true) ?? []);
                // Get current user role (first role name)
                $currentRole = $member->user ? $member->user->roles->first()?->name : '';
            @endphp

            <div class="tab-content">
                {{-- STEP 1: Membership --}}
                <div class="tab-pane fade show active" id="step1">
                    <div class="form-group">
                        <label>Membership Type <span class="text-danger">*</span></label>
                        <select name="membership_type_id" class="form-control select2" required>
                            <option value="">Select Type</option>
                            @foreach($membershipTypes as $type)
                                <option value="{{ $type->id }}" {{ old('membership_type_id', $member->membership_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ROLE SELECTION --}}
                    <div class="form-group">
                        <label>System Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Determines what this member can access in the system.</small>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Active" {{ old('status', $member->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $member->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- STEP 2: Personal --}}
                <div class="tab-pane fade" id="step2">
                    <div class="row">
                        <div class="col-md-4">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input name="first_name" class="form-control" value="{{ old('first_name', $member->first_name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>Middle Name</label>
                            <input name="middle_name" class="form-control" value="{{ old('middle_name', $member->middle_name) }}">
                        </div>
                        <div class="col-md-4">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input name="last_name" class="form-control" value="{{ old('last_name', $member->last_name) }}" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($member->date_of_birth)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" required>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Email</label>
                        <input name="email" class="form-control" value="{{ old('email', $member->email) }}">
                    </div>
                    <div class="form-group"><label>National ID</label><input name="national_id" class="form-control" value="{{ old('national_id', $member->national_id) }}"></div>
                    <hr>
                    <h6>Current Address</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Country</label>
                            <select name="current_country" id="country" class="form-control">
                                <option value="Tanzania" {{ old('current_country', $member->current_country) == 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Region</label>
                            <select id="region" class="form-control">
                                <option value="">Select Region</option>
                            </select>
                            <input type="hidden" name="region_id" id="region_id" value="{{ old('region_id', $member->region_id ?? '') }}">
                            <input type="hidden" name="current_region" id="current_region" value="{{ old('current_region', $member->current_region) }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>District</label>
                            <select id="district" class="form-control">
                                <option value="">Select District</option>
                            </select>
                            <input type="hidden" name="district_id" id="district_id" value="{{ old('district_id', $member->district_id ?? '') }}">
                            <input type="hidden" name="current_district" id="current_district" value="{{ old('current_district', $member->current_district) }}">
                        </div>
                        <div class="col-md-6">
                            <label>Village/Street</label>
                            <input name="current_village" class="form-control" value="{{ old('current_village', $member->current_village) }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group"><label>Permanent Address</label><textarea name="address" class="form-control">{{ old('address', $member->address) }}</textarea></div>
                </div>

                {{-- STEP 3: Family --}}
                <div class="tab-pane fade" id="step3">
                    <div class="form-group">
                        <label>Marital Status</label>
                        <select name="marital_status_id" id="maritalStatus" class="form-control" onchange="toggleFamilyFields()">
                            <option value="">None</option>
                            @foreach($maritalStatuses as $status)
                                <option value="{{ $status->id }}" {{ old('marital_status_id', $member->marital_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="familyFields" style="display: {{ old('marital_status_id', $member->marital_status_id) ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-4"><label>Spouse First Name</label><input name="spouse_first_name" class="form-control" value="{{ old('spouse_first_name', $member->spouse_first_name) }}"></div>
                            <div class="col-md-4"><label>Spouse Middle Name</label><input name="spouse_middle_name" class="form-control" value="{{ old('spouse_middle_name', $member->spouse_middle_name) }}"></div>
                            <div class="col-md-4"><label>Spouse Surname</label><input name="spouse_surname" class="form-control" value="{{ old('spouse_surname', $member->spouse_surname) }}"></div>
                        </div>
                        <div class="form-group mt-2"><label>Spouse Phone</label><input name="spouse_phone" class="form-control" value="{{ old('spouse_phone', $member->spouse_phone) }}"></div>
                        <div class="form-group"><label>Children Count</label><input type="number" name="children_count" class="form-control" value="{{ old('children_count', $member->children_count ?? 0) }}"></div>
                    </div>
                </div>

                {{-- STEP 4: Academic --}}
                <div class="tab-pane fade" id="step4">
                    <h6 class="text-primary mb-2">Academic Background</h6>
                    <button type="button" class="btn btn-success btn-sm mb-2" onclick="addAcademicRow()">+ Add Academic Record</button>
                    <table class="table table-bordered">
                        <thead>
                            <th>Institution</th>
                            <th>Level</th>
                            <th>Start Year</th>
                            <th>End Year</th>
                            <th>Certificate</th>
                            <th></th>
                        </thead>
                        <tbody id="academicBody">
                            @foreach($oldAcademics as $index => $acad)
                            <tr>
                                <td style="min-width:220px">
                                    <select name="academics[{{ $index }}][institution_name]" class="form-control" required>
                                        <option value="">Search or type institution</option>
                                        @foreach($institutions as $inst)
                                        <option value="{{ $inst->name }}" {{ ($acad['institution_name'] ?? '') == $inst->name ? 'selected' : '' }}>{{ $inst->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="academics[{{ $index }}][education_level]" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Certificate" {{ ($acad['education_level'] ?? '') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                        <option value="Diploma" {{ ($acad['education_level'] ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="Bachelor" {{ ($acad['education_level'] ?? '') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="Master" {{ ($acad['education_level'] ?? '') == 'Master' ? 'selected' : '' }}>Master</option>
                                        <option value="PhD" {{ ($acad['education_level'] ?? '') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        <option value="Professional" {{ ($acad['education_level'] ?? '') == 'Professional' ? 'selected' : '' }}>Professional</option>
                                    </select>
                                </td>
                                <td><input type="number" name="academics[{{ $index }}][start_year]" class="form-control" value="{{ $acad['start_year'] ?? '' }}"></td>
                                <td><input type="number" name="academics[{{ $index }}][end_year]" class="form-control" value="{{ $acad['end_year'] ?? '' }}"></td>
                                <td>
                                    @if(!empty($acad['certificate_file']))
                                        <a href="{{ asset('storage/certificates/'.$acad['certificate_file']) }}" target="_blank">View current</a>
                                        <br><small>Upload new to replace</small>
                                    @endif
                                    <input type="file" name="academics[{{ $index }}][certificate_file]" class="form-control" accept=".pdf,.jpg,.png">
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- STEP 5: Professional --}}
                <div class="tab-pane fade" id="step5">
                    <h6 class="text-primary mb-2">Professional Experience</h6>
                    <button type="button" class="btn btn-success btn-sm mb-2" onclick="addProfessionalRow()">+ Add Experience</button>
                    <table class="table table-bordered">
                        <thead>
                            <th>Occupation</th>
                            <th>Employer</th>
                            <th>Years of Experience</th>
                            <th>Work Address</th>
                            <th></th>
                        </thead>
                        <tbody id="professionalBody">
                            @foreach($oldProfessionals as $index => $prof)
                            <tr>
                                <td><input name="professionals[{{ $index }}][occupation]" class="form-control" value="{{ $prof['occupation'] ?? '' }}"></td>
                                <td><input name="professionals[{{ $index }}][employer]" class="form-control" value="{{ $prof['employer'] ?? '' }}"></td>
                                <td><input name="professionals[{{ $index }}][years_of_experience]" class="form-control" value="{{ $prof['years_of_experience'] ?? '' }}"></td>
                                <td><input name="professionals[{{ $index }}][work_address]" class="form-control" value="{{ $prof['work_address'] ?? '' }}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- STEP 6: Employment --}}
                <div class="tab-pane fade" id="step6">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="hidden" name="is_employed" value="0">
                            <input class="form-check-input" type="checkbox" name="is_employed" id="isEmployed" value="1" {{ old('is_employed', $member->is_employed) ? 'checked' : '' }} onclick="toggleEmployment()">
                            <label class="form-check-label" for="isEmployed">Currently Employed?</label>
                        </div>
                    </div>
                    <div id="employmentFields" style="display: {{ old('is_employed', $member->is_employed) ? 'block' : 'none' }};">
                        <div class="form-group"><label>Employer Name</label><input name="employer_name" class="form-control" value="{{ old('employer_name', $member->employer_name) }}"></div>
                        <div class="form-group"><label>Job Title</label><input name="job_title" class="form-control" value="{{ old('job_title', $member->job_title) }}"></div>
                        <div class="form-group"><label>Employment Status</label>
                            <select name="employment_status" class="form-control">
                                <option value="">Select</option>
                                <option value="full time" {{ old('employment_status', $member->employment_status) == 'full time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part time" {{ old('employment_status', $member->employment_status) == 'part time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_status', $member->employment_status) == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="intern" {{ old('employment_status', $member->employment_status) == 'intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Physical Address</label><textarea name="employer_address" class="form-control" rows="3">{{ old('employer_address', $member->employer_address) }}</textarea></div>
                    </div>
                </div>

                {{-- STEP 7: Attachments --}}
                <div class="tab-pane fade" id="step7">
                    <div class="form-group">
                        <label>Profile Photo</label>
                        @if($member->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/photos/'.$member->photo) }}" alt="Current Photo" style="max-height: 100px;">
                                <br><small>Current photo. Upload new to replace.</small>
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary" onclick="validateAndNextStep()">Next Step</button>
            <button class="btn btn-warning">Update Member</button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
// ========== FUNCTIONS ==========
function toggleFamilyFields() {
    var maritalStatus = document.getElementById('maritalStatus').value;
    var familyFields = document.getElementById('familyFields');
    familyFields.style.display = maritalStatus ? 'block' : 'none';
}

function toggleEmployment() {
    var employed = document.getElementById('isEmployed').checked;
    var employmentFields = document.getElementById('employmentFields');
    employmentFields.style.display = employed ? 'block' : 'none';
}

// Wizard navigation with validation
const wizardSteps = ['#step1','#step2','#step3','#step4','#step5','#step6','#step7'];
let currentStep = 0;

function validateStep(stepIndex) {
    let stepId = wizardSteps[stepIndex];
    let stepElement = document.querySelector(stepId);
    let requiredFields = stepElement.querySelectorAll('[required]');
    let valid = true;
    let firstInvalid = null;

    requiredFields.forEach(field => {
        if (field.offsetParent !== null) {
            field.classList.remove('is-invalid');
            let value = field.value.trim();
            if (value === '') {
                valid = false;
                field.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = field;
            }
        }
    });

    if (!valid && firstInvalid) {
        alert('Please fill all required fields in this step.');
        firstInvalid.focus();
    }
    return valid;
}

function showStep(index) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
    document.querySelector(wizardSteps[index]).classList.add('show', 'active');
    document.querySelector('#editTab a[href="'+wizardSteps[index]+'"]').classList.add('active');
    updateProgress();
}

function nextStep() {
    if (currentStep < wizardSteps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
}

function validateAndNextStep() {
    if (validateStep(currentStep)) {
        nextStep();
    }
}

function updateProgress() {
    let percent = ((currentStep + 1) / wizardSteps.length) * 100;
    document.getElementById('editProgress').style.width = percent + '%';
}

// ========== ACADEMIC AND PROFESSIONAL ROWS ==========
let academicIndex = {{ count($oldAcademics) }};
function addAcademicRow() {
    let row = document.createElement('tr');
    row.innerHTML = `
        <td style="min-width:220px">
            <select name="academics[${academicIndex}][institution_name]" class="form-control" required>
                <option value="">Search or type institution</option>
                @foreach($institutions as $inst)
                <option value="{{ $inst->name }}">{{ $inst->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="academics[${academicIndex}][education_level]" class="form-control">
                <option>Certificate</option><option>Diploma</option><option>Bachelor</option><option>Master</option><option>PhD</option><option>Professional</option>
            </select>
        </td>
        <td><input type="number" name="academics[${academicIndex}][start_year]" class="form-control"></td>
        <td><input type="number" name="academics[${academicIndex}][end_year]" class="form-control"></td>
        <td><input type="file" name="academics[${academicIndex}][certificate_file]" class="form-control" accept=".pdf,.jpg,.png"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
    `;
    document.getElementById('academicBody').appendChild(row);
    academicIndex++;
}

let profIndex = {{ count($oldProfessionals) }};
function addProfessionalRow() {
    let row = document.createElement('tr');
    row.innerHTML = `
        <td><input name="professionals[${profIndex}][occupation]" class="form-control"></td>
        <td><input name="professionals[${profIndex}][employer]" class="form-control"></td>
        <td><input name="professionals[${profIndex}][years_of_experience]" class="form-control"></td>
        <td><input name="professionals[${profIndex}][work_address]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
    `;
    document.getElementById('professionalBody').appendChild(row);
    profIndex++;
}

// ========== CASCADING DROPDOWNS ==========
$(document).ready(function() {
    // Load regions
    $.get('{{ route("regions.list") }}', function(regions) {
        var regionSelect = $('#region');
        regionSelect.empty().append('<option value="">Select Region</option>');
        $.each(regions, function(index, region) {
            regionSelect.append('<option value="' + region.id + '">' + region.name + '</option>');
        });
        // Pre-select the saved region if any
        var savedRegionId = $('#region_id').val();
        if (savedRegionId) {
            regionSelect.val(savedRegionId);
            triggerDistrictLoad(savedRegionId);
        }
    });

    $('#region').change(function() {
        var regionId = $(this).val();
        var regionName = $(this).find('option:selected').text();
        $('#region_id').val(regionId);
        $('#current_region').val(regionName);
        triggerDistrictLoad(regionId);
    });

    function triggerDistrictLoad(regionId) {
        if (regionId) {
            $.get('/districts/' + regionId, function(districts) {
                var districtSelect = $('#district');
                districtSelect.empty().append('<option value="">Select District</option>');
                $.each(districts, function(index, district) {
                    districtSelect.append('<option value="' + district.id + '">' + district.name + '</option>');
                });
                var savedDistrictId = $('#district_id').val();
                if (savedDistrictId) {
                    districtSelect.val(savedDistrictId);
                    var districtName = districtSelect.find('option:selected').text();
                    $('#current_district').val(districtName);
                }
            });
        } else {
            $('#district').empty().append('<option value="">Select District</option>');
            $('#district_id').val('');
            $('#current_district').val('');
        }
    }

    $('#district').change(function() {
        var districtId = $(this).val();
        var districtName = $(this).find('option:selected').text();
        $('#district_id').val(districtId);
        $('#current_district').val(districtName);
    });

    // Remove is-invalid class when user starts typing
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Initially set the first step active and update progress
    showStep(0);
});
</script>

<style>
.is-invalid {
    border-color: #dc3545 !important;
}
</style>
@endpush