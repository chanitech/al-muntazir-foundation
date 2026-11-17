<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\Slider;
use App\Rules\SafeLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SliderController extends ContentController
{
    protected string $model = Slider::class;

    protected string $view = 'admin.website.sliders';

    protected string $route = 'admin.sliders';

    protected string $singular = 'Slide';

    protected array $images = ['image' => 1920];

    protected string $folder = 'sliders';

    protected array $searchable = ['title', 'subtitle'];

    protected function rules(?Model $item): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image' => $this->imageRule(required: ! $item?->image),
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_link' => ['nullable', 'string', 'max:255', new SafeLink],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepare(Request $request, ?Model $item): array
    {
        $data = parent::prepare($request, $item);

        // Older sliders tables may not allow an empty (NULL) heading.
        $data['title'] = $data['title'] ?? '';

        return $data;
    }
}
