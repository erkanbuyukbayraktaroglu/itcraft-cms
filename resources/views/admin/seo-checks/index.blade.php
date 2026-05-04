@extends('admin.layouts.app')

@section('title', 'SEO Kontrol')
@section('topbar_title', 'SEO Kontrol')

@section('content')
<div class="page-title">
    <div>
        <h1>SEO Kontrol</h1>
        <p>Sayfa, hizmet ve blog içeriklerinin temel SEO durumunu kontrol edebilirsin.</p>
    </div>
</div>

<div class="seo-summary-grid">
    <div class="seo-summary-card">
        <span>Toplam İçerik</span>
        <strong>{{ $summary['total'] }}</strong>
    </div>

    <div class="seo-summary-card good">
        <span>İyi</span>
        <strong>{{ $summary['good'] }}</strong>
    </div>

    <div class="seo-summary-card warning">
        <span>Geliştirilmeli</span>
        <strong>{{ $summary['warning'] }}</strong>
    </div>

    <div class="seo-summary-card danger">
        <span>Kritik</span>
        <strong>{{ $summary['danger'] }}</strong>
    </div>

    <div class="seo-summary-card average">
        <span>Ortalama Puan</span>
        <strong>{{ $summary['average'] }}</strong>
    </div>
</div>

<div class="card">
    <form method="get" class="seo-filter-form">
        <div>
            <label for="type">İçerik Tipi</label>
            <select id="type" name="type" class="form-control">
                <option value="">Tümü</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" @selected($filters['type'] === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status">SEO Durumu</label>
            <select id="status" name="status" class="form-control">
                <option value="">Tümü</option>
                <option value="good" @selected($filters['status'] === 'good')>İyi</option>
                <option value="warning" @selected($filters['status'] === 'warning')>Geliştirilmeli</option>
                <option value="danger" @selected($filters['status'] === 'danger')>Kritik</option>
            </select>
        </div>

        <div class="seo-filter-actions">
            <button type="submit" class="btn">Filtrele</button>
            <a href="{{ url('/admin/seo-checks') }}" class="btn btn-secondary">Temizle</a>
        </div>
    </form>
</div>

