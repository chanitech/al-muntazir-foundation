<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GalleryItemController extends ContentController
{
    protected string $model = GalleryItem::class;

    protected string $view = 'admin.website.gallery';

    protected string $route = 'admin.gallery';

    protected string $singular = 'Photo';

    protected array $images = ['image' => 1600];

    protected string $folder = 'gallery';

    protected array $searchable = ['title', 'category'];

    protected int $perPage = 24;

    protected function rules(?Model $item): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => $this->imageRule(),
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /** Several photos can be uploaded at once; each becomes a gallery item. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => $this->imageRule(required: true),
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $order = (int) GalleryItem::max('sort_order');

        foreach ($request->file('images') as $file) {
            GalleryItem::create([
                'title' => $validated['title'] ?? null,
                'category' => $validated['category'] ?? null,
                'image' => $this->uploader->store($file, $this->folder, $this->images['image']),
                'sort_order' => ++$order,
                'is_active' => $request->boolean('is_active', true),
            ]);
        }

        $count = count($request->file('images'));

        return redirect()->route($this->route.'.index')
            ->with('success', $count === 1 ? 'Photo added to the gallery.' : $count.' photos added to the gallery.');
    }
}
