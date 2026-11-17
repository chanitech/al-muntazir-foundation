<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/** Management → Membership Applications: review applications sent from the website. */
class MembershipApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $status = in_array($request->query('status'), ['pending', 'approved', 'rejected', 'all'], true)
            ? $request->query('status')
            : 'pending';
        $term = trim((string) $request->query('q', ''));

        $applications = MembershipApplication::query()
            ->with('membershipType')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($term !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.membership-applications.index', [
            'applications' => $applications,
            'status' => $status,
            'search' => $term,
            'counts' => MembershipApplication::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function show(MembershipApplication $application): View
    {
        return view('admin.membership-applications.show', [
            'application' => $application->load(['membershipType', 'member', 'reviewer']),
            'existingMember' => $application->isPending() ? $application->existingMember() : null,
            'membershipTypes' => MembershipType::where('is_active', true)->orderBy('name')->get(),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    /** Approve: creates the member (and optionally a login account). */
    public function approve(Request $request, MembershipApplication $application): RedirectResponse
    {
        abort_unless($application->isPending(), 409, 'This application has already been reviewed.');

        $data = $request->validate([
            'membership_type_id' => ['required', Rule::exists('membership_types', 'id')],
            'create_account' => ['nullable', 'boolean'],
            'role' => ['required_if:create_account,1', 'nullable', Rule::exists('roles', 'name')],
        ]);

        if (Member::where('phone', $application->phone)->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'A member with the phone number '.$application->phone.' already exists.',
            ]);
        }

        $createAccount = $request->boolean('create_account');
        $loginEmail = $application->email ?: preg_replace('/\D+/', '', $application->phone).'@member.local';

        if ($createAccount && User::where('email', $loginEmail)->exists()) {
            throw ValidationException::withMessages([
                'create_account' => 'A login account with the e-mail '.$loginEmail.' already exists. Untick "Create a login account" or change the e-mail first.',
            ]);
        }

        $password = Str::password(10, symbols: false);

        $member = DB::transaction(function () use ($application, $data, $createAccount, $loginEmail, $password, $request) {
            $user = null;

            if ($createAccount) {
                $user = User::create([
                    'name' => trim($application->first_name.' '.$application->last_name),
                    'email' => $loginEmail,
                    'password' => Hash::make($password),
                ]);
                $user->assignRole($data['role']);
            }

            $member = Member::create([
                'user_id' => $user?->id,
                'membership_number' => Member::generateMembershipNumber(),
                'join_date' => now(),
                'membership_type_id' => $data['membership_type_id'],
                'status' => 'Active',
                'first_name' => $application->first_name,
                'middle_name' => $application->middle_name,
                'last_name' => $application->last_name,
                'full_name' => $application->fullName(),
                'date_of_birth' => $application->date_of_birth,
                'national_id' => $application->national_id,
                'phone' => $application->phone,
                'email' => $application->email,
                'current_region' => $application->current_region,
                'current_district' => $application->current_district,
                'current_village' => $application->current_village,
                'children_count' => 0,
                // Same record shape as the member registration wizard.
                'academic_history' => $application->education ? [[
                    'institution_name' => null, 'education_level' => $application->education,
                    'start_year' => null, 'end_year' => null, 'certificate_file' => null,
                ]] : [],
                'professional_history' => $application->occupation ? [[
                    'occupation' => $application->occupation, 'employer' => null,
                    'years_of_experience' => null, 'work_address' => null,
                ]] : [],
            ]);

            $application->update([
                'status' => MembershipApplication::APPROVED,
                'member_id' => $member->id,
                'membership_type_id' => $data['membership_type_id'],
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);

            return $member;
        });

        $redirect = redirect()->route('admin.applications.show', $application)
            ->with('success', $application->fullName().' is now a member with number '.$member->membership_number.'.');

        // Shown once so the administrator can pass the login details on.
        return $createAccount
            ? $redirect->with('new_login', ['email' => $loginEmail, 'password' => $password])
            : $redirect;
    }

    public function reject(Request $request, MembershipApplication $application): RedirectResponse
    {
        abort_unless($application->isPending(), 409, 'This application has already been reviewed.');

        $data = $request->validate(['review_note' => ['nullable', 'string', 'max:1000']]);

        $application->update([
            'status' => MembershipApplication::REJECTED,
            'review_note' => $data['review_note'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Application '.$application->reference().' was rejected.');
    }

    public function destroy(MembershipApplication $application): RedirectResponse
    {
        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Application deleted.');
    }
}
