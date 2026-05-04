@extends('admin.layouts.app')

@section('title', 'Tema Presetleri')
@section('topbar_title', 'Tema Presetleri')

@section('content')
<div class="page-title">
    <div>
        <h1>Tema Presetleri</h1>
        <p>Sektöre göre hazırlanmış hazır tema setlerinden birini tek tıkla uygulayabilirsin.</p>
    </div>

    <a href="{{ url('/admin/theme-settings') }}" class="btn btn-secondary">Tema Ayarlarına Git</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="theme-preset-note">
    <strong>Not:</strong>
    Preset uygulandığında mevcut tema renkleri, font, radius, footer renkleri ve gölge ayarları seçilen sektörel pakete göre güncellenir.
</div>

<div class="theme-preset-grid">
    @foreach($presets as $key => $preset)
        @php
            $values = $preset['values'];
        @endphp

        <div class="theme-preset-card">
            <div class="theme-preset-preview" style="background: {{ $values['theme_body_bg'] }};">
                <div class="theme-preset-header" style="background: {{ $values['theme_header_bg'] }};">
                    <span style="background: {{ $values['theme_primary_color'] }};"></span>
                    <span style="background: {{ $values['theme_secondary_color'] }};"></span>
                    <span style="background: {{ $values['theme_accent_color'] }};"></span>
                </div>

                <div class="theme-preset-hero">
                    <span class="theme-preset-badge" style="background: {{ $values['theme_accent_color'] }}22; color: {{ $values['theme_accent_color'] }};">
                        {{ $preset['badge'] }}
                    </span>

                    <h3 style="color: {{ $values['theme_heading_color'] }};">
                        {{ $preset['name'] }}
                    </h3>

                    <p style="color: {{ $values['theme_text_color'] }};">
                        {{ $preset['sector'] }}
                    </p>

                    <div class="theme-preset-button" style="background: {{ $values['theme_button_color'] }}; border-radius: {{ $values['theme_button_radius'] }};">
                        Uygula
                    </div>
                </div>

                <div class="theme-preset-footer" style="background: {{ $values['theme_footer_bg'] }}; color: {{ $values['theme_footer_text_color'] }};">
                    <span style="color: {{ $values['theme_footer_heading_color'] }};">Footer</span>
                    <small style="color: {{ $values['theme_footer_link_color'] }};">Link</small>
                </div>
            </div>

            <div class="theme-preset-content">
                <div class="theme-preset-title-row">
                    <div>
                        <h2>{{ $preset['name'] }}</h2>
                        <span>{{ $preset['sector'] }}</span>
                    </div>

                    <span class="theme-preset-sector">{{ $preset['badge'] }}</span>
                </div>

                <p>{{ $preset['description'] }}</p>

                <div class="theme-preset-colors">
                    <span title="Ana renk" style="background: {{ $values['theme_primary_color'] }}"></span>
                    <span title="İkincil renk" style="background: {{ $values['theme_secondary_color'] }}"></span>
                    <span title="Vurgu rengi" style="background: {{ $values['theme_accent_color'] }}"></span>
                    <span title="Buton rengi" style="background: {{ $values['theme_button_color'] }}"></span>
                    <span title="Footer arka plan" style="background: {{ $values['theme_footer_bg'] }}"></span>
                </div>

                <div class="theme-preset-meta">
                    <div>
                        <strong>Font</strong>
                        <span>{{ explode(',', $values['theme_font_family'])[0] }}</span>
                    </div>

                    <div>
                        <strong>Radius</strong>
                        <span>{{ $values['theme_button_radius'] }}</span>
                    </div>

                    <div>
                        <strong>Gölge</strong>
                        <span>{{ $values['theme_shadow_level'] }}</span>
                    </div>
                </div>

                <form method="post" action="{{ route('admin.theme-presets.apply') }}" onsubmit="return confirm('{{ $preset['name'] }} temasını uygulamak istiyor musun? Mevcut tema ayarları bu preset ile değiştirilecek.');">
                    @csrf
                    <input type="hidden" name="preset" value="{{ $key }}">
                    <button type="submit" class="btn theme-preset-apply-btn">Bu Temayı Uygula</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<style>
    .theme-preset-note {
        background:#fff7ed;
        border:1px solid #fed7aa;
        color:#9a3412;
        padding:14px 16px;
        border-radius:16px;
        margin-bottom:20px;
        line-height:1.55;
    }

    .theme-preset-grid {
        display:grid;
        grid-template-columns:repeat(2, minmax(0, 1fr));
        gap:22px;
    }

    .theme-preset-card {
        background:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 18px 42px rgba(15,23,42,.08);
        display:grid;
        grid-template-columns:260px minmax(0, 1fr);
        min-height:320px;
    }

    .theme-preset-preview {
        display:flex;
        flex-direction:column;
        min-height:100%;
        border-right:1px solid #e5e7eb;
    }

    .theme-preset-header {
        display:flex;
        gap:7px;
        padding:14px;
        border-bottom:1px solid rgba(148,163,184,.25);
    }

    .theme-preset-header span {
        width:13px;
        height:13px;
        border-radius:999px;
        display:block;
    }

    .theme-preset-hero {
        padding:22px;
        flex:1;
    }

    .theme-preset-badge {
        display:inline-flex;
        padding:5px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        margin-bottom:14px;
    }

    .theme-preset-hero h3 {
        font-size:25px;
        line-height:1.08;
        letter-spacing:-.05em;
        margin:0 0 8px;
    }

    .theme-preset-hero p {
        margin:0 0 16px;
        font-weight:700;
        line-height:1.45;
    }

    .theme-preset-button {
        display:inline-flex;
        color:#ffffff;
        min-height:40px;
        padding:10px 15px;
        align-items:center;
        justify-content:center;
        font-weight:900;
        font-size:13px;
    }

    .theme-preset-footer {
        padding:14px 18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        font-weight:800;
    }

    .theme-preset-footer small {
        font-weight:900;
    }

    .theme-preset-content {
        padding:22px;
        display:flex;
        flex-direction:column;
        gap:16px;
    }

    .theme-preset-title-row {
        display:flex;
        justify-content:space-between;
        gap:14px;
        align-items:flex-start;
    }

    .theme-preset-title-row h2 {
        margin:0 0 5px;
        font-size:22px;
        line-height:1.1;
        letter-spacing:-.04em;
    }

    .theme-preset-title-row span {
        color:#64748b;
        font-weight:800;
        font-size:13px;
    }

    .theme-preset-sector {
        display:inline-flex;
        background:#f1f5f9;
        color:#334155 !important;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px !important;
        font-weight:950 !important;
        white-space:nowrap;
    }

    .theme-preset-content p {
        margin:0;
        color:#475569;
        line-height:1.6;
    }

    .theme-preset-colors {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .theme-preset-colors span {
        width:32px;
        height:32px;
        border-radius:999px;
        border:2px solid #ffffff;
        box-shadow:0 0 0 1px #e5e7eb;
        display:block;
    }

    .theme-preset-meta {
        display:grid;
        grid-template-columns:repeat(3, minmax(0, 1fr));
        gap:10px;
    }

    .theme-preset-meta div {
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:10px;
    }

    .theme-preset-meta strong {
        display:block;
        font-size:11px;
        color:#64748b;
        margin-bottom:4px;
        text-transform:uppercase;
        letter-spacing:.06em;
    }

    .theme-preset-meta span {
        display:block;
        color:#0f172a;
        font-weight:900;
        font-size:13px;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .theme-preset-apply-btn {
        width:100%;
        justify-content:center;
    }

    @media (max-width: 1280px) {
        .theme-preset-grid {
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 760px) {
        .theme-preset-card {
            grid-template-columns:1fr;
        }

        .theme-preset-preview {
            border-right:0;
            border-bottom:1px solid #e5e7eb;
        }

        .theme-preset-meta {
            grid-template-columns:1fr;
        }
    }
</style>
<!-- theme-preset-card-polish-start -->
<style>
    .theme-preset-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 22px !important;
        align-items: stretch !important;
    }

    .theme-preset-card {
        position: relative !important;
        display: flex !important;
        flex-direction: column !important;
        min-height: 100% !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 26px !important;
        overflow: hidden !important;
        box-shadow: 0 18px 42px rgba(15, 23, 42, .08) !important;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease !important;
    }

    .theme-preset-card:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 26px 56px rgba(15, 23, 42, .14) !important;
        border-color: #cbd5e1 !important;
    }

    .theme-preset-preview {
        display: flex !important;
        flex-direction: column !important;
        min-height: 275px !important;
        border-right: 0 !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .theme-preset-header {
        min-height: 48px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 15px 18px !important;
        border-bottom: 1px solid rgba(148, 163, 184, .24) !important;
    }

    .theme-preset-header::after {
        content: "Preview";
        margin-left: auto;
        font-size: 11px;
        font-weight: 950;
        color: #94a3b8;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .theme-preset-header span {
        width: 14px !important;
        height: 14px !important;
        border-radius: 999px !important;
        display: block !important;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.35), 0 0 0 1px rgba(15,23,42,.08) !important;
    }

    .theme-preset-hero {
        flex: 1 !important;
        padding: 24px !important;
    }

    .theme-preset-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        padding: 7px 11px !important;
        border-radius: 999px !important;
        font-size: 12px !important;
        font-weight: 950 !important;
        margin-bottom: 16px !important;
    }

    .theme-preset-badge::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: currentColor;
        opacity: .9;
    }

    .theme-preset-hero h3 {
        font-size: 25px !important;
        line-height: 1.05 !important;
        letter-spacing: -.055em !important;
        margin: 0 0 10px !important;
        max-width: 260px !important;
    }

    .theme-preset-hero p {
        margin: 0 0 18px !important;
        font-size: 14px !important;
        font-weight: 800 !important;
        line-height: 1.45 !important;
        max-width: 280px !important;
    }

    .theme-preset-button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff !important;
        min-height: 40px !important;
        padding: 10px 16px !important;
        font-weight: 950 !important;
        font-size: 13px !important;
        box-shadow: 0 12px 24px rgba(15,23,42,.14) !important;
    }

    .theme-preset-footer {
        min-height: 52px !important;
        padding: 14px 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 10px !important;
        font-weight: 900 !important;
    }

    .theme-preset-content {
        flex: 1 !important;
        padding: 22px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 16px !important;
    }

    .theme-preset-title-row h2 {
        margin: 0 0 6px !important;
        font-size: 21px !important;
        line-height: 1.1 !important;
        letter-spacing: -.045em !important;
        color: #0f172a !important;
    }

    .theme-preset-title-row span {
        color: #64748b !important;
        font-weight: 850 !important;
        font-size: 13px !important;
    }

    .theme-preset-sector {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 30px !important;
        background: #f1f5f9 !important;
        color: #334155 !important;
        padding: 6px 11px !important;
        border-radius: 999px !important;
        font-size: 12px !important;
        font-weight: 950 !important;
        white-space: nowrap !important;
    }

    .theme-preset-content p {
        margin: 0 !important;
        color: #475569 !important;
        line-height: 1.58 !important;
        font-size: 14px !important;
    }

    .theme-preset-colors {
        display: flex !important;
        gap: 9px !important;
        flex-wrap: wrap !important;
        padding: 10px !important;
        background: #f8fafc !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 16px !important;
    }

    .theme-preset-colors span {
        width: 30px !important;
        height: 30px !important;
        border-radius: 11px !important;
        border: 2px solid #ffffff !important;
        box-shadow: 0 0 0 1px #e5e7eb, 0 6px 12px rgba(15,23,42,.08) !important;
        display: block !important;
    }

    .theme-preset-meta {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-top: auto !important;
    }

    .theme-preset-meta div {
        background: #f8fafc !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 15px !important;
        padding: 11px !important;
    }

    .theme-preset-meta strong {
        display: block !important;
        font-size: 10px !important;
        color: #64748b !important;
        margin-bottom: 5px !important;
        text-transform: uppercase !important;
        letter-spacing: .08em !important;
    }

    .theme-preset-meta span {
        display: block !important;
        color: #0f172a !important;
        font-weight: 950 !important;
        font-size: 13px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .theme-preset-apply-btn {
        width: 100% !important;
        justify-content: center !important;
        min-height: 44px !important;
        border-radius: 14px !important;
        margin-top: 2px !important;
    }

    .theme-preset-note {
        border-radius: 18px !important;
        margin-bottom: 22px !important;
    }

    @media (max-width: 1500px) {
        .theme-preset-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 920px) {
        .theme-preset-grid {
            grid-template-columns: 1fr !important;
        }

        .theme-preset-preview {
            min-height: 250px !important;
        }
    }

    @media (max-width: 640px) {
        .theme-preset-meta {
            grid-template-columns: 1fr !important;
        }
    }
</style>
<!-- theme-preset-card-polish-end -->
@endsection