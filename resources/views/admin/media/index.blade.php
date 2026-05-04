@extends('admin.layouts.app')

@section('title', 'Medya Yönetimi')
@section('topbar_title', 'Medya Yönetimi')

@section('content')
<div class="page-title">
    <h1>Medya Yönetimi</h1>
    <p>Görsel ve dosyaları yükleyebilir, optimize edilmiş tam URL adreslerini formlarda kullanabilirsin.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <h2>Yeni Dosya Yükle</h2>

    <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid form-grid-2">
            <div>
                <label for="media_file">Dosya Seç</label>
                <input class="form-control" type="file" id="media_file" name="media_file" accept=".jpg,.jpeg,.png,.webp,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                <small style="display:block; color:#6b7280; margin-top:6px;">
                    JPG, PNG ve WebP görseller otomatik optimize edilir. Büyük görseller maksimum 1920px seviyesine düşürülür.
                </small>
            </div>

            <div style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn">Yükle</button>
            </div>
        </div>
    </form>

    @if(session('uploaded_path'))
        @php
            $uploadedPath = session('uploaded_path');
            $uploadedUrl = str_starts_with($uploadedPath, 'http') ? $uploadedPath : url($uploadedPath);
        @endphp

        <div style="margin-top:18px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:14px;">
            <strong>Yüklenen Dosya URL:</strong>
            <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-top:8px;">
                <code style="word-break:break-word;">{{ $uploadedUrl }}</code>
                <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('{{ $uploadedUrl }}'); alert('Tam URL kopyalandı');">
                    URL Kopyala
                </button>
            </div>
        </div>
    @endif
</div>

<div class="card">
    <h2>Yüklenen Dosyalar</h2>

    @if(empty($files))
        <p style="color:#6b7280;">Henüz medya dosyası yüklenmemiş.</p>
    @else
        <div class="media-grid">
            @foreach($files as $file)
                @php
                    $fullUrl = $file['url'];
                @endphp

                <div class="media-card">
                    <div class="media-preview">
                        @if($file['is_image'])
                            <img src="{{ $fullUrl }}" alt="{{ $file['name'] }}">
                        @else
                            <div class="media-file-icon">{{ strtoupper($file['extension']) }}</div>
                        @endif
                    </div>

                    <div class="media-info">
                        <strong title="{{ $file['name'] }}">{{ $file['name'] }}</strong>
                        <span>{{ $file['size'] }}</span>

                        <label style="font-size:12px; color:#6b7280;">Tam URL</label>
                        <code>{{ $fullUrl }}</code>

                        <label style="font-size:12px; color:#6b7280;">Relative Path</label>
                        <code>{{ $file['path'] }}</code>
                    </div>

                    <div class="media-actions">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('{{ $fullUrl }}'); alert('Tam URL kopyalandı');">
                            URL Kopyala
                        </button>

                        <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('{{ $file['path'] }}'); alert('Relative path kopyalandı');">
                            Path Kopyala
                        </button>

                        <a href="{{ $fullUrl }}" target="_blank" class="btn btn-secondary btn-sm">Aç</a>

                        <form method="post" action="{{ route('admin.media.delete') }}" onsubmit="return confirm('Bu dosyayı silmek istediğine emin misin?');">
                            @csrf
                            <input type="hidden" name="path" value="{{ $file['path'] }}">
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .media-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));
        gap:18px;
    }

    .media-card {
        border:1px solid #e5e7eb;
        border-radius:16px;
        overflow:hidden;
        background:#fff;
    }

    .media-preview {
        height:160px;
        background:#f9fafb;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .media-preview img {
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .media-file-icon {
        width:70px;
        height:70px;
        border-radius:18px;
        background:#111827;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:800;
    }

    .media-info {
        padding:14px;
        display:grid;
        gap:7px;
    }

    .media-info strong {
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .media-info span {
        color:#6b7280;
        font-size:13px;
    }

    .media-info code {
        font-size:12px;
        white-space:normal;
        word-break:break-word;
        background:#f3f4f6;
        padding:6px;
        border-radius:8px;
    }

    .media-actions {
        padding:0 14px 14px;
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .btn-sm {
        padding:8px 10px;
        font-size:13px;
    }
</style>
@endsection