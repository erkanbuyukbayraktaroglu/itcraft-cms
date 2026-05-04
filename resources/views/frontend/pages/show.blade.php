@extends('frontend.layouts.app')

@section('title', seo_title($page->meta_title ?: $page->title))
@section('meta_description', seo_description($page->meta_description ?: $page->summary))
@section('meta_keywords', $page->meta_keywords ?? '')
@section('canonical', $page->canonical_url ?: url()->current())
@section('robots', ($page->robots_index ?? 'index') . ', ' . ($page->robots_follow ?? 'follow'))
@section('og_title', $page->og_title ?: seo_title($page->title))
@section('og_description', $page->og_description ?: seo_description($page->summary))
@if($page->og_image)
    @section('og_image', asset_upload($page->og_image))
@endif

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>{{ $page->title }}</h1>
        @if($page->summary)
            <p>{{ $page->summary }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="content">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection