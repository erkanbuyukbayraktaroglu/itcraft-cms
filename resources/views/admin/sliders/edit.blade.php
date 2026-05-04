@extends('admin.layouts.app')

@section('title', 'Slider Düzenle')
@section('topbar_title', 'Slider Düzenle')

@section('content')
<div class="page-title">
    <h1>Slider Düzenle</h1>
    <p><strong>{{ $slider->title }}</strong> slider kaydını düzenliyorsun.</p>
</div>

@include('admin.sliders.partials.form', [
    'slider' => $slider,
    'action' => route('admin.sliders.update', $slider->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection