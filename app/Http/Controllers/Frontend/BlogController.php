<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->active()
            ->with('category')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        $categories = PostCategory::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('frontend.blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::query()
            ->active()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $latestPosts = Post::query()
            ->active()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('frontend.blog.show', compact('post', 'latestPosts'));
    }

    public function category(string $slug)
    {
        $category = PostCategory::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Post::query()
            ->active()
            ->with('category')
            ->where('post_category_id', $category->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        $categories = PostCategory::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('frontend.blog.category', compact('category', 'posts', 'categories'));
    }
}
