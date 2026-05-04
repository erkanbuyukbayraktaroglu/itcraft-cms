@extends('frontend.layouts.app')

@section('title', seo_title('Blog'))
@section('meta_description', 'Güncel blog yazıları ve haberler.')

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>Blog</h1>
        <p>Güncel yazılar, duyurular ve bilgilendirici içerikler.</p>
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
                <p>Henüz blog yazısı eklenmemiş.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection