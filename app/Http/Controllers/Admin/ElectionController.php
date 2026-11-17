<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::latest()->paginate(10);

        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        Election::create($this->validated($request) + ['is_active' => true]);

        return redirect()
            ->route('elections.index')
            ->with('success', 'Election created successfully');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    /** Correct the name or voting period, or close the election early. */
    public function update(Request $request, Election $election)
    {
        $election->update($this->validated($request) + ['is_active' => $request->boolean('is_active')]);

        return redirect()
            ->route('elections.index')
            ->with('success', 'Election updated successfully');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ], [
            'end_time.after' => 'The voting end time must be after the start time.',
        ]);
    }
}
