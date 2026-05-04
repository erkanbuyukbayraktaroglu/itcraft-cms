@extends('admin.layouts.app')

@section('title', 'Sürüm Geçmişi')
@section('topbar_title', 'Sürüm Geçmişi')

@section('content')
<div class="page-title">
    <div>
        <h1>Sürüm Geçmişi</h1>
        <p>İçerikler güncellenmeden önce alınan otomatik eski sürümleri buradan görüntüleyebilirsin.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="version-summary-grid">
    <div class="version-summary-card">
        <span>Toplam Sürüm</span>
        <strong>{{ $summary['total'] }}</strong>
    </div>

    <div class="version-summary-card today">
        <span>Bugün</span>
        <strong>{{ $summary['today'] }}</strong>
    </div>

    <div class="version-summary-card pages">
        <span>Sayfalar</span>
        <strong>{{ $summary['pages'] }}</strong>
    </div>

    <div class="version-summary-card services">
        <span>Hizmetler</span>
        <strong>{{ $summary['services'] }}</strong>
    </div>

    <div class="version-summary-card blogs">
        <span>Blog / Yazılar</span>
        <strong>{{ $summary['blogs'] }}</strong>
    </div>
</div>

<div class="card">
    <form method="get" class="version-filter-form">
        <div>
            <label for="table_name">İçerik Tipi</label>
            <select id="table_name" name="table_name" class="form-control">
                <option value="">Tümü</option>
                @foreach($labels as $table => $label)
                    <option value="{{ $table }}" @selected($filters['table_name'] === $table)>
                        {{ $label }} — {{ $table }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="search">Arama</label>
            <input type="text" id="search" name="search" class="form-control" value="{{ $filters['search'] }}" placeholder="Başlık, slug veya kullanıcı">
        </div>

        <div class="version-filter-actions">
            <button type="submit" class="btn">Filtrele</button>
            <a href="{{ url('/admin/content-versions') }}" class="btn btn-secondary">Temizle</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>İçerik</th>
                    <th>Tip</th>
                    <th>Slug</th>
                    <th>Kullanıcı</th>
                    <th>Tarih</th>
                    <th style="width:210px;">İşlem</th>
                </tr>
            </thead>

            <tbody>
                @forelse($versions as $version)
                    <tr>
                        <td>{{ $version->id }}</td>
                        <td>
                            <strong>{{ $version->record_title ?: 'Başlıksız içerik' }}</strong>
                            <div class="version-muted">Kayıt ID: {{ $version->record_id }}</div>
                        </td>
                        <td>
                            <span class="version-type-badge">
                                {{ $labels[$version->table_name] ?? $version->table_name }}
                            </span>
                        </td>
                        <td>
                            <code>{{ $version->record_slug ?: '-' }}</code>
                        </td>
                        <td>{{ $version->changed_by ?: '-' }}</td>
                        <td>{{ $version->created_at ?: '-' }}</td>
                        <td>
                            <div class="version-row-actions">
                                <a href="{{ route('admin.content-versions.show', $version->id) }}" class="btn btn-secondary btn-sm">
                                    Görüntüle
                                </a>

                                <form method="post" action="{{ route('admin.content-versions.destroy', $version->id) }}" onsubmit="return confirm('Bu sürüm kaydını silmek istiyor musun?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Henüz sürüm kaydı yok. Bir sayfa/hizmet/blog düzenlendikten sonra burada görünecek.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $versions->links() }}
    </div>
</div>

<style>
    .version-summary-grid {
        display:grid;
        grid-template-columns:repeat(5, minmax(0, 1fr));
        gap:14px;
        margin-bottom:18px;
    }

    .version-summary-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:16px;
        box-shadow:0 12px 26px rgba(15,23,42,.06);
    }

    .version-summary-card span {
        display:block;
        font-size:12px;
        font-weight:950;
        color:#64748b;
        letter-spacing:.06em;
        text-transform:uppercase;
        margin-bottom:8px;
    }

    .version-summary-card strong {
        display:block;
        font-size:32px;
        line-height:1;
        letter-spacing:-.05em;
        color:#0f172a;
    }

    .version-summary-card.today {
        background:linear-gradient(135deg, rgba(59,130,246,.14), #fff 62%);
    }

    .version-summary-card.pages {
        background:linear-gradient(135deg, rgba(16,185,129,.14), #fff 62%);
    }

    .version-summary-card.services {
        background:linear-gradient(135deg, rgba(245,158,11,.16), #fff 62%);
    }

    .version-summary-card.blogs {
        background:linear-gradient(135deg, rgba(139,92,246,.14), #fff 62%);
    }

    .version-filter-form {
        display:grid;
        grid-template-columns:1fr 1fr auto;
        gap:14px;
        align-items:end;
    }

    .version-filter-actions,
    .version-row-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        align-items:center;
    }

    .version-row-actions form {
        margin:0;
    }

    .version-muted {
        color:#64748b;
        font-size:12px;
        margin-top:4px;
    }

    .version-type-badge {
        display:inline-flex;
        padding:5px 9px;
        border-radius:999px;
        background:#f1f5f9;
        color:#334155;
        font-size:12px;
        font-weight:950;
    }

    .btn-danger {
        background:#ef4444 !important;
        border-color:#ef4444 !important;
        color:#fff !important;
    }

    .btn-sm {
        min-height:34px;
        padding:7px 11px;
        font-size:13px;
    }

    @media (max-width: 1180px) {
        .version-summary-grid {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .version-filter-form {
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 640px) {
        .version-summary-grid {
            grid-template-columns:1fr;
        }
    }
</style>
@endsection