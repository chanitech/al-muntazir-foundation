<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Models\DocumentDownload;
use Illuminate\Http\Request;

class DocumentAuditService
{
    public function logDownload(Document $document, Request $request, ?int $memberId = null): void
    {
        DocumentDownload::create([
            'document_id' => $document->id,
            'member_id' => $memberId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }
}