@extends('admin.layouts.app')

@section('title', 'Kullanım Kılavuzu')
@section('topbar_title', 'Kullanım Kılavuzu')

@section('content')
<div class="page-title">
    <h1>Kullanım Kılavuzu</h1>
    <p>Admin panel kullanımı için hazırlanmış yardım dokümanı.</p>
</div>

@if(!empty($guidePath))
    <div class="alert alert-info">
        Okunan doküman:
        <code>{{ str_replace(base_path() . DIRECTORY_SEPARATOR, '', $guidePath) }}</code>
    </div>
@endif

<div class="card usage-guide-card">
    <div class="usage-guide-content">
        {!! $html !!}
    </div>
</div>

<style>
    .usage-guide-card {
        max-width: 1080px;
    }

    .usage-guide-content {
        color: #1f2937;
        line-height: 1.75;
        font-size: 15px;
    }

    .usage-guide-content h1 {
        font-size: 32px;
        line-height: 1.2;
        margin: 0 0 22px;
        letter-spacing: -0.04em;
    }

    .usage-guide-content h2 {
        font-size: 25px;
        margin: 36px 0 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
        letter-spacing: -0.03em;
    }

    .usage-guide-content h3 {
        font-size: 20px;
        margin: 28px 0 12px;
    }

    .usage-guide-content h4 {
        font-size: 17px;
        margin: 22px 0 10px;
    }

    .usage-guide-content p {
        margin: 0 0 14px;
    }

    .usage-guide-content ul {
        margin: 0 0 18px 22px;
        padding: 0;
    }

    .usage-guide-content li {
        margin-bottom: 7px;
    }

    .usage-guide-content hr {
        border: 0;
        border-top: 1px solid #e5e7eb;
        margin: 30px 0;
    }

    .usage-guide-content pre {
        background: #111827;
        color: #e5e7eb;
        border-radius: 14px;
        padding: 16px;
        overflow-x: auto;
        margin: 14px 0 20px;
        line-height: 1.6;
    }

    .usage-guide-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }

    .usage-guide-content code {
        background: #f3f4f6;
        color: #111827;
        padding: 3px 6px;
        border-radius: 7px;
        font-size: 13px;
    }

    .usage-guide-content blockquote {
        margin: 18px 0;
        padding: 14px 16px;
        border-left: 4px solid #111827;
        background: #f9fafb;
        border-radius: 10px;
        color: #374151;
    }

    .usage-guide-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 18px 0;
        overflow: hidden;
        border-radius: 14px;
    }

    .usage-guide-content th,
    .usage-guide-content td {
        border: 1px solid #e5e7eb;
        padding: 12px 14px;
        text-align: left;
        vertical-align: top;
    }

    .usage-guide-content th {
        background: #f9fafb;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .usage-guide-content h1 {
            font-size: 26px;
        }

        .usage-guide-content h2 {
            font-size: 21px;
        }

        .usage-guide-content table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endsection