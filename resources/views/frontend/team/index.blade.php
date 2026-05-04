@extends('frontend.layouts.app')

@section('title', seo_title('Ekibimiz'))
@section('meta_description', 'Profesyonel ekibimizi ve uzman kadromuzu inceleyin.')

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>Ekibimiz</h1>
        <p>Alanında deneyimli profesyonel kadromuzu inceleyin.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @forelse($teamMembers as $teamMember)
                <article class="card">
                    @if($teamMember->image)
                        <img src="{{ asset_upload($teamMember->image) }}" alt="{{ $teamMember->name }}" style="width:100%; border-radius:16px; margin-bottom:16px;">
                    @endif

                    <h3>{{ $teamMember->name }}</h3>

                    @if($teamMember->title)
                        <p style="font-weight:700; color:var(--color-accent); margin-bottom:8px;">{{ $teamMember->title }}</p>
                    @endif

                    @if($teamMember->summary)
                        <p>{{ $teamMember->summary }}</p>
                    @endif

                    <a href="{{ route('team.show', $teamMember->slug) }}" class="btn">Profili İncele</a>
                </article>
            @empty
                <p>Henüz ekip üyesi eklenmemiş.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $teamMembers->links() }}
        </div>
    </div>
</section>
@endsection