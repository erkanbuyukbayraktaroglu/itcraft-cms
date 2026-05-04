@extends('admin.layouts.app')

@section('title', 'Admin Kullanıcısı Düzenle')
@section('topbar_title', 'Admin Kullanıcısı Düzenle')

@section('content')
<div class="page-title">
    <div>
        <h1>Admin Kullanıcısı Düzenle</h1>
        <p>Kullanıcı bilgilerini ve şifresini güncelleyebilirsin.</p>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Geri Dön</a>
</div>

@include('admin.users.partials.form', [
    'action' => route('admin.users.update', $user->id),
    'method' => 'PUT',
    'user' => $user,
    'columns' => $columns,
])
@endsection