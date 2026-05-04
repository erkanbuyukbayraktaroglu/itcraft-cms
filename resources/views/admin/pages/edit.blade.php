@extends('admin.layouts.app')

@section('title', 'Sayfa Düzenle')
@section('topbar_title', 'Sayfa Düzenle')

@section('content')
<div class="page-title">
    <h1>Sayfa Düzenle</h1>
    <p><strong>{{ $page->title }}</strong> sayfasını düzenliyorsun.</p>
</div>

@include('admin.pages.partials.form', [
    'page' => $page,
    'action' => route('admin.pages.update', $page->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection