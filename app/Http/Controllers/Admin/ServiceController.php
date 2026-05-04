<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: now();

        Service::query()->create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $service = Service::query()->findOrFail($id);

        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $service = Service::query()->findOrFail($id);

        $validated = $this->validateService($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title'], $service->id);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: $service->published_at;

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $service = Service::query()->findOrFail($id);
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla silindi.');
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'string', 'max:500'],
            'robots_index' => ['required', 'string', 'max:20'],
            'robots_follow' => ['required', 'string', 'max:20'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'schema_type' => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = 'hizmet';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            Service::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
