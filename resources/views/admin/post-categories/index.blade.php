@extends('admin.layouts.app')

@section('title', 'Blog Kategorileri')
@section('topbar_title', 'Blog Kategorileri')

@section('content')
<div class="page-title">
    <h1>Blog Kategorileri</h1>
    <p>Blog yazılarını sınıflandırmak için kategorileri buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.post-categories.create') }}" class="btn">Yeni Kategori Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Slug</th>
                <th>Yazı Sayısı</th>
                <th>Durum</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        <strong>{{ $category->title }}</strong>
                        @if($category->description)
                            <br><small style="color:#6b7280;">{{ Str::limit($category->description, 90) }}</small>
                        @endif
                    </td>
                    <td><code>/blog/kategori/{{ $category->slug }}</code></td>
                    <td>{{ $category->posts_count }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('blog.category', $category->slug) }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.post-categories.edit', $category->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.post-categories.destroy', $category->id) }}" onsubmit="return confirm('Bu kategoriyi silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Henüz kategori eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $categories->links() }}
    </div>
</div>
@endsection