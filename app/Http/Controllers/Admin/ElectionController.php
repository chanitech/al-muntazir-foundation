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

        return view('admin.elections.index',compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'start_time'=>'required',
            'end_time'=>'required'
        ]);

        Election::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'is_active'=>true
        ]);

        return redirect()
            ->route('elections.index')
            ->with('success','Election created successfully');
    }
}