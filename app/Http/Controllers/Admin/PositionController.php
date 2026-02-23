<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->paginate(10);

        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);

        Position::create([
            'name'=>$request->name
        ]);

        return redirect()
            ->route('positions.index')
            ->with('success','Position created');
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit',compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name'=>'required'
        ]);

        $position->update([
            'name'=>$request->name
        ]);

        return redirect()
            ->route('positions.index')
            ->with('success','Updated successfully');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return back()->with('success','Deleted');
    }
}