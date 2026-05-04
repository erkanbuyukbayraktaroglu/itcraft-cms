<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Sunucu Hatası | ITCRAFT CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">

    <style>
        :root {
            --primary: #111827;
            --secondary: #374151;
            --accent: #b08968;
            --danger: #991b1b;
            --bg: #f8fafc;
            --white: #ffffff;
            --border: rgba(17, 24, 39, .10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(153, 27, 27, .10), transparent 34%),
                linear-gradient(135deg, #f8fafc, #ffffff);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .error-wrap {
            width: 100%;
            max-width: 760px;
            background: rgba(255, 255, 255, .94);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 46px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .10);
            text-align: center;
        }

        .code {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 112px;
            height: 54px;
            border-radius: 999px;
            background: rgba(153, 27, 27, .10);
            color: var(--danger);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 22px;
        }

        h1 {
            margin: 0 0 14px;
            font-size: clamp(32px, 5vw, 52px);
            line-height: 1.05;
            letter-spacing: -1.4px;
        }

        p {
            margin: 0 auto 28px;
            max-width: 570px;
            color: var(--secondary);
            font-size: 17px;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 20px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            transition: .18s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--primary);
            border: 1px solid var(--border);
        }

        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, .12);
        }

        .small {
            margin-top: 28px;
            color: #9ca3af;
            font-size: 13px;
        }

        @media (max-width: 560px) {
            .error-wrap {
                padding: 34px 22px;
                border-radius: 22px;
            }

            .actions a {
                width: 100%;
            }
        }
    </style>
<!-- frontend-theme-style-start -->
@include('partials.theme-style')
<!-- frontend-theme-style-end -->
</head>
<body class="theme-enabled">
    <main class="error-wrap theme-scope">
        <div class="code">500</div>
        <h1>Geçici bir sorun oluştu</h1>
        <p>
            İsteğiniz işlenirken beklenmeyen bir sunucu hatası oluştu.
            Lütfen kısa bir süre sonra tekrar deneyin. Sorun devam ederse bizimle iletişime geçebilirsiniz.
        </p>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn-primary">Ana Sayfaya Dön</a>
            <a href="{{ url('/iletisim') }}" class="btn-secondary">İletişime Geç</a>
        </div>

        <div class="small">
            {{ setting('site_slogan', setting('site_name', '')) }}
        </div>
    </main>
</body>
</html>