<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** E-mail addresses collected by the newsletter box in the website footer. */
class SubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));

        return view('admin.website.subscribers.index', [
            'subscribers' => NewsletterSubscriber::query()
                ->when($term !== '', fn ($q) => $q->where('email', 'like', "%{$term}%"))
                ->latest()
                ->paginate(30)
                ->withQueryString(),
            'search' => $term,
            'total' => NewsletterSubscriber::count(),
        ]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Subscribed on']);

            NewsletterSubscriber::query()->orderBy('id')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    // Stop spreadsheet apps from treating a value as a formula.
                    $email = preg_match('/^[=+\-@\t\r]/', $row->email) ? "'".$row->email : $row->email;
                    fputcsv($out, [$email, $row->created_at?->format('Y-m-d H:i')]);
                }
            });

            fclose($out);
        }, 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber removed.');
    }
}
