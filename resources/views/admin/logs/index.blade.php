@extends('admin.layouts.app')

@section('title', 'Sistem Logları')
@section('topbar_title', 'Sistem Logları')

@section('content')
<div class="page-title">
    <div>
        <h1>Sistem Logları</h1>
        <p>Laravel log dosyalarını güvenli şekilde görüntüleyebilirsin. Bu ekran log silmez veya değiştirmez.</p>
    </div>
</div>

<div class="log-summary-grid">
    <div class="log-summary-card danger">
        <span>ERROR</span>
        <strong>{{ $summary['error'] }}</strong>
    </div>

    <div class="log-summary-card warning">
        <span>WARNING</span>
        <strong>{{ $summary['warning'] }}</strong>
    </div>

    <div class="log-summary-card info">
        <span>INFO</span>
        <strong>{{ $summary['info'] }}</strong>
    </div>

    <div class="log-summary-card debug">
        <span>DEBUG</span>
        <strong>{{ $summary['debug'] }}</strong>
    </div>

    <div class="log-summary-card critical">
        <span>CRITICAL</span>
        <strong>{{ $summary['critical'] }}</strong>
    </div>
</div>

<div class="card">
    <form method="get" class="log-filter-form">
        <div>
            <label for="file">Log Dosyası</label>
            <select id="file" name="file" class="form-control">
                @forelse($logFiles as $fileName => $file)
                    <option value="{{ $fileName }}" @selected($selectedFile === $fileName)>
                        {{ $fileName }} — {{ $file['size'] }} — {{ $file['modified'] }}
                    </option>
                @empty
                    <option value="">Log dosyası bulunamadı</option>
                @endforelse
            </select>
        </div>

        <div>
            <label for="lines">Satır Sayısı</label>
            <select id="lines" name="lines" class="form-control">
                @foreach([100, 200, 500, 1000, 2000, 5000] as $count)
                    <option value="{{ $count }}" @selected((int) $lineCount === $count)>
                        Son {{ $count }} satır
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="level">Seviye</label>
            <select id="level" name="level" class="form-control">
                <option value="" @selected($level === '')>Tümü</option>
                @foreach(['ERROR', 'WARNING', 'INFO', 'DEBUG', 'CRITICAL', 'ALERT', 'EMERGENCY'] as $item)
                    <option value="{{ $item }}" @selected($level === $item)>{{ $item }}</option>
                @endforeach
            </select>
        </div>

        <div class="log-filter-actions">
            <button type="submit" class="btn">Görüntüle</button>
            <a href="{{ url('/admin/logs') }}" class="btn btn-secondary">Temizle</a>
        </div>
    </form>
</div>

<div class="card log-info-card">
    <div>
        <strong>Seçili Dosya:</strong>
        <code>{{ $selectedFile ?: '-' }}</code>
    </div>

    <div>
        <strong>Boyut:</strong>
        <span>{{ $fileSize ?: '-' }}</span>
    </div>

    <div>
        <strong>Son Değişiklik:</strong>
        <span>{{ $lastModified ?: '-' }}</span>
    </div>
</div>

<div class="card">
    <div class="log-toolbar">
        <div>
            <strong>Log İçeriği</strong>
            <span>Güvenlik için bazı hassas bilgiler maskelenir.</span>
        </div>

        <button type="button" class="btn btn-secondary btn-sm" onclick="copyLogContent()">Kopyala</button>
    </div>

    @if(!$selectedFile)
        <div class="alert alert-warning">Henüz görüntülenecek log dosyası bulunamadı.</div>
    @elseif(trim($content) === '')
        <div class="alert alert-info">Seçili filtrelere göre log içeriği bulunamadı.</div>
    @else
        <pre id="logContent" class="log-content">{{ $content }}</pre>
    @endif
</div>

<style>
    .log-summary-grid {
        display:grid;
        grid-template-columns:repeat(5, minmax(0, 1fr));
        gap:14px;
        margin-bottom:18px;
    }

    .log-summary-card {
        border-radius:18px;
        padding:16px;
        background:#fff;
        border:1px solid #e5e7eb;
        box-shadow:0 12px 26px rgba(15,23,42,.06);
    }

    .log-summary-card span {
        display:block;
        font-size:12px;
        font-weight:950;
        letter-spacing:.06em;
        color:#64748b;
        margin-bottom:8px;
    }

    .log-summary-card strong {
        display:block;
        font-size:30px;
        line-height:1;
        letter-spacing:-.05em;
        color:#0f172a;
    }

    .log-summary-card.danger {
        background:linear-gradient(135deg, rgba(239,68,68,.14), #fff 62%);
    }

    .log-summary-card.warning {
        background:linear-gradient(135deg, rgba(245,158,11,.16), #fff 62%);
    }

    .log-summary-card.info {
        background:linear-gradient(135deg, rgba(59,130,246,.14), #fff 62%);
    }

    .log-summary-card.debug {
        background:linear-gradient(135deg, rgba(100,116,139,.14), #fff 62%);
    }

    .log-summary-card.critical {
        background:linear-gradient(135deg, rgba(127,29,29,.16), #fff 62%);
    }

    .log-filter-form {
        display:grid;
        grid-template-columns:2fr 1fr 1fr auto;
        gap:14px;
        align-items:end;
    }

    .log-filter-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .log-info-card {
        display:flex;
        gap:18px;
        flex-wrap:wrap;
        align-items:center;
        color:#334155;
    }

    .log-toolbar {
        display:flex;
        justify-content:space-between;
        gap:12px;
        align-items:center;
        margin-bottom:14px;
    }

    .log-toolbar span {
        display:block;
        color:#64748b;
        font-size:13px;
        margin-top:3px;
    }

    .log-content {
        background:#0f172a;
        color:#e5e7eb;
        border-radius:16px;
        padding:18px;
        overflow:auto;
        max-height:680px;
        font-size:13px;
        line-height:1.65;
        white-space:pre-wrap;
        word-break:break-word;
        border:1px solid rgba(255,255,255,.08);
    }

    .log-content::selection {
        background:#f59e0b;
        color:#111827;
    }

    @media (max-width: 1180px) {
        .log-summary-grid {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .log-filter-form {
            grid-template-columns:1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .log-summary-grid,
        .log-filter-form {
            grid-template-columns:1fr;
        }

        .log-toolbar {
            display:block;
        }

        .log-toolbar button {
            margin-top:10px;
        }
    }
</style>

<script>
    function copyLogContent() {
        var el = document.getElementById('logContent');

        if (!el) {
            alert('Kopyalanacak log içeriği bulunamadı.');
            return;
        }

        navigator.clipboard.writeText(el.innerText).then(function () {
            alert('Log içeriği kopyalandı.');
        }).catch(function () {
            alert('Kopyalama başarısız oldu. Tarayıcı izinlerini kontrol et.');
        });
    }
</script>
@endsection