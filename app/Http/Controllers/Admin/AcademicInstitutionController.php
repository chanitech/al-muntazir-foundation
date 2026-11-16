<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicInstitution;
use Illuminate\Http\Request;

class AcademicInstitutionController extends Controller
{

/*
|--------------------------------------------------------------------------
| List
|--------------------------------------------------------------------------
*/
public function index()
{
    $institutions = AcademicInstitution::latest()
        ->paginate(15);

    return view('admin.academic_institutions.index',
        compact('institutions'));
}

/*
|--------------------------------------------------------------------------
| Create Form
|--------------------------------------------------------------------------
*/
public function create()
{
    return view('admin.academic_institutions.create');
}

/*
|--------------------------------------------------------------------------
| Store
|--------------------------------------------------------------------------
*/
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:academic_institutions,name'
    ]);

    AcademicInstitution::create([
        'name' => $request->name,
        'is_active' => $request->is_active ? true : false
    ]);

    return redirect()
        ->route('academic-institutions.index')
        ->with('success','Institution created');
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/
public function edit(AcademicInstitution $academicInstitution)
{
    return view('admin.academic_institutions.create',
        compact('academicInstitution'));
}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/
public function update(Request $request,
    AcademicInstitution $academicInstitution)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:academic_institutions,name,'.
            $academicInstitution->id
    ]);

    $academicInstitution->update([
        'name' => $request->name,
        'is_active' => $request->is_active ? true : false
    ]);

    return redirect()
        ->route('academic-institutions.index')
        ->with('success','Updated successfully');
}

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/
public function destroy(AcademicInstitution $academicInstitution)
{
    $academicInstitution->delete();

    return back()->with('success','Deleted');
}

}