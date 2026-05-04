@extends('admin.layouts.app')

@section('title', 'Sürüm Detayı')
@section('topbar_title', 'Sürüm Detayı')

@section('content')
<div class="page-title">
    <div>
        <h1>Sürüm Detayı</h1>
        <p>Seçilen eski içerik sürümünü inceleyebilir ve gerekirse geri yükleyebilirsin.</p>
    </div>

    <a href="{{ url('/admin/content-versions') }}" class="btn btn-secondary">Listeye Dön</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="version-detail-grid">
    <div class="card">
        <h2>Sürüm Bilgisi</h2>

        <div class="version-info-list">
            <div>
                <strong>Tip</strong>
                <span>{{ $label }} — {{ $version->table_name }}</span>
            </div>

            <div>
                <strong>Kayıt ID</strong>
                <span>{{ $version->record_id }}</span>
            </div>

            <div>
                <strong>Başlık</strong>
                <span>{{ $version->record_title ?: '-' }}</span>
            </div>

            <div>
                <strong>Slug</strong>
                <span>{{ $version->record_slug ?: '-' }}</span>
            </div>

            <div>
                <strong>Kullanıcı</strong>
                <span>{{ $version->changed_by ?: '-' }}</span>
            </div>

            <div>
                <strong>IP</strong>
                <span>{{ $version->ip_address ?: '-' }}</span>
            </div>

            <div>
                <strong>Tarih</strong>
                <span>{{ $version->created_at ?: '-' }}</span>
            </div>
        </div>

        <div class="version-restore-box">
            @if($currentExists)
                <form method="post" action="{{ route('admin.content-versions.restore', $version->id) }}" onsubmit="return confirm('Bu eski sürümü mevcut içeriğe geri yüklemek istiyor musun? Mevcut içerik bu sürümle değiştirilecek.');">
                    @csrf
                    <button type="submit" class="btn btn-danger">Bu Sürümü Geri Yükle</button>
                </form>
            @else
                <div class="alert alert-warning">
                    Mevcut kayıt bulunamadığı için geri yükleme yapılamaz.
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <h2>Alanlar</h2>

        <div class="version-data-list">
            @foreach($data as $key => $value)
                <div class="version-data-item">
                    <strong>{{ $key }}</strong>

                    @if(is_null($value))
                        <span class="version-null">NULL</span>
                    @elseif(is_scalar($value))
                        <pre>{{ $value }}</pre>
                    @else
                        <pre>{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .version-detail-grid {
        display:grid;
        grid-template-columns:360px minmax(0, 1fr);
        gap:18px;
        align-items:start;
    }

    .version-detail-grid h2 {
        margin:0 0 16px;
        font-size:22px;
        letter-spacing:-.04em;
    }

    .version-info-list {
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .version-info-list div {
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:11px;
    }

    .version-info-list strong {
        display:block;
        font-size:11px;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.06em;
        margin-bottom:5px;
    }

    .version-info-list span {
        display:block;
        color:#0f172a;
        font-weight:850;
        word-break:break-word;
    }

    .version-restore-box {
        margin-top:18px;
        padding-top:18px;
        border-top:1px solid #e5e7eb;
    }

    .version-restore-box form {
        margin:0;
    }

    .btn-danger {
        background:#ef4444 !important;
        border-color:#ef4444 !important;
        color:#fff !important;
    }

    .version-data-list {
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .version-data-item {
        border:1px solid #e5e7eb;
        border-radius:16px;
        overflow:hidden;
        background:#fff;
    }

    .version-data-item strong {
        display:block;
        padding:10px 12px;
        background:#f8fafc;
        border-bottom:1px solid #e5e7eb;
        color:#334155;
        font-size:13px;
    }

    .version-data-item pre {
        margin:0;
        padding:13px;
        white-space:pre-wrap;
        word-break:break-word;
        color:#0f172a;
        font-size:13px;
        line-height:1.55;
        max-height:360px;
        overflow:auto;
    }

    .version-null {
        display:block;
        padding:13px;
        color:#94a3b8;
        font-weight:900;
    }

    @media (max-width: 960px) {
        .version-detail-grid {
            grid-template-columns:1fr;
        }
    }
</style>
@endsection