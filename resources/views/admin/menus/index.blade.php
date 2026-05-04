@extends('admin.layouts.app')

@section('title', 'Menü Yönetimi')
@section('topbar_title', 'Menü Yönetimi')

@section('content')
<div class="page-title">
    <h1>Menü Yönetimi</h1>
    <p>Header ve footer menülerini buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.menus.create') }}" class="btn">Yeni Menü Ekle</a>
</div>

<div class="grid" style="grid-template-columns:repeat(2, minmax(0, 1fr));">
    @forelse($menus as $menu)
        <div class="card">
            <div style="display:flex; justify-content:space-between; gap:14px; align-items:flex-start;">
                <div>
                    <h2 style="margin:0 0 6px;">{{ $menu->name }}</h2>
                    <p style="margin:0; color:#6b7280;">Slug: <code>{{ $menu->slug }}</code></p>
                </div>

                @if($menu->is_active)
                    <span class="badge badge-success">Aktif</span>
                @else
                    <span class="badge badge-danger">Pasif</span>
                @endif
            </div>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin:18px 0;">

            @if($menu->items->count())
                <div style="display:grid; gap:10px;">
                    @foreach($menu->items as $item)
                        <div style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:12px;">
                            <strong>{{ $item->title }}</strong>
                            <br>
                            <small style="color:#6b7280;">{{ $item->url }}</small>

                            @if($item->children->count())
                                <div style="margin-top:8px; padding-left:14px;">
                                    @foreach($item->children as $child)
                                        <div style="font-size:13px; color:#6b7280;">↳ {{ $child->title }} — {{ $child->url }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6b7280;">Bu menüde henüz eleman yok.</p>
            @endif

            <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:18px;">
                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn">Düzenle</a>

                <form method="post" action="{{ route('admin.menus.destroy', $menu->id) }}" onsubmit="return confirm('Bu menüyü silmek istediğine emin misin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            Henüz menü eklenmemiş.
        </div>
    @endforelse
</div>
@endsection