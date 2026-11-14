<?php
namespace App\Services\Document;

use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class DocumentUploadService
{
    public function __construct(
        protected DocumentStorageService $storageService
    ) {}

    public function upload(array $data, UploadedFile $file, ?int $memberId = null): Document
    {
        return DB::transaction(function () use ($data, $file, $memberId) {

            // Store file
            $storageData = $this->storageService->store($file);

            // Create document record
            return Document::create([
                'document_category_id' => $data['document_category_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,

                'file_path' => $storageData['file_path'],
                'file_name' => $storageData['file_name'],
                'file_extension' => $storageData['file_extension'],
                'file_size' => $storageData['file_size'],

                'is_public' => $data['is_public'] ?? false,
                'uploaded_by' => $memberId
            ]);
        });
    }
}