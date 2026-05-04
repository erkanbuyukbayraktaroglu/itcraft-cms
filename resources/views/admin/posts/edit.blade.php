@extends('admin.layouts.app')

@section('title', 'Blog Yazısı Düzenle')
@section('topbar_title', 'Blog Yazısı Düzenle')

@section('content')
<div class="page-title">
    <h1>Blog Yazısı Düzenle</h1>
    <p><strong>{{ $post->title }}</strong> yazısını düzenliyorsun.</p>
</div>

@include('admin.posts.partials.form', [
    'post' => $post,
    'categories' => $categories,
    'action' => route('admin.posts.update', $post->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection