@extends('admin.layouts.app')

@section('title', 'Yeni Sayfa')
@section('topbar_title', 'Yeni Sayfa Ekle')

@section('content')
<div class="page-title">
    <h1>Yeni Sayfa Ekle</h1>
    <p>Sınırsız sayfa yapısına yeni bir içerik ekleyebilirsin.</p>
</div>

@include('admin.pages.partials.form', [
    'page' => null,
    'action' => route('admin.pages.store'),
    'method' => 'POST',
    'buttonText' => 'Sayfayı Kaydet'
])
@endsection