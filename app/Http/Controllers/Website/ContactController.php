<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public const SUBJECTS = ['General enquiry', 'Membership', 'Volunteering', 'Partnership', 'Programs', 'Events', 'Other'];

    public function show(Request $request): View
    {
        $subject = in_array($request->query('subject'), self::SUBJECTS, true) ? $request->query('subject') : null;

        return view('website.contact', ['subjects' => self::SUBJECTS, 'selectedSubject' => $subject]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Hidden "website" field: real visitors never fill it, spam bots do.
        if (filled($request->input('website'))) {
            return redirect()->route('site.contact')->with('success', 'Thank you! Your message has been sent.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactMessage::create($data + ['ip_address' => $request->ip()]);

        return redirect()->route('site.contact')->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
    }
}
