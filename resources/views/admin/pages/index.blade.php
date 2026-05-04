@extends('admin.layouts.app')

@section('title', 'Sayfalar')
@section('topbar_title', 'Sayfa Yönetimi')

@section('content')
<div class="page-title">
    <h1>Sayfalar</h1>
    <p>Kurumsal sitenin tüm içerik sayfalarını buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.pages.create') }}" class="btn">Yeni Sayfa Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Slug</th>
                <th>Template</th>
                <th>Menü</th>
                <th>Durum</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>
                        <strong>{{ $page->title }}</strong>
                        @if($page->summary)
                            <br><small style="color:#6b7280;">{{ Str::limit($page->summary, 80) }}</small>
                        @endif
                    </td>
                    <td>
                        <code>/{{ $page->slug }}</code>
                    </td>
                    <td>{{ $page->template }}</td>
                    <td>
                        @if($page->show_in_menu)
                            <span class="badge badge-success">Göster</span>
                        @else
                            <span class="badge">Gizli</span>
                        @endif
                    </td>
                    <td>
                        @if($page->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $page->sort_order }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ url('/' . $page->slug) }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.pages.destroy', $page->id) }}" onsubmit="return confirm('Bu sayfayı silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Henüz sayfa eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $pages->links() }}
    </div>
</div>
@endsection