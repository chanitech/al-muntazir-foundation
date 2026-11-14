<?php

namespace App\Http\Controllers\Admin\Website;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;

class TeamMemberController extends ContentController
{
    protected string $model = TeamMember::class;

    protected string $view = 'admin.website.team';

    protected string $route = 'admin.team';

    protected string $singular = 'Team member';

    protected array $images = ['photo' => 800];

    protected string $folder = 'team';

    protected array $searchable = ['name', 'position'];

    protected function rules(?Model $item): array
    {
        $social = ['nullable', 'url:http,https', 'max:255'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => $this->imageRule(),
            'facebook_url' => $social,
            'instagram_url' => $social,
            'x_url' => $social,
            'linkedin_url' => $social,
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
