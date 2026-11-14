<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberAcademicBackground;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcademicBackgroundController extends Controller
{
    public function index()
    {
        $records = MemberAcademicBackground::with('member')
            ->latest()
            ->paginate(20);

        return view('admin.academic.index', compact('records'));
    }

    public function create()
    {
        $members = Member::select('id','full_name')
            ->orderBy('full_name')
            ->get();

        return view('admin.academic.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'institution_name' => 'required',
            'education_level' => 'required'
        ]);

        $data = $request->all();

        /*
        |--------------------------------------------------------------------------
        | Certificate Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('certificate_file')) {

            $file = $request->file('certificate_file');

            $name = uniqid().'_'.$file->getClientOriginalName();

            $path = $file->storeAs(
                'certificates',
                $name,
                'public'
            );

            $data['certificate_file'] = $path;
        }

        MemberAcademicBackground::create($data);

        return redirect()
            ->route('academic.index')
            ->with('success','Academic record saved');
    }
}