<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Moves document-library files that older versions stored in the public
 * folder (reachable at /storage/documents/...) to private storage, so they
 * can only be downloaded through the permission-checked download link.
 */
class SecureDocumentsCommand extends Command
{
    protected $signature = 'documents:secure {--dry-run : Only list the files that would be moved}';

    protected $description = 'Move document library files from public storage to private storage';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk(Document::DISK);
        $moved = 0;

        Document::query()->whereNotNull('file_path')->orderBy('id')->each(function (Document $document) use ($public, $private, &$moved) {
            $path = $document->file_path;

            if (! $public->exists($path) || $private->exists($path)) {
                return;
            }

            if ($this->option('dry-run')) {
                $this->line("Would move: {$path}");
                $moved++;

                return;
            }

            $private->writeStream($path, $public->readStream($path));

            if ($private->exists($path) && $private->size($path) === $public->size($path)) {
                $public->delete($path);
                $moved++;
                $this->line("Moved: {$path}");
            } else {
                $this->error("Could not copy {$path}; it was left where it is.");
            }
        });

        $this->info($this->option('dry-run') ? "{$moved} file(s) would be moved." : "{$moved} file(s) moved to private storage.");

        return self::SUCCESS;
    }
}
