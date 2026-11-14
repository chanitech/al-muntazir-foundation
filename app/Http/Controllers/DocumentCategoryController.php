<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------
    | List Categories
    |--------------------------------------------------------------
    */
    public function index()
    {
        $categories = DocumentCategory::latest()->paginate(20);

        return view('document_categories.index', compact('categories'));
    }

    /*
    |--------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------
    */
    public function create()
    {
        return view('document_categories.create');
    }

    /*
    |--------------------------------------------------------------
    | Store Category
    |--------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        DocumentCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => true
        ]);

        return redirect()
            ->route('document-categories.index')
            ->with('success','Category created successfully');
    }

    /*
    |--------------------------------------------------------------
    | Edit Form
    |--------------------------------------------------------------
    */
    public function edit(DocumentCategory $documentCategory)
    {
        return view('document_categories.edit', compact('documentCategory'));
    }

    /*
    |--------------------------------------------------------------
    | Update Category
    |--------------------------------------------------------------
    */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $documentCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()
            ->route('document-categories.index')
            ->with('success','Category updated successfully');
    }

    /*
    |--------------------------------------------------------------
    | Delete Category
    |--------------------------------------------------------------
    */
    public function destroy(DocumentCategory $documentCategory)
    {
        $documentCategory->delete();

        return back()->with('success','Category deleted successfully');
    }
}