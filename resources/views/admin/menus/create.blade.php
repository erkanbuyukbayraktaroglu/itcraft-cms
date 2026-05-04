@extends('admin.layouts.app')

@section('title', 'Yeni Menü')
@section('topbar_title', 'Yeni Menü')

@section('content')
<div class="page-title">
    <h1>Yeni Menü Ekle</h1>
    <p>Header, footer veya özel alanlar için yeni menü oluşturabilirsin.</p>
</div>

<form method="post" action="{{ route('admin.menus.store') }}">
    @csrf

    @if($errors->any())
        <div class="alert-error">Lütfen form alanlarını kontrol edin.</div>
    @endif

    <div class="card">
        <label for="name">Menü Adı</label>
        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>

        <label for="slug">Slug</label>
        <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="header, footer, mobile-menu">

        <small style="display:block; margin-top:-8px; margin-bottom:14px; color:#6b7280;">
            Header için <code>header</code>, footer için <code>footer</code> slug kullanılır.
        </small>

        <div class="checkbox-row">
            <label>
                <input type="checkbox" name="is_active" value="1" checked>
                Aktif
            </label>
        </div>
    </div>

    <br>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <button type="submit" class="btn">Menüyü Kaydet</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Geri Dön</a>
    </div>
</form>
@endsection