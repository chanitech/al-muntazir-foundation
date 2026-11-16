<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;

class PartnerController extends ContentController
{
    protected string $model = Partner::class;

    protected string $view = 'admin.website.partners';

    protected string $route = 'admin.partners';

    protected string $singular = 'Partner';

    protected array $images = ['logo' => 600];

    protected string $folder = 'partners';

    protected array $searchable = ['name'];

    protected function rules(?Model $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url:http,https', 'max:255'],
            'logo' => $this->imageRule(),
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
