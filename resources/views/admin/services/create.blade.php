@extends('admin.layouts.app')

@section('title', 'Yeni Hizmet')
@section('topbar_title', 'Yeni Hizmet Ekle')

@section('content')
<div class="page-title">
    <h1>Yeni Hizmet Ekle</h1>
    <p>Yeni hizmet, çözüm veya çalışma alanı ekleyebilirsin.</p>
</div>

@include('admin.services.partials.form', [
    'service' => null,
    'action' => route('admin.services.store'),
    'method' => 'POST',
    'buttonText' => 'Hizmeti Kaydet'
])
@endsection