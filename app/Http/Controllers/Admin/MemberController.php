<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\MaritalStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use log;

class MemberController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Members
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $members = Member::with('membershipType')
            ->latest()
            ->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Member Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $membershipTypes = MembershipType::where('is_active', true)->get();
        $maritalStatuses = MaritalStatus::all();

        return view('admin.members.create', compact(
            'membershipTypes',
            'maritalStatuses'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Member + User Account
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|unique:members,phone',
                'email' => 'nullable|email|unique:users,email'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create User Account
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email ?? $request->phone . '@foundation.local',
                'password' => bcrypt('12345678')
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Member Record
            |--------------------------------------------------------------------------
            */

            Member::create([
                'user_id' => $user->id,
                'membership_number' => Member::generateMembershipNumber(),
                'join_date' => now(),
                'membership_type_id' => $request->membership_type_id ?? 1,
                'status' => 'Active',

                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'photo' => null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.members.index')
                ->with('success','Member and user account created successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error($e->getMessage());

            return back()->withErrors('Member creation failed');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show Member Profile
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Update Member
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|unique:members,phone,' . $member->id,
        ]);

        $member->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Member
    |--------------------------------------------------------------------------
    */
    public function destroy(Member $member)
    {
        $member->delete();

        return back()->with('success', 'Member deleted successfully');
    }
}