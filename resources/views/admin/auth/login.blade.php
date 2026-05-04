<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Admin Giriş | {{ setting('site_name', 'Corporate CMS') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #111827;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-card {
            width: min(440px, 100%);
            background: #ffffff;
            border-radius: 22px;
            padding: 30px;
            box-shadow: 0 24px 70px rgba(0,0,0,.24);
        }

        .brand {
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.04em;
        }

        .brand span {
            color: #c9a227;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-weight: 800;
            margin-bottom: 7px;
            color: #374151;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            margin-bottom: 15px;
            font-size: 15px;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: #0f172a;
            color: #ffffff;
            padding: 13px 18px;
            font-size: 15px;
            font-weight: 900;
            cursor: pointer;
        }

        button:hover {
            background: #1e293b;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px 15px;
            border-radius: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 13px 15px;
            border-radius: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .errors {
            background: #fff7ed;
            color: #9a3412;
            padding: 13px 15px;
            border-radius: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .footer-link {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
        }

        .footer-link a {
            color: #0f172a;
            font-weight: 800;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="brand">{{ setting('site_name', 'Corporate CMS') }}<span>.</span></div>
    <div class="subtitle">Yönetim paneline giriş yapın.</div>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="errors">Lütfen giriş bilgilerini kontrol edin.</div>
    @endif

    <form method="post" action="{{ route('admin.login.submit') }}">
        @csrf

        <label for="email">E-posta</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Şifre</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Giriş Yap</button>
    </form>

    <div class="footer-link">
        <a href="{{ route('home') }}">Siteye dön</a>
    </div>
</div>
</body>
</html>