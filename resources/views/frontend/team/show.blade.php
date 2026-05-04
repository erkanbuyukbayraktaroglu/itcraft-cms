@extends('frontend.layouts.app')

@section('title', seo_title($teamMember->meta_title ?: $teamMember->name))
@section('meta_description', seo_description($teamMember->meta_description ?: $teamMember->summary))
@section('meta_keywords', $teamMember->meta_keywords ?? '')
@section('canonical', $teamMember->canonical_url ?: url()->current())
@section('robots', ($teamMember->robots_index ?? 'index') . ', ' . ($teamMember->robots_follow ?? 'follow'))
@section('og_title', $teamMember->og_title ?: seo_title($teamMember->name))
@section('og_description', $teamMember->og_description ?: seo_description($teamMember->summary))
@if($teamMember->og_image)
    @section('og_image', asset_upload($teamMember->og_image))
@endif

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>{{ $teamMember->name }}</h1>
        @if($teamMember->title)
            <p>{{ $teamMember->title }}</p>
        @elseif($teamMember->summary)
            <p>{{ $teamMember->summary }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2">
            <div>
                @if($teamMember->image)
                    <img src="{{ asset_upload($teamMember->image) }}" alt="{{ $teamMember->name }}" style="width:100%; border-radius:22px;">
                @else
                    <div class="card">
                        <h2>{{ $teamMember->name }}</h2>
                        <p>Profil görseli henüz eklenmemiş.</p>
                    </div>
                @endif
            </div>

            <div class="content">
                @if($teamMember->summary)
                    <p style="font-size:19px; color:#374151;">{{ $teamMember->summary }}</p>
                @endif

                @if($teamMember->bio)
                    {!! $teamMember->bio !!}
                @endif

                <div class="card" style="margin-top:24px;">
                    <h3>İletişim</h3>

                    @if($teamMember->email)
                        <p><strong>E-posta:</strong> <a href="mailto:{{ $teamMember->email }}">{{ $teamMember->email }}</a></p>
                    @endif

                    @if($teamMember->phone)
                        <p><strong>Telefon:</strong> <a href="tel:{{ clean_phone_link($teamMember->phone) }}">{{ $teamMember->phone }}</a></p>
                    @endif

                    @if($teamMember->linkedin_url)
                        <p><strong>LinkedIn:</strong> <a href="{{ $teamMember->linkedin_url }}" target="_blank" rel="noopener">Profili Görüntüle</a></p>
                    @endif
                </div>
            </div>
        </div>

        @if($otherTeamMembers->count())
            <br><br>
            <div class="section-title">
                <h2>Diğer Ekip Üyeleri</h2>
            </div>

            <div class="grid grid-3">
                @foreach($otherTeamMembers as $otherTeamMember)
                    <article class="card">
                        <h3>{{ $otherTeamMember->name }}</h3>
                        @if($otherTeamMember->title)
                            <p>{{ $otherTeamMember->title }}</p>
                        @endif
                        <a href="{{ route('team.show', $otherTeamMember->slug) }}" class="btn">Profili İncele</a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection