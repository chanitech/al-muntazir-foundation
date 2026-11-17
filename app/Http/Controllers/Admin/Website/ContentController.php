<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\ImageUploader;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Shared create / list / edit / delete logic for the Website section of the
 * admin panel. Each module (sliders, programs, news...) only declares its
 * model, views, validation rules and image fields.
 */
abstract class ContentController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    /** Blade folder, e.g. "admin.website.sliders". */
    protected string $view;

    /** Route name prefix, e.g. "admin.sliders". */
    protected string $route;

    /** Human name of one item, e.g. "Slide". */
    protected string $singular;

    /** Image columns and the maximum width (px) they are resized to. */
    protected array $images = [];

    /** Folder (inside storage/app/public/website) for uploaded images. */
    protected string $folder = 'misc';

    /** Checkbox columns. */
    protected array $booleans = ['is_active'];

    /** Rich-text columns that are cleaned with the HTML sanitizer. */
    protected array $richText = [];

    /** Columns searched by the list's search box. */
    protected array $searchable = ['title'];

    protected int $perPage = 15;

    /** Replaced or removed image files, deleted once the record is saved. */
    private array $staleFiles = [];

    public function __construct(protected ImageUploader $uploader) {}

    abstract protected function rules(?Model $item): array;

    public function index(Request $request): View
    {
        $query = $this->model::query();
        $term = trim((string) $request->query('q', ''));

        if ($term !== '') {
            $query->where(function (Builder $q) use ($term) {
                foreach ($this->searchable as $column) {
                    $q->orWhere($column, 'like', '%'.$term.'%');
                }
            });
        }

        $this->orderForIndex($query);

        return view($this->view.'.index', [
            'items' => $query->paginate($this->perPage)->withQueryString(),
            'search' => $term,
        ]);
    }

    public function create(): View
    {
        return view($this->view.'.form', ['item' => $this->newItem()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->model::create($this->prepare($request, null));
        $this->deleteStaleFiles();

        return redirect()->route($this->route.'.index')->with('success', $this->singular.' created successfully.');
    }

    public function edit(string $id): View
    {
        return view($this->view.'.form', ['item' => $this->find($id)]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $item = $this->find($id);
        $item->update($this->prepare($request, $item));
        $this->deleteStaleFiles();

        return redirect()->route($this->route.'.index')->with('success', $this->singular.' updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $item = $this->find($id);

        foreach (array_keys($this->images) as $column) {
            $this->uploader->delete($item->{$column});
        }

        $item->delete();

        return redirect()->route($this->route.'.index')->with('success', $this->singular.' deleted.');
    }

    /** Show / hide an item on the website with one click from the list. */
    public function toggle(string $id): RedirectResponse
    {
        $item = $this->find($id);
        $item->update(['is_active' => ! $item->is_active]);

        return back()->with('success', $this->singular.($item->is_active ? ' is now visible on the website.' : ' is now hidden from the website.'));
    }

    protected function find(string $id): Model
    {
        return $this->model::query()->findOrFail($id);
    }

    protected function newItem(): Model
    {
        return new $this->model([
            'is_active' => true,
            'sort_order' => (int) $this->model::query()->max('sort_order') + 1,
        ]);
    }

    protected function orderForIndex(Builder $query): void
    {
        $query->ordered();
    }

    /** Validates the request and handles checkboxes, rich text and images. */
    protected function prepare(Request $request, ?Model $item): array
    {
        $data = $request->validate($this->rules($item));

        foreach ($this->booleans as $column) {
            $data[$column] = $request->boolean($column);
        }

        // An empty "Display order" field means "first".
        if (array_key_exists('sort_order', $data) && $data['sort_order'] === null) {
            $data['sort_order'] = 0;
        }

        foreach ($this->richText as $column) {
            if (array_key_exists($column, $data)) {
                $data[$column] = HtmlSanitizer::clean($data[$column]);
            }
        }

        foreach ($this->images as $column => $maxWidth) {
            unset($data[$column], $data['remove_'.$column]);

            if ($request->hasFile($column)) {
                $data[$column] = $this->uploader->store($request->file($column), $this->folder, $maxWidth);
                $this->staleFiles[] = $item?->{$column};
            } elseif ($item && $request->boolean('remove_'.$column)) {
                $this->staleFiles[] = $item->{$column};
                $data[$column] = null;
            }
        }

        return $data;
    }

    protected function deleteStaleFiles(): void
    {
        foreach (array_filter($this->staleFiles) as $path) {
            $this->uploader->delete($path);
        }

        $this->staleFiles = [];
    }

    /** Standard validation for an optional uploaded image. */
    protected function imageRule(bool $required = false): array
    {
        return [$required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'];
    }
}
