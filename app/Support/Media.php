<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Turns a stored image path into a public URL.
 *
 * Supports full URLs, files inside /public (e.g. "images/logo.jpg", which
 * covers images uploaded by older versions of the site) and files on the
 * "public" storage disk (served through the /storage link).
 */
class Media
{
    public static function url(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/') || is_file(public_path($path))) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }
}
