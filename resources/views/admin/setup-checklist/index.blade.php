@extends('admin.layouts.app')

@section('title', 'Kurulum Checklist')
@section('topbar_title', 'Kurulum Checklist')

@section('content')
<div class="page-title">
    <div>
        <h1>Kurulum Checklist</h1>
        <p>İlk kurulum ve müşteri teslimi öncesi kontrol listesi.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(!empty($checklistPath))
    <div class="alert alert-info">
        Okunan doküman:
        <code>{{ str_replace(base_path() . DIRECTORY_SEPARATOR, '', $checklistPath) }}</code>
    </div>
@endif

@if($isCompleted)
    <div class="alert alert-success">
        Bu kurulum checklist kontrolü tamamlandı olarak işaretlenmiş.
        @if($completedAt)
            <br>Tamamlanma zamanı: <strong>{{ $completedAt }}</strong>
        @endif
    </div>
@else
    <div class="alert alert-warning">
        Bu checklist henüz tamamlandı olarak işaretlenmemiş. İlk kurulum ve teslim öncesinde tüm maddeler kontrol edilmelidir.
    </div>
@endif

<div class="setup-checklist-actions">
    @if(!$isCompleted)
        <form method="post" action="{{ route('admin.setup-checklist.complete') }}" onsubmit="return confirm('Kurulum checklist kontrolünü tamamlandı olarak işaretlemek istiyor musun?');">
            @csrf
            <button type="submit" class="btn">Kontrol Edildi Olarak İşaretle</button>
        </form>
    @else
        <form method="post" action="{{ route('admin.setup-checklist.reopen') }}" onsubmit="return confirm('Checklist uyarısını tekrar aktif etmek istiyor musun?');">
            @csrf
            <button type="submit" class="btn btn-secondary">Tekrar Aktif Et</button>
        </form>
    @endif
</div>

<div class="card setup-checklist-card">
    <div class="setup-checklist-content">
        {!! $html !!}
    </div>
</div>

<style>
    .setup-checklist-actions {
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        margin-bottom:18px;
    }

    .setup-checklist-card {
        max-width: 1080px;
    }

    .setup-checklist-content {
        color: #1f2937;
        line-height: 1.75;
        font-size: 15px;
    }

    .setup-checklist-content h1 {
        font-size: 32px;
        line-height: 1.2;
        margin: 0 0 22px;
        letter-spacing: -0.04em;
    }

    .setup-checklist-content h2 {
        font-size: 25px;
        margin: 36px 0 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
        letter-spacing: -0.03em;
    }

    .setup-checklist-content h3 {
        font-size: 20px;
        margin: 28px 0 12px;
    }

    .setup-checklist-content h4 {
        font-size: 17px;
        margin: 22px 0 10px;
    }

    .setup-checklist-content p {
        margin: 0 0 14px;
    }

    .setup-checklist-content ul {
        margin: 0 0 18px 22px;
        padding: 0;
    }

    .setup-checklist-content li {
        margin-bottom: 7px;
    }

    .setup-checklist-content hr {
        border: 0;
        border-top: 1px solid #e5e7eb;
        margin: 30px 0;
    }

    .setup-checklist-content pre {
        background: #111827;
        color: #e5e7eb;
        border-radius: 14px;
        padding: 16px;
        overflow-x: auto;
        margin: 14px 0 20px;
        line-height: 1.6;
    }

    .setup-checklist-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }

    .setup-checklist-content code {
        background: #f3f4f6;
        color: #111827;
        padding: 3px 6px;
        border-radius: 7px;
        font-size: 13px;
    }

    .setup-checklist-content blockquote {
        margin: 18px 0;
        padding: 14px 16px;
        border-left: 4px solid #111827;
        background: #f9fafb;
        border-radius: 10px;
        color: #374151;
    }

    .setup-checklist-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 18px 0;
        overflow: hidden;
        border-radius: 14px;
    }

    .setup-checklist-content th,
    .setup-checklist-content td {
        border: 1px solid #e5e7eb;
        padding: 12px 14px;
        text-align: left;
        vertical-align: top;
    }

    .setup-checklist-content th {
        background: #f9fafb;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .setup-checklist-content h1 {
            font-size: 26px;
        }

        .setup-checklist-content h2 {
            font-size: 21px;
        }

        .setup-checklist-content table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endsection