<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Last Laravel Error Reader
|--------------------------------------------------------------------------
| Son Laravel log kayıtlarını gösterir.
| Hiçbir dosyayı değiştirmez.
| İşlem bitince bu dosya manuel olarak silinmelidir.
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');

$basePath = dirname(__DIR__);
$logPath = $basePath . '/storage/logs/laravel.log';

function tail_file(string $filePath, int $lines = 260): string
{
    if (!file_exists($filePath)) {
        return 'Log dosyası bulunamadı: ' . $filePath;
    }

    if (!is_readable($filePath)) {
        return 'Log dosyası okunamıyor: ' . $filePath;
    }

    $data = file($filePath, FILE_IGNORE_NEW_LINES);

    if (!$data) {
        return 'Log dosyası boş veya okunamadı.';
    }

    $tail = array_slice($data, -$lines);

    return implode("\n", $tail);
}

$output = tail_file($logPath, 260);

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Son Laravel Hatası</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background:#f5f7fb;
            color:#111827;
            padding:30px;
        }

        .box {
            max-width:1200px;
            margin:0 auto;
            background:#fff;
            border-radius:14px;
            padding:28px;
            box-shadow:0 10px 30px rgba(0,0,0,.06);
        }

        pre {
            white-space:pre-wrap;
            word-break:break-word;
            background:#111827;
            color:#e5e7eb;
            padding:18px;
            border-radius:12px;
            overflow:auto;
            max-height:760px;
            font-size:13px;
            line-height:1.55;
        }

        .warning {
            margin-top:18px;
            background:#fff7ed;
            border:1px solid #fed7aa;
            color:#9a3412;
            padding:14px 16px;
            border-radius:10px;
            line-height:1.6;
        }

        code {
            background:#f3f4f6;
            color:#111827;
            padding:3px 6px;
            border-radius:6px;
        }
    </style>
</head>
<body>
<div class="box">
    <h1>Son Laravel Hatası</h1>

    <pre><?= htmlspecialchars($output) ?></pre>


</div>
</body>
</html>