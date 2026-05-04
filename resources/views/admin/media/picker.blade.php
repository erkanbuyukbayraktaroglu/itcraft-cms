<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Medya Seç</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin:0;
            font-family:Arial, Helvetica, sans-serif;
            background:#f5f7fb;
            color:#111827;
        }

        .picker-header {
            position:sticky;
            top:0;
            z-index:5;
            background:#fff;
            border-bottom:1px solid #e5e7eb;
            padding:16px;
        }

        .picker-header h1 {
            margin:0 0 6px;
            font-size:20px;
        }

        .picker-header p {
            margin:0;
            color:#6b7280;
            font-size:14px;
        }

        .picker-body {
            padding:16px;
        }

        .upload-box {
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:16px;
            padding:16px;
            margin-bottom:16px;
        }

        .upload-row {
            display:grid;
            grid-template-columns:1fr auto;
            gap:10px;
            align-items:end;
        }

        input[type=file] {
            width:100%;
            border:1px solid #d1d5db;
            border-radius:12px;
            padding:11px;
            background:#fff;
        }

        .btn {
            border:0;
            border-radius:12px;
            padding:12px 14px;
            background:#111827;
            color:#fff;
            font-weight:700;
            cursor:pointer;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .btn-secondary {
            background:#f3f4f6;
            color:#111827;
        }

        .alert {
            border-radius:12px;
            padding:12px 14px;
            margin-bottom:14px;
            font-weight:700;
        }

        .alert-success {
            background:#dcfce7;
            color:#166534;
            border:1px solid #86efac;
        }

        .alert-danger {
            background:#fee2e2;
            color:#991b1b;
            border:1px solid #fecaca;
        }

        .media-grid {
            display:grid;
            grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));
            gap:14px;
        }

        .media-card {
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:16px;
            overflow:hidden;
        }

        .media-preview {
            height:130px;
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
            width:62px;
            height:62px;
            border-radius:16px;
            background:#111827;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
        }

        .media-info {
            padding:12px;
            display:grid;
            gap:7px;
        }

        .media-info strong {
            font-size:13px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .media-info code {
            background:#f3f4f6;
            padding:6px;
            border-radius:8px;
            font-size:11px;
            word-break:break-word;
        }

        .select-btn {
            width:100%;
            margin-top:4px;
        }

        .path-btn {
            width:100%;
            background:#f3f4f6;
            color:#111827;
        }

        @media (max-width: 640px) {
            .upload-row {
                grid-template-columns:1fr;
            }

            .media-grid {
                grid-template-columns:1fr 1fr;
            }
        }
    </style>
</head>
<body>
<div class="picker-header">
    <h1>Medya Seç</h1>
    <p>Bir dosya seç veya yeni dosya yükle. Görseller otomatik optimize edilir.</p>
</div>

<div class="picker-body">
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

    <div class="upload-box">
        <form method="post" action="{{ route('admin.media.upload_optimized') }}" enctype="multipart/form-data">
            @csrf

            <div class="upload-row">
                <div>
                    <input type="file" name="media_file" accept=".jpg,.jpeg,.png,.webp,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                </div>

                <button type="submit" class="btn">Yükle ve Optimize Et</button>
            </div>
        </form>
    </div>

    @if(empty($files))
        <p style="color:#6b7280;">Henüz medya dosyası yok.</p>
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
                        <code>{{ $fullUrl }}</code>

                        <button type="button" class="btn select-btn" data-value="{{ $fullUrl }}">
                            Tam URL Seç
                        </button>

                        <button type="button" class="btn path-btn" data-value="{{ $file['path'] }}">
                            Path Seç
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function selectMediaValue(value) {
        if (window.parent) {
            window.parent.postMessage({
                type: 'media-selected',
                path: value
            }, window.location.origin);
        }
    }

    document.querySelectorAll('[data-value]').forEach(function (button) {
        button.addEventListener('click', function () {
            selectMediaValue(button.getAttribute('data-value'));
        });
    });

    @if(!empty($selected))
        selectMediaValue(@json(str_starts_with($selected, 'http') ? $selected : url($selected)));
    @endif
</script>
</body>
</html>