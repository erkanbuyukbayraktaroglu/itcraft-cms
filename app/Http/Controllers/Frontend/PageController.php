<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($page->template === 'contact') {
            return app(ContactController::class)->index();
        }

        return view('frontend.pages.show', compact('page'));
    }
}
