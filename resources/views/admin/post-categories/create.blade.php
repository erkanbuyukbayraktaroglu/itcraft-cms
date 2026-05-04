@extends('admin.layouts.app')

@section('title', 'Yeni Blog Kategorisi')
@section('topbar_title', 'Yeni Blog Kategorisi')

@section('content')
<div class="page-title">
    <h1>Yeni Blog Kategorisi</h1>
    <p>Blog yazıları için yeni bir kategori ekleyebilirsin.</p>
</div>

@include('admin.post-categories.partials.form', [
    'category' => null,
    'action' => route('admin.post-categories.store'),
    'method' => 'POST',
    'buttonText' => 'Kategoriyi Kaydet'
])
@endsection