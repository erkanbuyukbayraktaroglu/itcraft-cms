@extends('admin.layouts.app')

@section('title', 'Menü Elemanı Düzenle')
@section('topbar_title', 'Menü Elemanı Düzenle')

@section('content')
<div class="page-title">
    <h1>Menü Elemanı Düzenle</h1>
    <p><strong>{{ $item->title }}</strong> menü elemanını düzenliyorsun.</p>
</div>

<form method="post" action="{{ route('admin.menus.items.update', $item->id) }}">
    @csrf
    @method('PUT')

    <div class="card">
        <label for="title">Başlık</label>
        <input class="form-control" type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required>

        <label for="url">URL</label>
        <input class="form-control" type="text" id="url" name="url" value="{{ old('url', $item->url) }}" required>

        <label for="parent_id">Üst Menü</label>
        <select class="form-control" id="parent_id" name="parent_id">
            <option value="">Ana Menü Elemanı</option>
            @foreach($allItems as $parentItem)
                <option value="{{ $parentItem->id }}" {{ (string) old('parent_id', $item->parent_id) === (string) $parentItem->id ? 'selected' : '' }}>
                    {{ $parentItem->title }}
                </option>
            @endforeach
        </select>

        <div class="form-grid form-grid-2">
            <div>
                <label for="target">Açılış Şekli</label>
                <select class="form-control" id="target" name="target">
                    <option value="_self" {{ old('target', $item->target) === '_self' ? 'selected' : '' }}>Aynı Sekme</option>
                    <option value="_blank" {{ old('target', $item->target) === '_blank' ? 'selected' : '' }}>Yeni Sekme</option>
                </select>
            </div>

            <div>
                <label for="sort_order">Sıralama</label>
                <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}">
            </div>
        </div>

        <div class="checkbox-row">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                Aktif
            </label>
        </div>
    </div>

    <br>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <button type="submit" class="btn">Değişiklikleri Kaydet</button>
        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-secondary">Geri Dön</a>
    </div>
</form>
@endsection