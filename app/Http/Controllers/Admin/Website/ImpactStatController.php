<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\ImpactStat;
use Illuminate\Database\Eloquent\Model;

/** Numbers shown in the "Our Impact" band on the homepage. */
class ImpactStatController extends ContentController
{
    protected string $model = ImpactStat::class;

    protected string $view = 'admin.website.stats';

    protected string $route = 'admin.stats';

    protected string $singular = 'Statistic';

    protected array $searchable = ['label'];

    protected function rules(?Model $item): array
    {
        return [
            'label' => ['required', 'string', 'max:100'],
            'value' => ['required', 'integer', 'min:0', 'max:999999999'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'icon' => ['nullable', 'string', 'max:60', 'regex:/^bi-[a-z0-9-]+$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
