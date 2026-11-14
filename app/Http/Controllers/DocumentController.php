<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DocumentUploadRequest;
use App\Services\Document\DocumentUploadService;
use App\Services\Document\DocumentAuditService;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;



class DocumentController extends Controller
{
    protected DocumentUploadService $uploadService;
    protected DocumentAuditService $auditService;

    public function __construct(
        DocumentUploadService $uploadService,
        DocumentAuditService $auditService
    ) {
        $this->uploadService = $uploadService;
        $this->auditService = $auditService;
    }

    /*
    |--------------------------------------------------------------------------
    | Document Listing
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $documents = Document::with('category')
            ->latest()
            ->paginate(20);

        return view('documents.index', compact('documents'));
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Form View
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('documents.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Document
    |--------------------------------------------------------------------------
    */
   

public function store(Request $request)
{
    $request->validate([
        'document_category_id' => 'required|exists:document_categories,id',
        'title' => 'required|string|max:255',
        'file' => [
    'required',
    'file',
    'max:51200',
    'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip'
]
    ]);

    $file = $request->file('file');

    $extension = $file->getClientOriginalExtension();

    $filename = uniqid().'_'.time().'.'.$extension;

    $path = $file->storeAs(
        'documents',
        $filename,
        'public'
    );

    Document::create([
        'document_category_id' => $request->document_category_id,
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $path,
        'file_name' => $file->getClientOriginalName(),
        'file_extension' => $extension,
        'file_size' => $file->getSize(),
        'is_public' => $request->is_public ? 1 : 0,
        'uploaded_by' => Auth::id()
    ]);

    return redirect()
        ->route('documents.index')
        ->with('success','Document uploaded successfully');
}

    /*
    |--------------------------------------------------------------------------
    | Document Detail View
    |--------------------------------------------------------------------------
    */
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    /*
    |--------------------------------------------------------------------------
    | Secure Download Handler
    |--------------------------------------------------------------------------
    */
    public function download(Request $request, Document $document)
{
    if (!$document->is_public && !Auth::check()) {
        abort(403);
    }

    $this->auditService->logDownload(
        $document,
        $request,
        Auth::id()
    );

    $document->increment('download_count');

    return response()->download(
        storage_path('app/public/' . $document->file_path),
        $document->file_name
    );
}


public function secureDownload(Request $request, Document $document)
{
    $filePath = storage_path('app/public/' . $document->file_path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    $document->increment('download_count');

    return response()->download(
        $filePath,
        $document->file_name
    );
}

    

public function generateDownloadLink(Document $document)
{
    if (!$document->is_public && !Auth::check()) {
        abort(403);
    }

    $url = URL::temporarySignedRoute(
        'documents.secure-download',
        now()->addMinutes(30),
        ['document' => $document->id]
    );

    return $url;
}

    /*
    |--------------------------------------------------------------------------
    | Delete Document
    |--------------------------------------------------------------------------
    */
    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return back()->with('success', 'Document deleted successfully');
    }
}