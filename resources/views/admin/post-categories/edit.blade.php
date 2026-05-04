@extends('admin.layouts.app')

@section('title', 'Blog Kategorisi Düzenle')
@section('topbar_title', 'Blog Kategorisi Düzenle')

@section('content')
<div class="page-title">
    <h1>Blog Kategorisi Düzenle</h1>
    <p><strong>{{ $category->title }}</strong> kategorisini düzenliyorsun.</p>
</div>

@include('admin.post-categories.partials.form', [
    'category' => $category,
    'action' => route('admin.post-categories.update', $category->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection