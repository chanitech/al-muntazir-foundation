<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Inbox for messages sent through the website's contact form. */
class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter');
        $term = trim((string) $request->query('q', ''));

        $messages = ContactMessage::query()
            ->when($filter === 'unread', fn ($q) => $q->unread())
            ->when($term !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('subject', 'like', "%{$term}%")
                ->orWhere('message', 'like', "%{$term}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.website.messages.index', [
            'messages' => $messages,
            'filter' => $filter,
            'search' => $term,
            'unreadCount' => ContactMessage::unread()->count(),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->isRead()) {
            $message->update(['read_at' => now()]);
        }

        return view('admin.website.messages.show', compact('message'));
    }

    public function markUnread(ContactMessage $message): RedirectResponse
    {
        $message->update(['read_at' => null]);

        return redirect()->route('admin.messages.index')->with('success', 'Message marked as unread.');
    }

    public function markAllRead(): RedirectResponse
    {
        ContactMessage::unread()->update(['read_at' => now()]);

        return back()->with('success', 'All messages marked as read.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
