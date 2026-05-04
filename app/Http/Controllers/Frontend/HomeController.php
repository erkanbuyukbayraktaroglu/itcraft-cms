<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\Slider;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $featuredServices = Service::query()
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $teamMembers = TeamMember::query()
            ->active()
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $latestPosts = Post::query()
            ->active()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $aboutPage = Page::query()
            ->active()
            ->where('slug', 'hakkimizda')
            ->first();

        return view('frontend.home', compact(
            'sliders',
            'featuredServices',
            'teamMembers',
            'latestPosts',
            'aboutPage'
        ));
    }
}
