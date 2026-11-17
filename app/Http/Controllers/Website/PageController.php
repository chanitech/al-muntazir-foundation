<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('website.about', [
            'team' => TeamMember::active()->ordered()->get(),
            'stats' => ImpactStat::active()->ordered()->take(4)->get(),
            'partners' => Partner::active()->ordered()->get(),
        ]);
    }

    public function gallery(Request $request): View
    {
        $category = $request->query('category');

        return view('website.gallery', [
            'photos' => GalleryItem::active()
                ->when($category, fn ($q) => $q->where('category', $category))
                ->ordered()
                ->paginate(24)
                ->withQueryString(),
            'categories' => GalleryItem::active()->whereNotNull('category')->where('category', '!=', '')
                ->distinct()->orderBy('category')->pluck('category'),
            'category' => $category,
        ]);
    }
}
