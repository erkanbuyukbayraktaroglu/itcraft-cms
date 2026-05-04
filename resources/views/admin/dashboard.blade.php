@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('topbar_title', 'Dashboard')

@section('content')
<div class="page-title">
    <h1>Dashboard</h1>
    <p>Kurumsal CMS yönetim panelinin genel durumunu buradan takip edebilirsin.</p>
</div>

<div class="dashboard-stats">
    @foreach($stats as $stat)
        <a href="{{ $stat['route'] }}" class="dashboard-stat-card {{ !empty($stat['highlight']) ? 'highlight' : '' }}">
            <div>
                <span>{{ $stat['title'] }}</span>
                <strong>{{ $stat['value'] }}</strong>
                <small>{{ $stat['description'] }}</small>
            </div>
        </a>
    @endforeach
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header-row">
            <div>
                <h2>Son Gelen İletişim Mesajları</h2>
                <p>Web sitesindeki iletişim formundan gelen son 5 mesaj.</p>
            </div>

            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">Tüm Mesajlar</a>
        </div>

        @if(isset($recentMessages) && $recentMessages->count())
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Durum</th>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Konu</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentMessages as $message)
                        <tr>
                            <td>
                                @if((int) ($message->is_read ?? 0) === 1)
                                    <span class="badge">Okundu</span>
                                @else
                                    <span class="badge badge-success">Yeni</span>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $message->name ?? '-' }}</strong>
                            </td>

                            <td>
                                @if(!empty($message->email))
                                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $message->subject ?? 'Konu belirtilmemiş' }}</td>
                            <td>{{ $message->created_at ?? '-' }}</td>

                            <td>
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn">Görüntüle</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <strong>Henüz mesaj yok.</strong>
                <p>İletişim formundan mesaj geldiğinde burada listelenecek.</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2 style="margin-top:0;">Hızlı İşlemler</h2>
        <p style="color:#6b7280; margin-top:-6px;">Sık kullanılan yönetim ekranlarına hızlı erişim.</p>

        <div class="quick-link-list">
            @foreach($quickLinks as $link)
                <a href="{{ $link['url'] }}" class="quick-link-item">
                    <strong>{{ $link['title'] }}</strong>
                    <span>{{ $link['description'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>
    .dashboard-stats {
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:18px;
        margin-bottom:22px;
    }

    .dashboard-stat-card {
        display:block;
        background:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:20px;
        text-decoration:none;
        color:#111827;
        box-shadow:0 10px 26px rgba(15, 23, 42, .045);
        transition:.18s ease;
    }

    .dashboard-stat-card:hover {
        transform:translateY(-2px);
        box-shadow:0 16px 32px rgba(15, 23, 42, .08);
    }

    .dashboard-stat-card span {
        display:block;
        color:#6b7280;
        font-size:14px;
        margin-bottom:8px;
    }

    .dashboard-stat-card strong {
        display:block;
        font-size:34px;
        line-height:1;
        color:#111827;
        margin-bottom:10px;
    }

    .dashboard-stat-card small {
        display:block;
        color:#6b7280;
        line-height:1.5;
    }

    .dashboard-stat-card.highlight {
        border-color:#bbf7d0;
        background:linear-gradient(135deg, #f0fdf4, #ffffff);
    }

    .dashboard-stat-card.highlight strong {
        color:#15803d;
    }

    .dashboard-grid {
        display:grid;
        grid-template-columns:2fr 1fr;
        gap:22px;
        align-items:start;
    }

    .card-header-row {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        margin-bottom:18px;
    }

    .card-header-row h2 {
        margin:0 0 6px;
    }

    .card-header-row p {
        margin:0;
        color:#6b7280;
    }

    .quick-link-list {
        display:grid;
        gap:12px;
        margin-top:16px;
    }

    .quick-link-item {
        display:block;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:14px 16px;
        text-decoration:none;
        color:#111827;
        background:#f9fafb;
        transition:.18s ease;
    }

    .quick-link-item:hover {
        background:#ffffff;
        transform:translateY(-1px);
        box-shadow:0 10px 24px rgba(15, 23, 42, .06);
    }

    .quick-link-item strong {
        display:block;
        margin-bottom:5px;
    }

    .quick-link-item span {
        display:block;
        color:#6b7280;
        font-size:14px;
        line-height:1.5;
    }

    .empty-state {
        background:#f9fafb;
        border:1px dashed #d1d5db;
        border-radius:14px;
        padding:20px;
        color:#6b7280;
    }

    .empty-state strong {
        display:block;
        color:#111827;
        margin-bottom:6px;
    }

    .empty-state p {
        margin:0;
    }

    @media (max-width: 1200px) {
        .dashboard-stats {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 650px) {
        .dashboard-stats {
            grid-template-columns:1fr;
        }

        .card-header-row {
            flex-direction:column;
        }
    }
</style>
@endsection