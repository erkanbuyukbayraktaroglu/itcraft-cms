@extends('admin.layouts.app')

@section('title', 'Slider')
@section('topbar_title', 'Slider Yönetimi')

@section('content')
<div class="page-title">
    <h1>Slider</h1>
    <p>Ana sayfa hero / slider alanlarını buradan yönetebilirsin.</p>
</div>

<div style="display:flex; justify-content:space-between; gap:14px; align-items:center; margin-bottom:18px;">
    <div></div>
    <a href="{{ route('admin.sliders.create') }}" class="btn">Yeni Slider Ekle</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Buton</th>
                <th>Durum</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($sliders as $slider)
                <tr>
                    <td>{{ $slider->id }}</td>
                    <td>
                        <strong>{{ $slider->title }}</strong>
                        @if($slider->subtitle)
                            <br><small style="color:#6b7280;">{{ $slider->subtitle }}</small>
                        @endif
                        @if($slider->description)
                            <br><small style="color:#6b7280;">{{ Str::limit($slider->description, 90) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($slider->button_text)
                            <span class="badge">{{ $slider->button_text }}</span>
                            @if($slider->button_url)
                                <br><small style="color:#6b7280;">{{ $slider->button_url }}</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($slider->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $slider->sort_order }}</td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">Görüntüle</a>
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn">Düzenle</a>

                            <form method="post" action="{{ route('admin.sliders.destroy', $slider->id) }}" onsubmit="return confirm('Bu slider kaydını silmek istediğine emin misin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Henüz slider eklenmemiş.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $sliders->links() }}
    </div>
</div>
@endsection