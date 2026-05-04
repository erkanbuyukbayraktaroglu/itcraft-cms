@extends('admin.layouts.app')

@section('title', 'Yeni Slider')
@section('topbar_title', 'Yeni Slider Ekle')

@section('content')
<div class="page-title">
    <h1>Yeni Slider Ekle</h1>
    <p>Ana sayfada gösterilecek yeni bir slider/hero kaydı oluşturabilirsin.</p>
</div>

@include('admin.sliders.partials.form', [
    'slider' => null,
    'action' => route('admin.sliders.store'),
    'method' => 'POST',
    'buttonText' => 'Slider Kaydet'
])
@endsection