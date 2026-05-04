@extends('frontend.layouts.app')

@section('title', seo_title($post->meta_title ?: $post->title))
@section('meta_description', seo_description($post->meta_description ?: $post->summary))
@section('meta_keywords', $post->meta_keywords ?? '')
@section('canonical', $post->canonical_url ?: url()->current())
@section('robots', ($post->robots_index ?? 'index') . ', ' . ($post->robots_follow ?? 'follow'))

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>{{ $post->title }}</h1>
        @if($post->summary)
            <p>{{ $post->summary }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="content">
            {!! $post->content !!}
        </div>

        @if($latestPosts->count())
            <br><br>
            <div class="section-title">
                <h2>Diğer Yazılar</h2>
            </div>

            <div class="grid grid-3">
                @foreach($latestPosts as $latestPost)
                    <article class="card">
                        <h3>{{ $latestPost->title }}</h3>
                        <p>{{ $latestPost->summary }}</p>
                        <a href="{{ route('blog.show', $latestPost->slug) }}" class="btn">Oku</a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection