@extends('frontend.layouts.app')

@section('title', seo_title($service->meta_title ?: $service->title))
@section('meta_description', seo_description($service->meta_description ?: $service->summary))
@section('meta_keywords', $service->meta_keywords ?? '')
@section('canonical', $service->canonical_url ?: url()->current())
@section('robots', ($service->robots_index ?? 'index') . ', ' . ($service->robots_follow ?? 'follow'))

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>{{ $service->title }}</h1>
        @if($service->summary)
            <p>{{ $service->summary }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="content">
            {!! $service->content !!}
        </div>

        @if($otherServices->count())
            <br><br>
            <div class="section-title">
                <h2>Diğer Hizmetler</h2>
            </div>

            <div class="grid grid-3">
                @foreach($otherServices as $otherService)
                    <article class="card">
                        <h3>{{ $otherService->title }}</h3>
                        <p>{{ $otherService->summary }}</p>
                        <a href="{{ route('services.show', $otherService->slug) }}" class="btn">Detay</a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection