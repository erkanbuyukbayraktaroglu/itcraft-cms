@extends('admin.layouts.app')

@section('title', 'Menü Düzenle')
@section('topbar_title', 'Menü Düzenle')

@section('content')
<div class="page-title">
    <h1>{{ $menu->name }}</h1>
    <p>Bu menüye ait linkleri buradan ekleyebilir ve düzenleyebilirsin.</p>
</div>

<div class="grid" style="grid-template-columns: 1fr 1.4fr;">
    <div>
        <form method="post" action="{{ route('admin.menus.update', $menu->id) }}">
            @csrf
            @method('PUT')

            <div class="card" style="margin-bottom:22px;">
                <h2 style="margin-top:0;">Menü Bilgileri</h2>

                <label for="name">Menü Adı</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" required>

                <label for="slug">Slug</label>
                <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $menu->slug) }}">

                <small style="display:block; margin-top:-8px; margin-bottom:14px; color:#6b7280;">
                    Frontend header menüsü için slug <code>header</code>, footer menüsü için <code>footer</code> olmalıdır.
                </small>

                <div class="checkbox-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                        Aktif
                    </label>
                </div>
            </div>

            <button type="submit" class="btn">Menüyü Güncelle</button>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Geri Dön</a>
        </form>

        <br>

        <form method="post" action="{{ route('admin.menus.items.store', $menu->id) }}">
            @csrf

            <div class="card">
                <h2 style="margin-top:0;">Yeni Menü Elemanı</h2>

                <label for="title">Başlık</label>
                <input class="form-control" type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Anasayfa" required>

                <label for="url">URL</label>
                <input class="form-control" type="text" id="url" name="url" value="{{ old('url') }}" placeholder="/ veya /hakkimizda" required>

                <label for="parent_id">Üst Menü</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="">Ana Menü Elemanı</option>
                    @foreach($allItems as $parentItem)
                        <option value="{{ $parentItem->id }}">{{ $parentItem->title }}</option>
                    @endforeach
                </select>

                <div class="form-grid form-grid-2">
                    <div>
                        <label for="target">Açılış Şekli</label>
                        <select class="form-control" id="target" name="target">
                            <option value="_self">Aynı Sekme</option>
                            <option value="_blank">Yeni Sekme</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort_order">Sıralama</label>
                        <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                    </div>
                </div>

                <div class="checkbox-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" checked>
                        Aktif
                    </label>
                </div>

                <br>

                <button type="submit" class="btn">Menü Elemanı Ekle</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">Menü Elemanları</h2>

        @if($menu->items->count())
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Başlık</th>
                        <th>URL</th>
                        <th>Durum</th>
                        <th>Sıra</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menu->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->title }}</strong>

                                @if($item->children->count())
                                    <div style="margin-top:8px; padding-left:12px;">
                                        @foreach($item->children->sortBy('sort_order') as $child)
                                            <div style="font-size:13px; color:#6b7280;">
                                                ↳ {{ $child->title }} — {{ $child->url }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td><code>{{ $item->url }}</code></td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('admin.menus.items.edit', $item->id) }}" class="btn">Düzenle</a>

                                    <form method="post" action="{{ route('admin.menus.items.destroy', $item->id) }}" onsubmit="return confirm('Bu menü elemanını silmek istediğine emin misin?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Sil</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @foreach($item->children->sortBy('sort_order') as $child)
                            <tr>
                                <td>↳ {{ $child->title }}</td>
                                <td><code>{{ $child->url }}</code></td>
                                <td>
                                    @if($child->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Pasif</span>
                                    @endif
                                </td>
                                <td>{{ $child->sort_order }}</td>
                                <td>
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <a href="{{ route('admin.menus.items.edit', $child->id) }}" class="btn">Düzenle</a>

                                        <form method="post" action="{{ route('admin.menus.items.destroy', $child->id) }}" onsubmit="return confirm('Bu menü elemanını silmek istediğine emin misin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Sil</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color:#6b7280;">Bu menüde henüz eleman yok.</p>
        @endif
    </div>
</div>
@endsection