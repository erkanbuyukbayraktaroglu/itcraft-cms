<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| ITCRAFT CMS GitHub Bootstrap Installer
|--------------------------------------------------------------------------
| Bu dosya GitHub reposundan ITCRAFT CMS paketini indirir,
| sunucuya açar ve /install ekranına yönlendirir.
|
| Kullanım:
| https://domain.com/itcraft_cms_bootstrap.php
|
| İşlem bitince bu dosya mutlaka silinmelidir.
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);

/*
|--------------------------------------------------------------------------
| Ayarlar
|--------------------------------------------------------------------------
*/

$repoZipUrl = 'https://github.com/erkanbuyukbayraktaroglu/itcraft-cms/archive/refs/heads/main.zip';

/*
| Script'in bulunduğu dizin kurulum kökü kabul edilir.
| cPanel'de genelde public_html veya subdomain root olur.
*/
$installRoot = __DIR__;

$tempDir = $installRoot . '/itcraft-cms-temp';
$zipFile = $installRoot . '/itcraft-cms-main.zip';

$results = [];

function add_result(array &$results, string $step, string $status, string $message): void
{
    $results[] = [
        'step' => $step,
        'status' => $status,
        'message' => $message,
    ];
}

function rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            @rmdir($item->getRealPath());
        } else {
            @unlink($item->getRealPath());
        }
    }

    @rmdir($dir);
}

function copy_directory(string $source, string $destination, array &$results): void
{
    if (!is_dir($source)) {
        throw new RuntimeException('Kaynak klasör bulunamadı: ' . $source);
    }

    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        $sourcePath = $item->getPathname();
        $relativePath = substr($sourcePath, strlen($source) + 1);
        $targetPath = $destination . '/' . $relativePath;

        /*
        |--------------------------------------------------------------------------
        | Bootstrap dosyasını ezme
        |--------------------------------------------------------------------------
        */

        if (basename($targetPath) === 'itcraft_cms_bootstrap.php') {
            continue;
        }

        if ($item->isDir()) {
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        } else {
            $targetDir = dirname($targetPath);

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            copy($sourcePath, $targetPath);
        }
    }
}

function download_file(string $url, string $target): void
{
    if (function_exists('curl_init')) {
        $fp = fopen($target, 'wb');

        if (!$fp) {
            throw new RuntimeException('ZIP dosyası oluşturulamadı.');
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'ITCRAFT-CMS-Installer',
        ]);

        $ok = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        fclose($fp);

        if (!$ok || $httpCode >= 400) {
            @unlink($target);
            throw new RuntimeException('GitHub ZIP indirilemedi. HTTP: ' . $httpCode . ' Hata: ' . $error);
        }

        return;
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 300,
            'header' => "User-Agent: ITCRAFT-CMS-Installer\r\n",
        ],
    ]);

    $data = file_get_contents($url, false, $context);

    if ($data === false) {
        throw new RuntimeException('GitHub ZIP indirilemedi. cURL yok, file_get_contents başarısız.');
    }

    file_put_contents($target, $data);
}

function find_extracted_root(string $tempDir): string
{
    $dirs = array_filter(glob($tempDir . '/*') ?: [], 'is_dir');

    if (!$dirs) {
        throw new RuntimeException('ZIP açıldı fakat proje klasörü bulunamadı.');
    }

    return array_values($dirs)[0];
}

function make_required_dirs(string $root): void
{
    $dirs = [
        $root . '/storage',
        $root . '/storage/app',
        $root . '/storage/app/public',
        $root . '/storage/framework',
        $root . '/storage/framework/cache',
        $root . '/storage/framework/cache/data',
        $root . '/storage/framework/sessions',
        $root . '/storage/framework/views',
        $root . '/storage/logs',
        $root . '/bootstrap/cache',
        $root . '/public/uploads',
        $root . '/public/uploads/media',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        @chmod($dir, 0775);
    }
}

