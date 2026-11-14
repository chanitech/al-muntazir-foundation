<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\MaritalStatus;
use App\Models\AcademicInstitution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Exports\MembersExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Members (Filtering Supported ⭐)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        // Eager load relationships
        $query = Member::with(['membershipType', 'maritalStatus']);

        /*
        Search Filter
        */
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('membership_number', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        /*
        Status Filter
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        Membership Type Filter
        */
        if ($request->filled('type')) {
            $query->where('membership_type_id', $request->type);
        }

        /*
        Marital Status Filter
        */
        if ($request->filled('marital_status')) {
            $query->where('marital_status_id', $request->marital_status);
        }

        /*
        Employment Filters
        */
        if ($request->filled('employed')) {
            $query->where('is_employed', $request->employed);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        /*
        Location Filters
        */
        if ($request->filled('region')) {
            $query->where('current_region', 'like', '%' . $request->region . '%');
        }

        if ($request->filled('district')) {
            $query->where('current_district', 'like', '%' . $request->district . '%');
        }

        if ($request->filled('village')) {
            $query->where('current_village', 'like', '%' . $request->village . '%');
        }

        $members = $query->latest()
            ->paginate(15)
            ->appends($request->all());

        $memberTypes = MembershipType::where('is_active', true)->get();
        $maritalStatuses = MaritalStatus::all(); // For dropdown

        return view('admin.members.index', compact('members', 'memberTypes', 'maritalStatuses'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------------------
    */
   public function create()
{
    $membershipTypes = MembershipType::where('is_active', true)->get();
    $maritalStatuses = MaritalStatus::all();
    $institutions = AcademicInstitution::where('is_active', true)
        ->orderBy('name')
        ->get();
    $roles = Role::all(); // Fetch all roles (you can filter if needed)

    return view('admin.members.create', compact(
        'membershipTypes',
        'maritalStatuses',
        'institutions',
        'roles'   // <-- added
    ));
}

    /*
    |--------------------------------------------------------------------------
    | Edit Form
    |--------------------------------------------------------------------------
    */
    public function edit(Member $member)
    {
        // Load necessary relationships for the edit form
        $member->load(['membershipType', 'maritalStatus', 'skills']);

        // Get data for dropdowns
        $membershipTypes = MembershipType::where('is_active', true)->get();
        $maritalStatuses = MaritalStatus::all();
        $institutions = AcademicInstitution::where('is_active', true)
            ->orderBy('name')
            ->get();

        // 👇 NEW: Fetch roles for dropdown
    $roles = Role::all();
    // Optionally, get current role name for pre-selection
    $currentRole = $member->user ? $member->user->roles->first()?->name : '';

        return view('admin.members.edit', compact(
            'member',
            'membershipTypes',
            'maritalStatuses',
            'institutions',
            'roles',          // <-- pass to view
        'currentRole'     // <-- pass to view (optional, can be extracted in Blade)
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Unified Member Store (Enterprise Wizard Version ⭐)
    |--------------------------------------------------------------------------
    */
    

public function wizardStore(Request $request)
{
    Log::info("Wizard store started");

    DB::beginTransaction();

    try {
        $request->validate([
            // Existing validation rules...
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30|unique:members,phone',
            'membership_type_id' => 'required|exists:membership_types,id',
            'current_country' => 'nullable|string|max:100',
            'current_region' => 'nullable|string|max:100',
            'current_district' => 'nullable|string|max:100',
            'current_village' => 'nullable|string|max:100',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'spouse_first_name' => 'required_with:marital_status_id|nullable|string|max:100',
            'spouse_middle_name' => 'nullable|string|max:100',
            'spouse_surname' => 'required_with:marital_status_id|nullable|string|max:100',
            'spouse_phone' => 'required_with:marital_status_id|nullable|string|max:30',
            'children_count' => 'required_with:marital_status_id|nullable|integer|min:0',
            'is_employed' => 'sometimes|boolean',
            'employer_name' => 'required_if:is_employed,1|nullable|string|max:255',
            'job_title' => 'required_if:is_employed,1|nullable|string|max:255',
            'employment_status' => 'required_if:is_employed,1|nullable|in:full time,part time,contract,intern',
            'employer_address' => 'nullable|string',

            // 👇 NEW: Role validation
            'role' => 'required|string|exists:roles,name',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email ?? $request->phone . '@member.local',
            'password' => Hash::make(substr($request->phone, -6) . '@Member')
        ]);

        // 👇 NEW: Assign the selected role
        $user->assignRole($request->role);

        // Photo upload
        $photoName = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $photoName, 'public');
        }

        $membershipNumber = Member::generateMembershipNumber();

        // Build full name from parts
        $fullName = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);

        // Family info JSON
        $familyInfo = [
            'spouse_first_name' => $request->spouse_first_name,
            'spouse_middle_name' => $request->spouse_middle_name,
            'spouse_surname' => $request->spouse_surname,
            'spouse_phone' => $request->spouse_phone,
            'children_count' => $request->children_count ?? 0,
        ];

        // Process academics (including certificate file uploads)
        $academics = [];
        if ($request->has('academics') && is_array($request->academics)) {
            foreach ($request->academics as $index => $academicData) {
                $academic = [
                    'institution_name' => $academicData['institution_name'] ?? null,
                    'education_level' => $academicData['education_level'] ?? null,
                    'start_year' => $academicData['start_year'] ?? null,
                    'end_year' => $academicData['end_year'] ?? null,
                ];

                // Handle certificate file upload
                if ($request->hasFile("academics.$index.certificate_file")) {
                    $file = $request->file("academics.$index.certificate_file");
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('certificates', $fileName, 'public');
                    $academic['certificate_file'] = $fileName;
                } else {
                    $academic['certificate_file'] = null;
                }

                $academics[] = $academic;
            }
        }

        // Process professionals (no file uploads assumed)
        $professionals = [];
        if ($request->has('professionals') && is_array($request->professionals)) {
            foreach ($request->professionals as $professionalData) {
                $professionals[] = [
                    'occupation' => $professionalData['occupation'] ?? null,
                    'employer' => $professionalData['employer'] ?? null,
                    'years_of_experience' => $professionalData['years_of_experience'] ?? null,
                    'work_address' => $professionalData['work_address'] ?? null,
                ];
            }
        }

        // Create member
        $member = Member::create([
            'user_id' => $user->id,
            'membership_number' => $membershipNumber,
            'join_date' => now(),
            'membership_type_id' => $request->membership_type_id,
            'status' => $request->status ?? 'Active',

            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'full_name' => $fullName,

            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'national_id' => $request->national_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'current_country' => $request->current_country,
            'current_region' => $request->current_region,
            'current_district' => $request->current_district,
            'current_village' => $request->current_village,

            'marital_status_id' => $request->marital_status_id,
            'spouse_first_name' => $request->spouse_first_name,
            'spouse_middle_name' => $request->spouse_middle_name,
            'spouse_surname' => $request->spouse_surname,
            'spouse_phone' => $request->spouse_phone,
            'children_count' => $request->children_count ?? 0,

            'is_employed' => $request->boolean('is_employed'),
            'employer_name' => $request->employer_name,
            'job_title' => $request->job_title,
            'employment_status' => $request->employment_status,
            'employer_address' => $request->employer_address,

            'photo' => $photoName,

            'academic_history' => $academics,
            'professional_history' => $professionals,

            'family_info' => $familyInfo,
        ]);

        // Skills sync
        if ($request->filled('skills')) {
            $member->skills()->sync($request->skills);
        }

        DB::commit();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member registered successfully');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Member Wizard Store Error: ' . $e->getMessage());
        return back()->withErrors('Registration failed: ' . $e->getMessage());
    }
}

    /*
    |--------------------------------------------------------------------------
    | Profile View
    |--------------------------------------------------------------------------
    */
    public function profile(Member $member)
    {
        $member->load([
            'membershipType',
            'maritalStatus',
            'skills'
        ]);

        return view('admin.members.profile', compact('member'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Member (Enhanced for full wizard data)
    |--------------------------------------------------------------------------
    */
   public function update(Request $request, Member $member)
{
    // Validate input
    $validated = $request->validate([
        // Membership
        'membership_type_id' => 'required|exists:membership_types,id',
        'status' => 'required|in:Active,Inactive',

        // Personal
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'date_of_birth' => 'nullable|date',
        'phone' => 'required|string|max:30|unique:members,phone,' . $member->id,
        'email' => 'nullable|email|max:255|unique:members,email,' . $member->id,
        'national_id' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'current_country' => 'nullable|string|max:100',
        'current_region' => 'nullable|string|max:100',
        'current_district' => 'nullable|string|max:100',
        'current_village' => 'nullable|string|max:100',

        // Family
        'marital_status_id' => 'nullable|exists:marital_statuses,id',
        'spouse_first_name' => 'required_with:marital_status_id|nullable|string|max:100',
        'spouse_middle_name' => 'nullable|string|max:100',
        'spouse_surname' => 'required_with:marital_status_id|nullable|string|max:100',
        'spouse_phone' => 'required_with:marital_status_id|nullable|string|max:30',
        'children_count' => 'required_with:marital_status_id|nullable|integer|min:0',

        // Employment
        'is_employed' => 'sometimes|boolean',
        'employer_name' => 'required_if:is_employed,1|nullable|string|max:255',
        'job_title' => 'required_if:is_employed,1|nullable|string|max:255',
        'employment_status' => 'required_if:is_employed,1|nullable|in:full time,part time,contract,intern',
        'employer_address' => 'nullable|string',

        // Photo
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        // Academic and professional are arrays; we'll validate loosely
        'academics' => 'nullable|array',
        'academics.*.institution_name' => 'nullable|string|max:255',
        'academics.*.education_level' => 'nullable|string|max:50',
        'academics.*.start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
        'academics.*.end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
        'academics.*.certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',

        'professionals' => 'nullable|array',
        'professionals.*.occupation' => 'nullable|string|max:255',
        'professionals.*.employer' => 'nullable|string|max:255',
        'professionals.*.years_of_experience' => 'nullable|integer|min:0',
        'professionals.*.work_address' => 'nullable|string',

        // Role assignment
        'role' => 'required|string|exists:roles,name',
    ]);

    DB::beginTransaction();

    try {
        // Update the user's role if a user account exists
        if ($member->user) {
            $member->user->syncRoles($request->role);
        }

        // Build full name from parts
        $fullName = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);

        // Prepare data for update
        $data = [
            'membership_type_id' => $request->membership_type_id,
            'status' => $request->status,

            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'full_name' => $fullName,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'email' => $request->email,
            'national_id' => $request->national_id,
            'address' => $request->address,
            'current_country' => $request->current_country,
            'current_region' => $request->current_region,
            'current_district' => $request->current_district,
            'current_village' => $request->current_village,

            'marital_status_id' => $request->marital_status_id,
            'spouse_first_name' => $request->spouse_first_name,
            'spouse_middle_name' => $request->spouse_middle_name,
            'spouse_surname' => $request->spouse_surname,
            'spouse_phone' => $request->spouse_phone,
            'children_count' => $request->children_count ?? 0,

            'is_employed' => $request->boolean('is_employed'),
            'employer_name' => $request->employer_name,
            'job_title' => $request->job_title,
            'employment_status' => $request->employment_status,
            'employer_address' => $request->employer_address,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo && Storage::disk('public')->exists('photos/' . $member->photo)) {
                Storage::disk('public')->delete('photos/' . $member->photo);
            }
            $file = $request->file('photo');
            $photoName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $photoName, 'public');
            $data['photo'] = $photoName;
        }

        // Process academic records
        $academics = [];
        if ($request->has('academics') && is_array($request->academics)) {
            foreach ($request->academics as $index => $academicData) {
                $academic = [
                    'institution_name' => $academicData['institution_name'] ?? null,
                    'education_level' => $academicData['education_level'] ?? null,
                    'start_year' => $academicData['start_year'] ?? null,
                    'end_year' => $academicData['end_year'] ?? null,
                ];

                // Handle certificate file upload
                if ($request->hasFile("academics.$index.certificate_file")) {
                    $file = $request->file("academics.$index.certificate_file");
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('certificates', $fileName, 'public');
                    $academic['certificate_file'] = $fileName;
                } else {
                    $academic['certificate_file'] = null;
                }
                $academics[] = $academic;
            }
        }

        // Merge with old data to preserve certificate files for unchanged records
        $oldAcademics = is_array($member->academic_history) ? $member->academic_history : json_decode($member->academic_history, true) ?? [];
        $newAcademics = [];
        foreach ($academics as $idx => $acad) {
            // If certificate is not set by new upload, try to find a matching old record to copy certificate file
            if (empty($acad['certificate_file'])) {
                foreach ($oldAcademics as $old) {
                    if (($old['institution_name'] ?? '') == ($acad['institution_name'] ?? '') &&
                        ($old['education_level'] ?? '') == ($acad['education_level'] ?? '') &&
                        ($old['start_year'] ?? '') == ($acad['start_year'] ?? '') &&
                        ($old['end_year'] ?? '') == ($acad['end_year'] ?? '')) {
                        $acad['certificate_file'] = $old['certificate_file'] ?? null;
                        break;
                    }
                }
            }
            $newAcademics[] = $acad;
        }
        $data['academic_history'] = $newAcademics;

        // Process professional records
        $professionals = [];
        if ($request->has('professionals') && is_array($request->professionals)) {
            foreach ($request->professionals as $professionalData) {
                $professionals[] = [
                    'occupation' => $professionalData['occupation'] ?? null,
                    'employer' => $professionalData['employer'] ?? null,
                    'years_of_experience' => $professionalData['years_of_experience'] ?? null,
                    'work_address' => $professionalData['work_address'] ?? null,
                ];
            }
        }
        $data['professional_history'] = $professionals;

        // Update member
        $member->update($data);

        // Skills sync if applicable
        if ($request->filled('skills')) {
            $member->skills()->sync($request->skills);
        }

        DB::commit();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Member Update Error: ' . $e->getMessage());
        return back()->withErrors('Update failed: ' . $e->getMessage())->withInput();
    }
}

    /*
    |--------------------------------------------------------------------------
    | Soft Delete Member
    |--------------------------------------------------------------------------
    */
    public function destroy(Member $member)
    {
        $member->delete();
        return back()->with('success', 'Member deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Export Members
    |--------------------------------------------------------------------------
    */
    public function export(Request $request, $type)
    {
        $filters = $request->only([
            'search', 'status', 'type', 'marital_status',
            'employed', 'employment_status', 'region', 'district', 'village'
        ]);

        // Remove empty filters
        $filters = array_filter($filters, function ($value) {
            return $value !== '' && $value !== null;
        });

        $export = new MembersExport($filters);

        if ($type == 'csv') {
            return Excel::download($export, 'members.csv', \Maatwebsite\Excel\Excel::CSV);
        } elseif ($type == 'excel') {
            return Excel::download($export, 'members.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } elseif ($type == 'pdf') {
            return Excel::download($export, 'members.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        abort(404);
    }
}