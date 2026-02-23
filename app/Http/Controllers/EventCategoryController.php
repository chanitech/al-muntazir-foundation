<?php
namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::latest()->paginate(10);

        return view('admin.event_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.event_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated) {
            EventCategory::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });

        return redirect()
            ->route('event-categories.index')
            ->with('success', 'Event Category created successfully.');
    }

    public function edit(EventCategory $eventCategory)
    {
        return view('admin.event_categories.edit', compact('eventCategory'));
    }

    public function update(Request $request, EventCategory $eventCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,' . $eventCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $eventCategory) {
            $eventCategory->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? false,
            ]);
        });

        return redirect()
            ->route('event-categories.index')
            ->with('success', 'Event Category updated successfully.');
    }

    public function destroy(EventCategory $eventCategory)
    {
        $eventCategory->delete();

        return redirect()
            ->route('event-categories.index')
            ->with('success', 'Event Category deleted successfully.');
    }
}