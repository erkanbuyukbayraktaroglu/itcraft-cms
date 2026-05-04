@extends('admin.layouts.app')

@section('title', 'Hizmet Düzenle')
@section('topbar_title', 'Hizmet Düzenle')

@section('content')
<div class="page-title">
    <h1>Hizmet Düzenle</h1>
    <p><strong>{{ $service->title }}</strong> hizmetini düzenliyorsun.</p>
</div>

@include('admin.services.partials.form', [
    'service' => $service,
    'action' => route('admin.services.update', $service->id),
    'method' => 'PUT',
    'buttonText' => 'Değişiklikleri Kaydet'
])
@endsection