function check_existing_project(string $root): bool
{
    return file_exists($root . '/artisan')
        || is_dir($root . '/app')
        || is_dir($root . '/vendor')
        || file_exists($root . '/composer.json');
}

try {
    /*
    |--------------------------------------------------------------------------
    | 1. Sistem kontrolü
    |--------------------------------------------------------------------------
    */

    add_result($results, 'Sistem', 'INFO', 'Kurulum kökü: ' . $installRoot);

    if (!class_exists(ZipArchive::class)) {
        throw new RuntimeException('ZipArchive PHP eklentisi aktif değil. cPanel PHP Extensions içinden zip aktif edilmeli.');
    }

    add_result($results, 'ZipArchive', 'SUCCESS', 'Aktif.');

    if (!is_writable($installRoot)) {
        throw new RuntimeException('Kurulum dizini yazılabilir değil: ' . $installRoot);
    }

    add_result($results, 'Dizin Yetkisi', 'SUCCESS', 'Kurulum dizini yazılabilir.');

    /*
    |--------------------------------------------------------------------------
    | 2. Mevcut proje kontrolü
    |--------------------------------------------------------------------------
    */

    if (check_existing_project($installRoot) && !isset($_GET['force'])) {
        throw new RuntimeException(
            'Bu dizinde mevcut Laravel/proje dosyaları görünüyor. Temiz kurulum için boş dizin önerilir. Yine de devam etmek istersen URL sonuna ?force=1 ekle.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Eski temp dosyalarını temizle
    |--------------------------------------------------------------------------
    */

    rrmdir($tempDir);

    if (file_exists($zipFile)) {
        @unlink($zipFile);
    }

    mkdir($tempDir, 0755, true);

    add_result($results, 'Temizlik', 'SUCCESS', 'Geçici klasör hazırlandı.');

    /*
    |--------------------------------------------------------------------------
    | 4. GitHub ZIP indir
    |--------------------------------------------------------------------------
    */

    download_file($repoZipUrl, $zipFile);

    if (!file_exists($zipFile) || filesize($zipFile) < 1000) {
        throw new RuntimeException('İndirilen ZIP dosyası geçersiz veya çok küçük.');
    }

    add_result($results, 'GitHub', 'SUCCESS', 'Repo ZIP indirildi.');

    /*
    |--------------------------------------------------------------------------
    | 5. ZIP aç
    |--------------------------------------------------------------------------
    */

    $zip = new ZipArchive();

    if ($zip->open($zipFile) !== true) {
        throw new RuntimeException('ZIP dosyası açılamadı.');
    }

    $zip->extractTo($tempDir);
    $zip->close();

    add_result($results, 'ZIP', 'SUCCESS', 'ZIP başarıyla açıldı.');

    /*
    |--------------------------------------------------------------------------
    | 6. Dosyaları kurulum köküne kopyala
    |--------------------------------------------------------------------------
    */

    $extractedRoot = find_extracted_root($tempDir);

    copy_directory($extractedRoot, $installRoot, $results);

    add_result($results, 'Dosyalar', 'SUCCESS', 'ITCRAFT CMS dosyaları kurulum köküne kopyalandı.');

    /*
    |--------------------------------------------------------------------------
    | 7. Gerekli klasörleri hazırla
    |--------------------------------------------------------------------------
    */

    make_required_dirs($installRoot);

    add_result($results, 'Klasörler', 'SUCCESS', 'Storage, cache ve uploads klasörleri hazırlandı.');

    /*
    |--------------------------------------------------------------------------
    | 8. Kritik dosya kontrolü
    |--------------------------------------------------------------------------
    */

    $requiredFiles = [
        'artisan' => $installRoot . '/artisan',
        'vendor/autoload.php' => $installRoot . '/vendor/autoload.php',
        'database/dump/itcraft_cms_base.sql' => $installRoot . '/database/dump/itcraft_cms_base.sql',
        'public/install/index.php' => $installRoot . '/public/install/index.php',
        '.env.example' => $installRoot . '/.env.example',
    ];

    foreach ($requiredFiles as $label => $path) {
        if (!file_exists($path)) {
            throw new RuntimeException('Eksik kritik dosya: ' . $label);
        }

        add_result($results, $label, 'SUCCESS', 'Mevcut.');
    }

    /*
    |--------------------------------------------------------------------------
    | 9. Geçici dosyaları temizle
    |--------------------------------------------------------------------------
    */

    rrmdir($tempDir);

    if (file_exists($zipFile)) {
        @unlink($zipFile);
    }

    add_result($results, 'Temizlik', 'SUCCESS', 'Geçici ZIP ve temp klasörü silindi.');

    /*
    |--------------------------------------------------------------------------
    | 10. Install ekranına yönlendirme linki
    |--------------------------------------------------------------------------
    */

    $installUrl = '/install';

    add_result($results, 'Sonraki Adım', 'SUCCESS', 'Kurulum sihirbazı hazır: ' . $installUrl);

    $completed = true;

} catch (Throwable $exception) {
    $completed = false;
    add_result($results, 'Hata', 'DANGER', $exception->getMessage());
}

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>ITCRAFT CMS Bootstrap Installer</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f3f6fb;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
            padding: 32px 18px;
        }

        .box {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 22px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(15,23,42,.10);
        }

        .hero {
            background: linear-gradient(135deg, #111827, #1d4ed8);
            color: #ffffff;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 22px;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: 32px;
            letter-spacing: -0.05em;
        }

        .hero p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .badge {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
        }

        .SUCCESS {
            background: #dcfce7;
            color: #166534;
        }

        .INFO {
            background: #dbeafe;
            color: #1e40af;
        }

        .DANGER {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        .btn {
            display: inline-flex;
            min-height: 46px;
            padding: 12px 16px;
            border-radius: 14px;
            background: #1d4ed8;
            color: #ffffff;
            text-decoration: none;
            font-weight: 900;
            align-items: center;
            justify-content: center;
        }

        .btn:hover {
            background: #1e40af;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn-danger:hover {
            background: #991b1b;
        }

        .warning {
            margin-top: 20px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 16px;
            padding: 15px 17px;
            line-height: 1.6;
            font-weight: 700;
        }

        code {
            background: #f3f4f6;
            padding: 3px 6px;
            border-radius: 6px;
            word-break: break-word;
        }
    </style>
</head>
<body>
<div class="box">
    <div class="hero">
        <h1>ITCRAFT CMS Bootstrap Installer</h1>
        <p>Bu araç GitHub reposundan ITCRAFT CMS dosyalarını indirir ve kurulum sihirbazını hazırlar.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Adım</th>
                <th>Durum</th>
                <th>Sonuç</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['step']) ?></strong></td>
                    <td><span class="badge <?= htmlspecialchars($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span></td>
                    <td><?= htmlspecialchars($item['message']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($completed): ?>
        <div class="actions">
            <a class="btn" href="/install">Kurulum Sihirbazına Git</a>
        </div>

        <div class="warning">
            Dosyalar indirildi. Şimdi <code>/install</code> ekranından veritabanı ve ilk admin hesabı oluşturulmalı.
            <br><br>
            Kurulum tamamen bittikten sonra şu dosyayı sil:
            <br>
            <code>itcraft_cms_bootstrap.php</code>
            <br><br>
            Ayrıca kurulum tamamlanınca:
            <br>
            <code>public/install/</code>
            <br>
            klasörü de silinmeli.
        </div>
    <?php else: ?>
        <div class="warning">
            Kurulum başlatılamadı. Hata satırını kontrol et.
            <br><br>
            Eğer dizinde eski dosyalar olduğu için durduysa ve bilinçli olarak üzerine yazmak istiyorsan:
            <br>
            <code>?force=1</code>
            <br>
            ile çalıştırabilirsin.
        </div>
    <?php endif; ?>
</div>
</body>
</html>