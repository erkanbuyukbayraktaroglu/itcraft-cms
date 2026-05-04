@extends('frontend.layouts.app')

@section('title', seo_title('Hizmetler'))
@section('meta_description', 'Hizmetlerimizi inceleyin.')

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>Hizmetler</h1>
        <p>Kurumsal ihtiyaçlara yönelik hizmet ve çözüm alanlarımızı inceleyin.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @forelse($services as $service)
                <article class="card">
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->summary }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="btn">Detay</a>
                </article>
            @empty
                <p>Henüz hizmet eklenmemiş.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $services->links() }}
        </div>
    </div>
</section>
@endsection