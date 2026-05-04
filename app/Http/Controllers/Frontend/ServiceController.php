<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::query()
            ->active()
            ->orderBy('sort_order')
            ->paginate(12);

        return view('frontend.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $otherServices = Service::query()
            ->active()
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('frontend.services.show', compact('service', 'otherServices'));
    }
}
