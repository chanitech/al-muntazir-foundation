<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\MaritalStatus;
use Illuminate\Http\Request;
use App\Services\MemberService;


class MemberController extends Controller
{
    protected MemberService $service;

    public function __construct(MemberService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $members = Member::with('membershipType')
            ->latest()
            ->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $membershipTypes = MembershipType::where('is_active', true)->get();
        $maritalStatuses = MaritalStatus::all();

        return view('admin.members.create', compact(
            'membershipTypes',
            'maritalStatuses'
        ));
    }

    public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'required|unique:members',
        'membership_type_id' => 'required',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Photo Upload
    $photoName = null;

    if ($request->hasFile('photo')) {

        $file = $request->file('photo');

        $photoName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->storeAs('public/photos', $photoName);
    }

    Member::create([
        'membership_number' => Member::generateMembershipNumber(),
        'join_date' => now(),
        'membership_type_id' => $request->membership_type_id ?? 1,
        'status' => 'Active',

        'full_name' => $request->full_name,
        'date_of_birth' => $request->date_of_birth,
        'place_of_birth' => $request->place_of_birth,
        'nationality' => $request->nationality,
        'religion' => $request->religion,
        'tribe' => $request->tribe,
        'national_id' => $request->national_id,
        'phone' => $request->phone,
        'email' => $request->email,
        'address' => $request->address,

        // Photo save filename only
        'photo' => $photoName,

        'marital_status_id' => $request->marital_status_id,
        'spouse_name' => $request->spouse_name,
        'spouse_phone' => $request->spouse_phone,
        'children_count' => $request->children_count ?? 0,
    ]);

    return redirect()
        ->route('admin.members.index')
        ->with('success','Member created successfully');
}

    public function show(Member $member)
    {
        return view('admin.members.show', compact('member'));
    }

    public function profile(Member $member)
{
    $member->load([
        'membershipType',
        'maritalStatus',
        'familyInfo',
        'academicBackgrounds',
        'professionalInfos',
        'skills'
    ]);

    return view('admin.members.profile', compact('member'));
}

    public function edit(Member $member)
    {
        $membershipTypes = MembershipType::where('is_active', true)->get();
        $maritalStatuses = MaritalStatus::all();

        return view('admin.members.edit', compact(
            'member',
            'membershipTypes',
            'maritalStatuses'
        ));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|unique:members,phone,' . $member->id,
        ]);

        $member->update($request->all());

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member updated successfully');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return back()->with('success', 'Member deleted successfully');
    }
}