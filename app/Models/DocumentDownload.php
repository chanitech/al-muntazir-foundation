<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDownload extends Model
{
    protected $fillable = [
        'document_id',
        'member_id',
        'ip_address',
        'user_agent'
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Member::class);
    }
}