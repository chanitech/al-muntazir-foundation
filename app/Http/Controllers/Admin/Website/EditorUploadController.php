<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\ImageUploader;
use App\Support\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Receives images inserted into the rich-text editor (articles, programs). */
class EditorUploadController extends Controller
{
    public function __invoke(Request $request, ImageUploader $uploader): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $path = $uploader->store($request->file('image'), 'editor', 1400);

        return response()->json(['url' => Media::url($path)]);
    }
}
