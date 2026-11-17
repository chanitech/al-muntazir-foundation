<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** News articles / blog posts. */
class PostController extends ContentController
{
    protected string $model = Post::class;

    protected string $view = 'admin.website.posts';

    protected string $route = 'admin.posts';

    protected string $singular = 'Article';

    protected array $images = ['image' => 1600];

    protected string $folder = 'news';

    protected array $booleans = ['is_published'];

    protected array $richText = ['body'];

    protected array $searchable = ['title', 'excerpt'];

    protected function rules(?Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($item?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'image' => $this->imageRule(),
            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function newItem(): Model
    {
        return new Post(['is_published' => true, 'published_at' => now()]);
    }

    protected function orderForIndex(Builder $query): void
    {
        $query->latestFirst();
    }

    protected function prepare(Request $request, ?Model $item): array
    {
        $data = parent::prepare($request, $item);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = $item?->published_at ?? now();
        }

        if (! $item) {
            $data['user_id'] = $request->user()?->id;
        }

        return $data;
    }

    public function toggle(string $id): RedirectResponse
    {
        $post = $this->find($id);
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => $post->published_at ?? now(),
        ]);

        return back()->with('success', $post->is_published ? 'Article published.' : 'Article moved to drafts.');
    }
}
