<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePage($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title']);
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: now();

        Page::query()->create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $page = Page::query()->findOrFail($id);

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $page = Page::query()->findOrFail($id);

        $validated = $this->validatePage($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title'], $page->id);
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: $page->published_at;

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $page = Page::query()->findOrFail($id);

        $protectedSlugs = [
            'hakkimizda',
            'iletisim',
        ];

        if (in_array($page->slug, $protectedSlugs, true)) {
            return back()->with('error', 'Bu sistem sayfası silinemez. İstersen pasif hale getirebilirsin.');
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla silindi.');
    }

    private function validatePage(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'template' => ['required', 'string', 'max:100'],
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
            $slug = 'sayfa';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            Page::query()
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