<div class="seo-check-list">
    @forelse($items as $item)
        <div class="card seo-check-card status-{{ $item['status'] }}">
            <div class="seo-check-main">
                <div class="seo-score-circle">
                    <strong>{{ $item['score'] }}</strong>
                    <span>SEO</span>
                </div>

                <div class="seo-check-content">
                    <div class="seo-check-title-row">
                        <div>
                            <span class="seo-type-badge">{{ $item['type_label'] }}</span>
                            <h2>{{ $item['title'] }}</h2>
                        </div>

                        <span class="seo-status-badge {{ $item['status'] }}">
                            {{ $item['status_label'] }}
                        </span>
                    </div>

                    <div class="seo-meta-grid">
                        <div>
                            <strong>Slug</strong>
                            <span>{{ $item['slug'] ?: '-' }}</span>
                        </div>

                        <div>
                            <strong>Meta Title</strong>
                            <span>{{ $item['meta_title_length'] }} karakter</span>
                        </div>

                        <div>
                            <strong>Meta Description</strong>
                            <span>{{ $item['meta_description_length'] }} karakter</span>
                        </div>

                        <div>
                            <strong>İçerik</strong>
                            <span>{{ $item['word_count'] }} kelime</span>
                        </div>
                    </div>

                    <div class="seo-detail-grid">
                        <div>
                            <h3>Eksikler / Uyarılar</h3>

                            @if(count($item['issues']) > 0)
                                <ul class="seo-issue-list">
                                    @foreach($item['issues'] as $issue)
                                        <li>{{ $issue }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="seo-empty-good">Kritik eksik görünmüyor.</p>
                            @endif
                        </div>

                        <div>
                            <h3>Başarılı Kontroller</h3>

                            @if(count($item['success']) > 0)
                                <ul class="seo-success-list">
                                    @foreach($item['success'] as $success)
                                        <li>{{ $success }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="seo-empty-muted">Henüz başarılı kontrol yok.</p>
                            @endif
                        </div>
                    </div>

                    <div class="seo-actions">
                        @if($item['edit_url'])
                            <a href="{{ $item['edit_url'] }}" class="btn btn-secondary">Düzenle</a>
                        @endif

                        @if($item['public_url'])
                            <a href="{{ $item['public_url'] }}" target="_blank" class="btn btn-secondary">Önizle</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            Kontrol edilecek içerik bulunamadı.
        </div>
    @endforelse
</div>

<style>
    .seo-summary-grid {
        display:grid;
        grid-template-columns:repeat(5, minmax(0, 1fr));
        gap:14px;
        margin-bottom:18px;
    }

    .seo-summary-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:16px;
        box-shadow:0 12px 26px rgba(15,23,42,.06);
    }

    .seo-summary-card span {
        display:block;
        font-size:12px;
        font-weight:950;
        color:#64748b;
        letter-spacing:.06em;
        text-transform:uppercase;
        margin-bottom:8px;
    }

    .seo-summary-card strong {
        display:block;
        font-size:32px;
        line-height:1;
        letter-spacing:-.05em;
        color:#0f172a;
    }

    .seo-summary-card.good {
        background:linear-gradient(135deg, rgba(16,185,129,.14), #fff 62%);
    }

    .seo-summary-card.warning {
        background:linear-gradient(135deg, rgba(245,158,11,.16), #fff 62%);
    }

    .seo-summary-card.danger {
        background:linear-gradient(135deg, rgba(239,68,68,.14), #fff 62%);
    }

    .seo-summary-card.average {
        background:linear-gradient(135deg, rgba(59,130,246,.14), #fff 62%);
    }

    .seo-filter-form {
        display:grid;
        grid-template-columns:1fr 1fr auto;
        gap:14px;
        align-items:end;
    }

    .seo-filter-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .seo-check-list {
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .seo-check-card {
        border-left:5px solid #cbd5e1;
    }

    .seo-check-card.status-good {
        border-left-color:#10b981;
    }

    .seo-check-card.status-warning {
        border-left-color:#f59e0b;
    }

    .seo-check-card.status-danger {
        border-left-color:#ef4444;
    }

    .seo-check-main {
        display:grid;
        grid-template-columns:90px minmax(0, 1fr);
        gap:18px;
        align-items:flex-start;
    }

    .seo-score-circle {
        width:78px;
        height:78px;
        border-radius:999px;
        background:#f8fafc;
        border:1px solid #e5e7eb;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        box-shadow:inset 0 0 0 6px #f1f5f9;
    }

    .seo-score-circle strong {
        display:block;
        font-size:25px;
        line-height:1;
        letter-spacing:-.05em;
        color:#0f172a;
    }

    .seo-score-circle span {
        display:block;
        font-size:10px;
        font-weight:950;
        color:#64748b;
        margin-top:4px;
    }

    .seo-check-title-row {
        display:flex;
        justify-content:space-between;
        gap:14px;
        align-items:flex-start;
        margin-bottom:14px;
    }

    .seo-check-title-row h2 {
        margin:6px 0 0;
        font-size:22px;
        line-height:1.2;
        letter-spacing:-.04em;
    }

    .seo-type-badge {
        display:inline-flex;
        padding:5px 9px;
        border-radius:999px;
        background:#f1f5f9;
        color:#334155;
        font-size:12px;
        font-weight:950;
    }

    .seo-status-badge {
        display:inline-flex;
        padding:7px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:950;
        white-space:nowrap;
    }

    .seo-status-badge.good {
        background:#dcfce7;
        color:#166534;
    }

    .seo-status-badge.warning {
        background:#fef3c7;
        color:#92400e;
    }

    .seo-status-badge.danger {
        background:#fee2e2;
        color:#991b1b;
    }

    .seo-meta-grid {
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:10px;
        margin-bottom:16px;
    }

    .seo-meta-grid div {
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:11px;
        min-width:0;
    }

    .seo-meta-grid strong {
        display:block;
        font-size:11px;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.06em;
        margin-bottom:5px;
    }

    .seo-meta-grid span {
        display:block;
        color:#0f172a;
        font-weight:850;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .seo-detail-grid {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
        margin-bottom:16px;
    }

    .seo-detail-grid h3 {
        margin:0 0 8px;
        font-size:15px;
    }

    .seo-issue-list,
    .seo-success-list {
        margin:0;
        padding-left:18px;
        line-height:1.7;
    }

    .seo-issue-list li {
        color:#991b1b;
    }

    .seo-success-list li {
        color:#166534;
    }

    .seo-empty-good {
        color:#166534;
        margin:0;
        font-weight:800;
    }

    .seo-empty-muted {
        color:#64748b;
        margin:0;
    }

    .seo-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    @media (max-width: 1180px) {
        .seo-summary-grid {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .seo-meta-grid {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .seo-detail-grid {
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 700px) {
        .seo-summary-grid,
        .seo-filter-form,
        .seo-meta-grid {
            grid-template-columns:1fr;
        }

        .seo-check-main {
            grid-template-columns:1fr;
        }

        .seo-check-title-row {
            flex-direction:column;
        }
    }
</style>
@endsection