<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('website.home', [
            'slides' => Slider::active()->ordered()->get(),
            'programs' => Program::active()->ordered()->take(6)->get(),
            'stats' => ImpactStat::active()->ordered()->take(4)->get(),
            'events' => Event::publicListing()->upcoming()->take(3)->get(),
            'posts' => Post::published()->latestFirst()->take(3)->get(),
            'testimonials' => Testimonial::active()->ordered()->take(6)->get(),
            'photos' => GalleryItem::active()->ordered()->take(9)->get(),
            'partners' => Partner::active()->ordered()->get(),
        ]);
    }
}
