<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ProgramController extends ContentController
{
    protected string $model = Program::class;

    protected string $view = 'admin.website.programs';

    protected string $route = 'admin.programs';

    protected string $singular = 'Program';

    protected array $images = ['image' => 1400];

    protected string $folder = 'programs';

    protected array $richText = ['body'];

    protected array $searchable = ['title', 'summary'];

    protected function rules(?Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('programs', 'slug')->ignore($item?->id)],
            'icon' => ['nullable', 'string', 'max:60', 'regex:/^bi-[a-z0-9-]+$/'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'image' => $this->imageRule(),
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
