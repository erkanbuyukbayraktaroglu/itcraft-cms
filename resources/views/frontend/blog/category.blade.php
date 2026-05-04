@extends('frontend.layouts.app')

@section('title', seo_title($category->meta_title ?: $category->title))
@section('meta_description', seo_description($category->meta_description ?: $category->description))

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>{{ $category->title }}</h1>
        @if($category->description)
            <p>{{ $category->description }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @forelse($posts as $post)
                <article class="card">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->summary }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn">Oku</a>
                </article>
            @empty
                <p>Bu kategoride henüz yazı bulunmuyor.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection