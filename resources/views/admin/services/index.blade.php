@extends('admin.layouts.app')

@section('title', 'Hizmetler')
@section('topbar_title', 'Hizmet Yönetimi')

@section('content')
<div class="page-title">
    <h1>Hizmetler</h1>
    <p>Kurumsal sitenin hizmet, çözüm veya çalışma alanlarını buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.services.create') }}" class="btn">Yeni Hizmet Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Slug</th>
                <th>Öne Çıkan</th>
                <th>Durum</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>
                        <strong>{{ $service->title }}</strong>
                        @if($service->summary)
                            <br><small style="color:#6b7280;">{{ Str::limit($service->summary, 90) }}</small>
                        @endif
                    </td>
                    <td>
                        <code>/hizmetler/{{ $service->slug }}</code>
                    </td>
                    <td>
                        @if($service->is_featured)
                            <span class="badge badge-success">Evet</span>
                        @else
                            <span class="badge">Hayır</span>
                        @endif
                    </td>
                    <td>
                        @if($service->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $service->sort_order }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.services.destroy', $service->id) }}" onsubmit="return confirm('Bu hizmeti silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Henüz hizmet eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $services->links() }}
    </div>
</div>
@endsection