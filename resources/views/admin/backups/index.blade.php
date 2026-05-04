@extends('admin.layouts.app')

@section('title', 'Yedekleme')
@section('topbar_title', 'Yedekleme')

@section('content')
<div class="page-title">
    <div>
        <h1>Yedekleme</h1>
        <p>Veritabanı ve uploads klasörü yedeklerini buradan oluşturabilir ve indirebilirsin.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="backup-summary-grid">
    <div class="backup-summary-card">
        <span>Toplam Yedek</span>
        <strong>{{ $stats['total'] }}</strong>
    </div>

    <div class="backup-summary-card database">
        <span>Veritabanı</span>
        <strong>{{ $stats['database'] }}</strong>
    </div>

    <div class="backup-summary-card uploads">
        <span>Uploads</span>
        <strong>{{ $stats['uploads'] }}</strong>
    </div>

    <div class="backup-summary-card size">
        <span>Toplam Boyut</span>
        <strong>{{ $stats['total_size'] }}</strong>
    </div>

    <div class="backup-summary-card last">
        <span>Son Yedek</span>
        <strong>{{ $stats['last_backup'] }}</strong>
    </div>
</div>

<div class="backup-action-grid">
    <div class="card backup-action-card">
        <div>
            <div class="backup-action-icon">🗄️</div>
            <h2>Veritabanı Yedeği</h2>
            <p>Sayfalar, bloglar, ayarlar, iletişim mesajları ve diğer veritabanı kayıtları SQL dosyası olarak yedeklenir.</p>
        </div>

        <form method="post" action="{{ route('admin.backups.database') }}" onsubmit="return confirm('Veritabanı yedeği oluşturmak istiyor musun?');">
            @csrf
            <button type="submit" class="btn">Veritabanı Yedeği Al</button>
        </form>
    </div>

    <div class="card backup-action-card">
        <div>
            <div class="backup-action-icon">🖼️</div>
            <h2>Uploads Yedeği</h2>
            <p>public/uploads klasöründeki görseller ve yüklenen dosyalar ZIP dosyası olarak yedeklenir.</p>

            @if(!$zipAvailable)
                <div class="alert alert-warning" style="margin-top:12px;">
                    ZipArchive PHP eklentisi aktif değil. Uploads ZIP yedeği alınamaz.
                </div>
            @endif
        </div>

        <form method="post" action="{{ route('admin.backups.uploads') }}" onsubmit="return confirm('Uploads klasörü yedeği oluşturmak istiyor musun?');">
            @csrf
            <button type="submit" class="btn" @disabled(!$zipAvailable)>Uploads Yedeği Al</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="backup-table-header">
        <div>
            <h2>Yedek Dosyaları</h2>
            <p>Yedekler public altında değil, storage/app/backups klasöründe tutulur.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Dosya</th>
                    <th>Tip</th>
                    <th>Boyut</th>
                    <th>Oluşturulma</th>
                    <th style="width:220px;">İşlem</th>
                </tr>
            </thead>

            <tbody>
                @forelse($backups as $backup)
                    <tr>
                        <td>
                            <strong>{{ $backup['name'] }}</strong>
                            <div class="backup-file-extension">{{ $backup['extension'] }}</div>
                        </td>
                        <td>{{ $backup['type'] }}</td>
                        <td>{{ $backup['size'] }}</td>
                        <td>{{ $backup['created_at'] }}</td>
                        <td>
                            <div class="backup-row-actions">
                                <a href="{{ route('admin.backups.download', $backup['name']) }}" class="btn btn-secondary btn-sm">
                                    İndir
                                </a>

                                <form method="post" action="{{ route('admin.backups.destroy', $backup['name']) }}" onsubmit="return confirm('Bu yedek dosyasını silmek istiyor musun?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Henüz yedek dosyası bulunmuyor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .backup-summary-grid {
        display:grid;
        grid-template-columns:repeat(5, minmax(0, 1fr));
        gap:14px;
        margin-bottom:18px;
    }

    .backup-summary-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:16px;
        box-shadow:0 12px 26px rgba(15,23,42,.06);
    }

    .backup-summary-card span {
        display:block;
        font-size:12px;
        font-weight:950;
        color:#64748b;
        letter-spacing:.06em;
        text-transform:uppercase;
        margin-bottom:8px;
    }

    .backup-summary-card strong {
        display:block;
        font-size:26px;
        line-height:1.1;
        letter-spacing:-.045em;
        color:#0f172a;
        word-break:break-word;
    }

    .backup-summary-card.database {
        background:linear-gradient(135deg, rgba(59,130,246,.14), #fff 62%);
    }

    .backup-summary-card.uploads {
        background:linear-gradient(135deg, rgba(16,185,129,.14), #fff 62%);
    }

    .backup-summary-card.size {
        background:linear-gradient(135deg, rgba(245,158,11,.16), #fff 62%);
    }

    .backup-summary-card.last {
        background:linear-gradient(135deg, rgba(139,92,246,.14), #fff 62%);
    }

    .backup-action-grid {
        display:grid;
        grid-template-columns:repeat(2, minmax(0, 1fr));
        gap:18px;
        margin-bottom:18px;
    }

    .backup-action-card {
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        gap:22px;
        min-height:250px;
    }

    .backup-action-icon {
        width:58px;
        height:58px;
        border-radius:20px;
        background:#f1f5f9;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:28px;
        margin-bottom:16px;
    }

    .backup-action-card h2 {
        margin:0 0 8px;
        font-size:22px;
        letter-spacing:-.04em;
    }

    .backup-action-card p {
        margin:0;
        color:#64748b;
        line-height:1.6;
    }

    .backup-table-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:12px;
        margin-bottom:16px;
    }

    .backup-table-header h2 {
        margin:0 0 5px;
        font-size:22px;
        letter-spacing:-.04em;
    }

    .backup-table-header p {
        margin:0;
        color:#64748b;
    }

    .backup-file-extension {
        display:inline-flex;
        margin-top:6px;
        padding:4px 8px;
        border-radius:999px;
        background:#f1f5f9;
        color:#334155;
        font-size:11px;
        font-weight:950;
    }

    .backup-row-actions {
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .backup-row-actions form {
        margin:0;
    }

    .btn-danger {
        background:#ef4444 !important;
        border-color:#ef4444 !important;
        color:#ffffff !important;
    }

    .btn-danger:hover {
        background:#dc2626 !important;
        border-color:#dc2626 !important;
        color:#ffffff !important;
    }

    .btn-sm {
        min-height:34px;
        padding:7px 11px;
        font-size:13px;
    }

    @media (max-width: 1180px) {
        .backup-summary-grid {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .backup-action-grid {
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 640px) {
        .backup-summary-grid {
            grid-template-columns:1fr;
        }
    }
</style>
@endsection