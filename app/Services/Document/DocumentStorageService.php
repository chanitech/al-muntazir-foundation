<?php
namespace App\Services\Document;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    public function store(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();

        $filename = Str::uuid() . '.' . $extension;

        $path = $file->storeAs(
            'documents',
            $filename,
            'public'
        );

        return [
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_extension' => $extension,
            'file_size' => $file->getSize()
        ];
    }
}