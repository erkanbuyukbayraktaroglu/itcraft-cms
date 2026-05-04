@extends('admin.layouts.app')

@section('title', 'Ekip Üyeleri')
@section('topbar_title', 'Ekip Üyeleri')

@section('content')
<div class="page-title">
    <h1>Ekip Üyeleri</h1>
    <p>Kurumsal sitenin ekip, uzman, avukat veya danışman profillerini buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.team-members.create') }}" class="btn">Yeni Ekip Üyesi Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>Ünvan</th>
                <th>Slug</th>
                <th>Durum</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($teamMembers as $teamMember)
                <tr>
                    <td>{{ $teamMember->id }}</td>
                    <td>
                        <strong>{{ $teamMember->name }}</strong>
                        @if($teamMember->summary)
                            <br><small style="color:#6b7280;">{{ Str::limit($teamMember->summary, 90) }}</small>
                        @endif
                    </td>
                    <td>{{ $teamMember->title ?: '-' }}</td>
                    <td><code>/ekibimiz/{{ $teamMember->slug }}</code></td>
                    <td>
                        @if($teamMember->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $teamMember->sort_order }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('team.show', $teamMember->slug) }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.team-members.edit', $teamMember->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.team-members.destroy', $teamMember->id) }}" onsubmit="return confirm('Bu ekip üyesini silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Henüz ekip üyesi eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $teamMembers->links() }}
    </div>
</div>
@endsection