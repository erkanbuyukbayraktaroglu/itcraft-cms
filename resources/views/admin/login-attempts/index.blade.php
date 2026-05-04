@extends('admin.layouts.app')

@section('title', 'Login Denemeleri')
@section('topbar_title', 'Login Denemeleri')

@section('content')
<div class="page-title">
    <div>
        <h1>Login Denemeleri</h1>
        <p>Admin panel giriş denemelerini, IP adreslerini ve başarılı/başarısız durumlarını takip edebilirsin.</p>
    </div>
</div>

<div class="login-attempt-summary">
    <div class="login-attempt-card">
        <span>Toplam Deneme</span>
        <strong>{{ $summary['total'] }}</strong>
    </div>

    <div class="login-attempt-card success">
        <span>Başarılı</span>
        <strong>{{ $summary['success'] }}</strong>
    </div>

    <div class="login-attempt-card danger">
        <span>Başarısız</span>
        <strong>{{ $summary['failed'] }}</strong>
    </div>

    <div class="login-attempt-card warning">
        <span>Son 24 Saat Başarısız</span>
        <strong>{{ $summary['last_24h_failed'] }}</strong>
    </div>
</div>

<div class="card">
    <form method="get" class="login-attempt-filter">
        <div>
            <label for="identifier">Kullanıcı / E-posta</label>
            <input type="text" id="identifier" name="identifier" class="form-control" value="{{ $filters['identifier'] ?? '' }}" placeholder="örn. admin@domain.com">
        </div>

        <div>
            <label for="ip_address">IP Adresi</label>
            <input type="text" id="ip_address" name="ip_address" class="form-control" value="{{ $filters['ip_address'] ?? '' }}" placeholder="örn. 1.2.3.4">
        </div>

        <div>
            <label for="status">Durum</label>
            <select id="status" name="status" class="form-control">
                <option value="">Tümü</option>
                <option value="success" @selected(($filters['status'] ?? '') === 'success')>Başarılı</option>
                <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Başarısız</option>
            </select>
        </div>

        <div class="login-attempt-filter-actions">
            <button type="submit" class="btn">Filtrele</button>
            <a href="{{ url('/admin/login-attempts') }}" class="btn btn-secondary">Temizle</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:80px;">ID</th>
                    <th>Kullanıcı / E-posta</th>
                    <th>IP</th>
                    <th>Durum</th>
                    <th>User-Agent</th>
                    <th style="width:180px;">Tarih</th>
                </tr>
            </thead>

            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->id }}</td>
                        <td>
                            <strong>{{ $attempt->identifier ?: '-' }}</strong>
                        </td>
                        <td>
                            <code>{{ $attempt->ip_address ?: '-' }}</code>
                        </td>
                        <td>
                            @if((int) $attempt->successful === 1)
                                <span class="badge badge-success">Başarılı</span>
                            @else
                                <span class="badge badge-danger">Başarısız</span>
                            @endif
                        </td>
                        <td class="ua-cell" title="{{ $attempt->user_agent }}">
                            {{ $attempt->user_agent ?: '-' }}
                        </td>
                        <td>{{ $attempt->created_at ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Login denemesi bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px;">
        {{ $attempts->links() }}
    </div>
</div>

<style>
    .login-attempt-summary {
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:16px;
        margin-bottom:20px;
    }

    .login-attempt-card {
        border-radius:20px;
        padding:18px;
        background:#fff;
        border:1px solid #e5e7eb;
        box-shadow:0 14px 30px rgba(15,23,42,.07);
    }

    .login-attempt-card span {
        display:block;
        color:#64748b;
        font-size:13px;
        font-weight:900;
        margin-bottom:8px;
    }

    .login-attempt-card strong {
        display:block;
        color:#0f172a;
        font-size:34px;
        line-height:1;
        letter-spacing:-.05em;
    }

    .login-attempt-card.success {
        background:linear-gradient(135deg, rgba(16,185,129,.14), #fff 60%);
    }

    .login-attempt-card.danger {
        background:linear-gradient(135deg, rgba(239,68,68,.14), #fff 60%);
    }

    .login-attempt-card.warning {
        background:linear-gradient(135deg, rgba(245,158,11,.18), #fff 60%);
    }

    .login-attempt-filter {
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr));
        gap:14px;
        align-items:end;
    }

    .login-attempt-filter-actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .ua-cell {
        max-width:420px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        color:#64748b;
        font-size:13px;
    }

    .badge {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:5px 9px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
    }

    .badge-success {
        background:#dcfce7;
        color:#166534;
    }

    .badge-danger {
        background:#fee2e2;
        color:#991b1b;
    }

    @media (max-width: 1024px) {
        .login-attempt-summary {
            grid-template-columns:repeat(2, minmax(0, 1fr));
        }

        .login-attempt-filter {
            grid-template-columns:1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .login-attempt-summary,
        .login-attempt-filter {
            grid-template-columns:1fr;
        }
    }
</style>
@endsection