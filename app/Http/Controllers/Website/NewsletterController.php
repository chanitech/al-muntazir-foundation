<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('newsletter', [
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => mb_strtolower(trim($data['email']))],
            ['ip_address' => $request->ip()]
        );

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
