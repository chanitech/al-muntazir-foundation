<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function showChangeForm()
    {
        return view('auth.password-change'); // Blade view for the form
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validate form input
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Update password securely
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Optionally: logout other sessions
        // $request->session()->regenerate();

        return back()->with('success', 'Your password has been updated successfully.');
    }
}