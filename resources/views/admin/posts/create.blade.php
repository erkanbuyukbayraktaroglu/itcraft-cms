@extends('admin.layouts.app')

@section('title', 'Yeni Blog Yazısı')
@section('topbar_title', 'Yeni Blog Yazısı')

@section('content')
<div class="page-title">
    <h1>Yeni Blog Yazısı</h1>
    <p>Yeni blog, haber veya makale içeriği ekleyebilirsin.</p>
</div>

@include('admin.posts.partials.form', [
    'post' => null,
    'categories' => $categories,
    'action' => route('admin.posts.store'),
    'method' => 'POST',
    'buttonText' => 'Yazıyı Kaydet'
])
@endsection