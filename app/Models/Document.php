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

    /** Disk for uploaded files: private, served only through the download route. */
    public const DISK = 'local';

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

    /** Link to download the file (checks the user's permission). */
    public function getFileUrlAttribute(): string
    {
        return route('documents.secure-download', $this);
    }

    /**
     * Full path of the stored file, or null if it is missing. Looks on the
     * private disk first, then on the public disk used by older uploads.
     */
    public function absolutePath(): ?string
    {
        foreach ([self::DISK, 'public'] as $disk) {
            if ($this->file_path && Storage::disk($disk)->exists($this->file_path)) {
                return Storage::disk($disk)->path($this->file_path);
            }
        }

        return null;
    }
}