<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with('category')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = $this->categories();

        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: now();

        Post::query()->create($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog yazısı başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $post = Post::query()->findOrFail($id);
        $categories = $this->categories();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $post = Post::query()->findOrFail($id);

        $validated = $this->validatePost($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['title'], $post->id);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['published_at'] = $request->input('published_at') ?: $post->published_at;

        $post->update($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog yazısı başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = Post::query()->findOrFail($id);
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog yazısı başarıyla silindi.');
    }

    private function categories()
    {
        return PostCategory::query()
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'post_category_id' => ['nullable', 'integer', 'exists:post_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'author_name' => ['nullable', 'string', 'max:255'],
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
            $slug = 'blog-yazisi';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            Post::query()
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
