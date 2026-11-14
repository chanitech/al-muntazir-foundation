<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));

        return view('website.news.index', [
            'posts' => Post::published()
                ->when($term !== '', fn ($q) => $q->where(fn ($q) => $q
                    ->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%")))
                ->latestFirst()
                ->paginate(9)
                ->withQueryString(),
            'search' => $term,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isLive(), 404);

        return view('website.news.show', [
            'post' => $post,
            'recent' => Post::published()->latestFirst()->whereKeyNot($post->id)->take(3)->get(),
        ]);
    }
}
