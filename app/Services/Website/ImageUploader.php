<?php

namespace App\Services\Website;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Stores website images on the "public" disk and scales down very large
 * photos (e.g. straight from a phone camera) so pages stay fast.
 */
class ImageUploader
{
    private const DISK = 'public';

    public function store(UploadedFile $file, string $folder, int $maxWidth = 1600): string
    {
        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        $path = 'website/'.trim($folder, '/').'/'.Str::uuid().'.'.$extension;

        $resized = $this->resize($file->getRealPath(), $extension, $maxWidth);

        if ($resized !== null) {
            Storage::disk(self::DISK)->put($path, $resized);
        } else {
            Storage::disk(self::DISK)->putFileAs(dirname($path), $file, basename($path));
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        // Only remove files this uploader created; never touch other files.
        if ($path && Str::startsWith($path, 'website/')) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /** Returns re-encoded image bytes, or null to keep the original file. */
    private function resize(string $source, string $extension, int $maxWidth): ?string
    {
        if (! extension_loaded('gd') || ! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return null;
        }

        try {
            [$width, $height] = getimagesize($source) ?: [0, 0];

            if ($width <= $maxWidth || $width === 0) {
                return null;
            }

            $image = match ($extension) {
                'png' => imagecreatefrompng($source),
                'webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($source) : false,
                default => imagecreatefromjpeg($source),
            };

            if (! $image) {
                return null;
            }

            $newHeight = (int) round($height * ($maxWidth / $width));
            $canvas = imagecreatetruecolor($maxWidth, $newHeight);

            if ($extension !== 'jpg' && $extension !== 'jpeg') {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
            }

            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);

            ob_start();
            match ($extension) {
                'png' => imagepng($canvas, null, 8),
                'webp' => imagewebp($canvas, null, 82),
                default => imagejpeg($canvas, null, 82),
            };
            $bytes = ob_get_clean();

            imagedestroy($image);
            imagedestroy($canvas);

            return $bytes ?: null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
