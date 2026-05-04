@extends('admin.layouts.app')

@section('title', 'Blog Yazıları')
@section('topbar_title', 'Blog Yazıları')

@section('content')
<div class="page-title">
    <h1>Blog Yazıları</h1>
    <p>Blog, haber ve makale içeriklerini buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <a href="{{ route('admin.post-categories.index') }}" class="btn btn-secondary">Kategoriler</a>
    <a href="{{ route('admin.posts.create') }}" class="btn">Yeni Yazı Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Slug</th>
                <th>Öne Çıkan</th>
                <th>Durum</th>
                <th>Yayın Tarihi</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <strong>{{ $post->title }}</strong>
                        @if($post->summary)
                            <br><small style="color:#6b7280;">{{ Str::limit($post->summary, 90) }}</small>
                        @endif
                    </td>
                    <td>{{ $post->category?->title ?? '-' }}</td>
                    <td><code>/blog/{{ $post->slug }}</code></td>
                    <td>
                        @if($post->is_featured)
                            <span class="badge badge-success">Evet</span>
                        @else
                            <span class="badge">Hayır</span>
                        @endif
                    </td>
                    <td>
                        @if($post->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $post->published_at ? $post->published_at->format('d.m.Y H:i') : '-' }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.posts.destroy', $post->id) }}" onsubmit="return confirm('Bu yazıyı silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Henüz blog yazısı eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection