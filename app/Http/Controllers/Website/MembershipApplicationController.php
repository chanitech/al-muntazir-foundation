<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** The public "Become a member" application form. */
class MembershipApplicationController extends Controller
{
    public function create(): View
    {
        return view('website.membership.apply', [
            'membershipTypes' => MembershipType::where('is_active', true)->orderBy('name')->get(),
            'regions' => Schema::hasTable('regions') ? Region::orderBy('name')->get(['id', 'name']) : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Hidden "website" field: people never fill it in, spam bots do.
        if (filled($request->input('website'))) {
            return redirect()->route('site.membership.apply')->with('application_sent', 'APP-000000');
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{7,30}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'membership_type_id' => ['nullable', Rule::exists('membership_types', 'id')->where('is_active', true)],
            'current_region' => ['nullable', 'string', 'max:100'],
            'current_district' => ['nullable', 'string', 'max:100'],
            'current_village' => ['nullable', 'string', 'max:150'],
            'education' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'motivation' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'consent.accepted' => 'Please confirm that the information is correct and that we may contact you.',
        ]);

        unset($data['consent']);
        $data['phone'] = trim(preg_replace('/\s+/', ' ', $data['phone']));

        // The same person applying twice while the first application is still open.
        $existing = MembershipApplication::pending()->where('phone', $data['phone'])->first();

        $application = $existing ?? MembershipApplication::create($data + [
            'status' => MembershipApplication::PENDING,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('site.membership.apply')->with('application_sent', $application->reference());
    }
}
