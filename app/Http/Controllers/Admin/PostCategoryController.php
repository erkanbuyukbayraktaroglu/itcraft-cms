<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostCategoryController extends Controller
{
    public function index(): View
    {
        $categories = PostCategory::query()
            ->withCount('posts')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.post-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.post-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title']);
        $validated['is_active'] = $request->has('is_active');

        PostCategory::query()->create($validated);

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Blog kategorisi başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $category = PostCategory::query()->findOrFail($id);

        return view('admin.post-categories.edit', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = PostCategory::query()->findOrFail($id);

        $validated = $this->validateCategory($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title'], $category->id);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Blog kategorisi başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = PostCategory::query()->withCount('posts')->findOrFail($id);

        if ($category->posts_count > 0) {
            return back()->with('error', 'Bu kategoriye bağlı yazılar olduğu için silinemez. Önce yazıları başka kategoriye taşımalısın.');
        }

        $category->delete();

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Blog kategorisi başarıyla silindi.');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = 'kategori';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            PostCategory::query()
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
