@extends('admin.layouts.app')

@section('title', 'Yeni Admin Kullanıcısı')
@section('topbar_title', 'Yeni Admin Kullanıcısı')

@section('content')
<div class="page-title">
    <div>
        <h1>Yeni Admin Kullanıcısı</h1>
        <p>Panel erişimi için yeni kullanıcı oluştur.</p>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Geri Dön</a>
</div>

@include('admin.users.partials.form', [
    'action' => route('admin.users.store'),
    'method' => 'POST',
    'user' => null,
    'columns' => $columns,
])
@endsection