<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;



class Document extends Model
{
    protected $fillable = [
        'document_category_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_extension',
        'file_size',
        'is_public',
        'download_count',
        'uploaded_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'download_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DocumentDownload::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Storage Helper
    |--------------------------------------------------------------------------
    */

    public function getFileUrlAttribute(): string
    {
        return \Storage::url($this->file_path);
    }
}