<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialController extends ContentController
{
    protected string $model = Testimonial::class;

    protected string $view = 'admin.website.testimonials';

    protected string $route = 'admin.testimonials';

    protected string $singular = 'Testimonial';

    protected array $images = ['photo' => 400];

    protected string $folder = 'testimonials';

    protected array $searchable = ['name', 'quote'];

    protected function rules(?Model $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'photo' => $this->imageRule(),
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
