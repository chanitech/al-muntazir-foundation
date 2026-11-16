<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipType;
use Illuminate\Http\Request;

class MembershipTypeController extends Controller
{

/*
|--------------------------------------------------------------------------
| List
|--------------------------------------------------------------------------
*/
public function index()
{
    $types = MembershipType::latest()->paginate(15);

    return view('admin.membership_types.index', compact('types'));
}

/*
|--------------------------------------------------------------------------
| Create Form
|--------------------------------------------------------------------------
*/
public function create()
{
    return view('admin.membership_types.create');
}

/*
|--------------------------------------------------------------------------
| Store
|--------------------------------------------------------------------------
*/
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:membership_types,name'
    ]);

    MembershipType::create([
        'name' => $request->name,
        'description' => $request->description,
        'is_active' => $request->is_active ? true : false
    ]);

    return redirect()
        ->route('membership-types.index')
        ->with('success','Membership Type created successfully');
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/
public function edit(MembershipType $membershipType)
{
    return view('admin.membership_types.create',
        compact('membershipType'));
}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/
public function update(Request $request, MembershipType $membershipType)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:membership_types,name,'.$membershipType->id
    ]);

    $membershipType->update([
        'name' => $request->name,
        'description' => $request->description,
        'is_active' => $request->is_active ? true : false
    ]);

    return redirect()
        ->route('membership-types.index')
        ->with('success','Membership Type updated successfully');
}

/*
|--------------------------------------------------------------------------
| Delete (Safe Delete Check)
|--------------------------------------------------------------------------
*/
public function destroy(MembershipType $membershipType)
{
    /*
    Prevent deleting type if members exist
    */

    if ($membershipType->members()->exists()) {
        return back()->withErrors(
            'Cannot delete. Members are using this type.'
        );
    }

    $membershipType->delete();

    return back()->with('success','Deleted successfully');
}

}