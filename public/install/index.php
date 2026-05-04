<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| ITCRAFT CMS Web Installer
|--------------------------------------------------------------------------
| cPanel / shared hosting uyumlu ilk kurulum sihirbazına hoşgeldiniz.
|
| Erişim:
| https://domain.com/install
|
| Bu dosya kurulumdan sonra public/install klasörüyle birlikte silinmelidir.
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);

session_start();

$basePath = dirname(__DIR__, 2);
$publicPath = dirname(__DIR__);
$lockFile = $basePath . '/storage/install.lock';
$sqlFile = $basePath . '/database/dump/itcraft_cms_base.sql';
$envFile = $basePath . '/.env';
$envExampleFile = $basePath . '/.env.example';

$errors = [];
$messages = [];

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function human_status(bool $status): string
{
    return $status ? 'OK' : 'HATA';
}

function random_app_key(): string
{
    return 'base64:' . base64_encode(random_bytes(32));
}

function write_env_file(string $envFile, array $data): bool
{
    $env = [];
    $env[] = 'APP_NAME="' . addslashes($data['app_name']) . '"';
    $env[] = 'APP_ENV=production';
    $env[] = 'APP_KEY=' . $data['app_key'];
    $env[] = 'APP_DEBUG=false';
    $env[] = 'APP_URL=' . rtrim($data['app_url'], '/');
    $env[] = '';
    $env[] = 'LOG_CHANNEL=stack';
    $env[] = 'LOG_LEVEL=error';
    $env[] = '';
    $env[] = 'DB_CONNECTION=mysql';
    $env[] = 'DB_HOST=' . $data['db_host'];
    $env[] = 'DB_PORT=' . $data['db_port'];
    $env[] = 'DB_DATABASE=' . $data['db_name'];
    $env[] = 'DB_USERNAME=' . $data['db_user'];
    $env[] = 'DB_PASSWORD=' . $data['db_pass'];
    $env[] = '';
    $env[] = 'SESSION_DRIVER=database';
    $env[] = 'CACHE_STORE=file';
    $env[] = 'QUEUE_CONNECTION=sync';
    $env[] = '';
    $env[] = 'MAIL_MAILER=smtp';
    $env[] = 'MAIL_HOST=';
    $env[] = 'MAIL_PORT=587';
    $env[] = 'MAIL_USERNAME=';
    $env[] = 'MAIL_PASSWORD=';
    $env[] = 'MAIL_ENCRYPTION=tls';
    $env[] = 'MAIL_FROM_ADDRESS=';
    $env[] = 'MAIL_FROM_NAME="${APP_NAME}"';
    $env[] = '';
    $env[] = 'FILESYSTEM_DISK=public';
    $env[] = '';

    return file_put_contents($envFile, implode(PHP_EOL, $env)) !== false;
}

function make_directories(array $directories): array
{
    $results = [];

    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            @mkdir($directory, 0775, true);
        }

        $results[$directory] = is_dir($directory) && is_writable($directory);
    }

    return $results;
}

function remove_sql_comments(string $sql): string
{
    $lines = preg_split('/\r\n|\r|\n/', $sql);
    $clean = [];

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '') {
            $clean[] = $line;
            continue;
        }

        if (str_starts_with($trimmed, '--')) {
            continue;
        }

        if (str_starts_with($trimmed, '#')) {
            continue;
        }

        $clean[] = $line;
    }

    return implode("\n", $clean);
}

function split_sql_statements(string $sql): array
{
    $sql = remove_sql_comments($sql);

    $statements = [];
    $current = '';

    $length = strlen($sql);
    $inSingle = false;
    $inDouble = false;
    $inBacktick = false;
    $escape = false;

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $current .= $char;

        if ($escape) {
            $escape = false;
            continue;
        }

        if ($char === '\\') {
            $escape = true;
            continue;
        }

        if ($char === "'" && !$inDouble && !$inBacktick) {
            $inSingle = !$inSingle;
            continue;
        }

        if ($char === '"' && !$inSingle && !$inBacktick) {
            $inDouble = !$inDouble;
            continue;
        }

        if ($char === '`' && !$inSingle && !$inDouble) {
            $inBacktick = !$inBacktick;
            continue;
        }

        if ($char === ';' && !$inSingle && !$inDouble && !$inBacktick) {
            $statement = trim($current);

            if ($statement !== ';' && $statement !== '') {
                $statements[] = rtrim($statement, ';');
            }

            $current = '';
        }
    }

    $remaining = trim($current);

    if ($remaining !== '') {
        $statements[] = $remaining;
    }

    return $statements;
}

function import_sql_file(PDO $pdo, string $sqlFile): array
{
    if (!file_exists($sqlFile)) {
        throw new RuntimeException('Base SQL dosyası bulunamadı: database/dump/itcraft_cms_base.sql');
    }

    $sql = file_get_contents($sqlFile);

    if ($sql === false) {
        throw new RuntimeException('Base SQL dosyası okunamadı.');
    }

    $statements = split_sql_statements($sql);
    $executed = 0;

    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach ($statements as $statement) {
        $statement = trim($statement);

        if ($statement === '') {
            continue;
        }

        $pdo->exec($statement);
        $executed++;
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    return [
        'statements' => count($statements),
        'executed' => $executed,
    ];
}

function get_table_columns(PDO $pdo, string $table): array
{
    $stmt = $pdo->query('DESCRIBE `' . str_replace('`', '``', $table) . '`');
    $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

    return array_map(static fn ($row) => $row['Field'], $rows);
}

function table_exists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare('SHOW TABLES LIKE ?');
    $stmt->execute([$table]);

    return (bool) $stmt->fetchColumn();
}

function create_admin_user(PDO $pdo, array $admin): void
{
    if (!table_exists($pdo, 'users')) {
        throw new RuntimeException('users tablosu bulunamadı. Base SQL doğru yüklenmemiş olabilir.');
    }

    $columns = get_table_columns($pdo, 'users');

    $check = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $check->execute([$admin['email']]);

    if ((int) $check->fetchColumn() > 0) {
        throw new RuntimeException('Bu e-posta ile admin kullanıcısı zaten var: ' . $admin['email']);
    }

    $data = [];

    if (in_array('name', $columns, true)) {
        $data['name'] = $admin['name'];
    }

    if (in_array('email', $columns, true)) {
        $data['email'] = $admin['email'];
    }

    if (in_array('email_verified_at', $columns, true)) {
        $data['email_verified_at'] = date('Y-m-d H:i:s');
    }

    if (in_array('password', $columns, true)) {
        $data['password'] = password_hash($admin['password'], PASSWORD_BCRYPT);
    }

    if (in_array('role', $columns, true)) {
        $data['role'] = 'admin';
    }

    if (in_array('is_active', $columns, true)) {
        $data['is_active'] = 1;
    }

    if (in_array('created_at', $columns, true)) {
        $data['created_at'] = date('Y-m-d H:i:s');
    }

    if (in_array('updated_at', $columns, true)) {
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    if (!isset($data['email'], $data['password'])) {
        throw new RuntimeException('users tablosunda email/password kolonları bulunamadı.');
    }

    $fieldSql = implode(', ', array_map(static fn ($field) => '`' . $field . '`', array_keys($data)));
    $placeholderSql = implode(', ', array_fill(0, count($data), '?'));

    $stmt = $pdo->prepare("INSERT INTO users ({$fieldSql}) VALUES ({$placeholderSql})");
    $stmt->execute(array_values($data));
}

function update_site_settings(PDO $pdo, array $site): void
{
    if (!table_exists($pdo, 'site_settings')) {
        return;
    }

    $columns = get_table_columns($pdo, 'site_settings');
    $rowExists = (int) $pdo->query('SELECT COUNT(*) FROM site_settings')->fetchColumn() > 0;

    $possibleUpdates = [
        'site_name' => $site['app_name'],
        'site_title' => $site['app_name'],
        'company_name' => $site['app_name'],
        'app_name' => $site['app_name'],
        'site_url' => rtrim($site['app_url'], '/'),
        'url' => rtrim($site['app_url'], '/'),
    ];

    $data = [];

    foreach ($possibleUpdates as $column => $value) {
        if (in_array($column, $columns, true)) {
            $data[$column] = $value;
        }
    }

    if (in_array('updated_at', $columns, true)) {
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    if (!$data) {
        return;
    }

    if ($rowExists) {
        $set = implode(', ', array_map(static fn ($field) => '`' . $field . '` = ?', array_keys($data)));
        $stmt = $pdo->prepare("UPDATE site_settings SET {$set} ORDER BY id ASC LIMIT 1");
        $stmt->execute(array_values($data));
    } else {
        if (in_array('created_at', $columns, true)) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $fieldSql = implode(', ', array_map(static fn ($field) => '`' . $field . '`', array_keys($data)));
        $placeholderSql = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $pdo->prepare("INSERT INTO site_settings ({$fieldSql}) VALUES ({$placeholderSql})");
        $stmt->execute(array_values($data));
    }
}

function create_storage_link(string $basePath, string $publicPath): string
{
    $target = $basePath . '/storage/app/public';
    $link = $publicPath . '/storage';

    if (file_exists($link)) {
        return 'public/storage zaten mevcut.';
    }

    if (!is_dir($target)) {
        @mkdir($target, 0775, true);
    }

    if (function_exists('symlink')) {
        $ok = @symlink($target, $link);

        if ($ok) {
            return 'public/storage symlink oluşturuldu.';
        }
    }

    return 'public/storage symlink oluşturulamadı. Gerekirse cPanel üzerinden manuel oluştur.';
}

function clear_laravel_cache_files(string $basePath): int
{
    $deleted = 0;

    $files = [
        $basePath . '/bootstrap/cache/config.php',
        $basePath . '/bootstrap/cache/routes.php',
        $basePath . '/bootstrap/cache/routes-v7.php',
        $basePath . '/bootstrap/cache/services.php',
        $basePath . '/bootstrap/cache/packages.php',
    ];

    foreach ($files as $file) {
        if (file_exists($file) && @unlink($file)) {
            $deleted++;
        }
    }

    $viewCachePath = $basePath . '/storage/framework/views';

    if (is_dir($viewCachePath)) {
        foreach (glob($viewCachePath . '/*.php') ?: [] as $viewFile) {
            if (is_file($viewFile) && @unlink($viewFile)) {
                $deleted++;
            }
        }
    }

    return $deleted;
}

function system_checks(string $basePath, string $sqlFile): array
{
    $checks = [];

    $requiredExtensions = [
        'pdo',
        'pdo_mysql',
        'openssl',
        'mbstring',
        'tokenizer',
        'xml',
        'ctype',
        'json',
        'fileinfo',
        'gd',
        'zip',
    ];

    foreach ($requiredExtensions as $extension) {
        $checks[] = [
            'label' => 'PHP Extension: ' . $extension,
            'ok' => extension_loaded($extension),
            'note' => extension_loaded($extension) ? 'Aktif' : 'Eksik',
        ];
    }

    $checks[] = [
        'label' => 'PHP Version',
        'ok' => version_compare(PHP_VERSION, '8.2.0', '>='),
        'note' => PHP_VERSION,
    ];

    $checks[] = [
        'label' => 'vendor/autoload.php',
        'ok' => file_exists($basePath . '/vendor/autoload.php'),
        'note' => file_exists($basePath . '/vendor/autoload.php') ? 'Var' : 'Yok',
    ];

    $checks[] = [
        'label' => '.env.example',
        'ok' => file_exists($basePath . '/.env.example'),
        'note' => file_exists($basePath . '/.env.example') ? 'Var' : 'Yok',
    ];

    $checks[] = [
        'label' => 'database/dump/itcraft_cms_base.sql',
        'ok' => file_exists($sqlFile),
        'note' => file_exists($sqlFile) ? 'Var' : 'Yok',
    ];

    $writablePaths = [
        $basePath . '/storage',
        $basePath . '/storage/framework',
        $basePath . '/storage/logs',
        $basePath . '/bootstrap/cache',
        dirname($basePath . '/.env'),
    ];

    foreach ($writablePaths as $path) {
        $checks[] = [
            'label' => 'Writable: ' . str_replace($basePath . '/', '', $path),
            'ok' => is_dir($path) ? is_writable($path) : is_writable(dirname($path)),
            'note' => is_dir($path) ? (is_writable($path) ? 'Yazılabilir' : 'Yazılamaz') : 'Klasör yok, üst dizin kontrol edildi',
        ];
    }

    return $checks;
}

function installation_locked(string $lockFile): bool
{
    return file_exists($lockFile);
}

$checks = system_checks($basePath, $sqlFile);
$hasFailedCheck = count(array_filter($checks, static fn ($check) => !$check['ok'])) > 0;

if (installation_locked($lockFile)) {
    render_page('locked', [
        'basePath' => $basePath,
        'lockFile' => $lockFile,
        'checks' => $checks,
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = [
        'app_name' => trim((string) ($_POST['app_name'] ?? 'ITCRAFT CMS')),
        'app_url' => trim((string) ($_POST['app_url'] ?? '')),
        'db_host' => trim((string) ($_POST['db_host'] ?? 'localhost')),
        'db_port' => trim((string) ($_POST['db_port'] ?? '3306')),
        'db_name' => trim((string) ($_POST['db_name'] ?? '')),
        'db_user' => trim((string) ($_POST['db_user'] ?? '')),
        'db_pass' => (string) ($_POST['db_pass'] ?? ''),
        'admin_name' => trim((string) ($_POST['admin_name'] ?? '')),
        'admin_email' => trim((string) ($_POST['admin_email'] ?? '')),
        'admin_password' => (string) ($_POST['admin_password'] ?? ''),
        'admin_password_confirmation' => (string) ($_POST['admin_password_confirmation'] ?? ''),
        'confirm_database_reset' => isset($_POST['confirm_database_reset']),
    ];

    if ($input['app_name'] === '') {
        $errors[] = 'Site adı boş olamaz.';
    }

    if ($input['app_url'] === '' || !filter_var($input['app_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Geçerli bir Site URL girilmelidir. Örnek: https://domain.com';
    }

    if ($input['db_name'] === '') {
        $errors[] = 'Veritabanı adı boş olamaz.';
    }

    if ($input['db_user'] === '') {
        $errors[] = 'Veritabanı kullanıcı adı boş olamaz.';
    }

    if ($input['admin_name'] === '') {
        $errors[] = 'Admin adı soyadı boş olamaz.';
    }

    if (!filter_var($input['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir admin e-posta adresi girilmelidir.';
    }

    if (strlen($input['admin_password']) < 8) {
        $errors[] = 'Admin şifresi en az 8 karakter olmalıdır.';
    }

    if ($input['admin_password'] !== $input['admin_password_confirmation']) {
        $errors[] = 'Admin şifreleri eşleşmiyor.';
    }

    if (!$input['confirm_database_reset']) {
        $errors[] = 'Veritabanı temiz kurulum onayı verilmelidir.';
    }

    if ($hasFailedCheck) {
        $errors[] = 'Sistem kontrollerinde eksik/hatalı alanlar var. Kuruluma başlamadan önce düzeltmelisin.';
    }

    if (!$errors) {
        try {
            $dsn = 'mysql:host=' . $input['db_host'] . ';port=' . $input['db_port'] . ';dbname=' . $input['db_name'] . ';charset=utf8mb4';

            $pdo = new PDO($dsn, $input['db_user'], $input['db_pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
            ]);

            $messages[] = 'Veritabanı bağlantısı başarılı.';

            $directories = make_directories([
                $basePath . '/storage',
                $basePath . '/storage/app',
                $basePath . '/storage/app/public',
                $basePath . '/storage/framework',
                $basePath . '/storage/framework/cache',
                $basePath . '/storage/framework/cache/data',
                $basePath . '/storage/framework/sessions',
                $basePath . '/storage/framework/views',
                $basePath . '/storage/logs',
                $basePath . '/bootstrap/cache',
                $publicPath . '/uploads',
                $publicPath . '/uploads/media',
            ]);

            foreach ($directories as $dir => $ok) {
                $messages[] = ($ok ? 'OK: ' : 'UYARI: ') . str_replace($basePath . '/', '', $dir);
            }

            $appKey = random_app_key();

            $envWritten = write_env_file($envFile, [
                'app_name' => $input['app_name'],
                'app_url' => $input['app_url'],
                'app_key' => $appKey,
                'db_host' => $input['db_host'],
                'db_port' => $input['db_port'],
                'db_name' => $input['db_name'],
                'db_user' => $input['db_user'],
                'db_pass' => $input['db_pass'],
            ]);

            if (!$envWritten) {
                throw new RuntimeException('.env dosyası yazılamadı.');
            }

            $messages[] = '.env dosyası oluşturuldu.';
            $messages[] = 'APP_KEY üretildi.';

            $importResult = import_sql_file($pdo, $sqlFile);
            $messages[] = 'Base SQL import edildi. Çalışan statement: ' . $importResult['executed'];

            create_admin_user($pdo, [
                'name' => $input['admin_name'],
                'email' => $input['admin_email'],
                'password' => $input['admin_password'],
            ]);

            $messages[] = 'İlk admin kullanıcısı oluşturuldu.';

            update_site_settings($pdo, [
                'app_name' => $input['app_name'],
                'app_url' => $input['app_url'],
            ]);

            $messages[] = 'Site ayarları güncellendi.';

            $messages[] = create_storage_link($basePath, $publicPath);

            $deletedCache = clear_laravel_cache_files($basePath);
            $messages[] = 'Cache dosyaları temizlendi: ' . $deletedCache;

            if (!is_dir(dirname($lockFile))) {
                @mkdir(dirname($lockFile), 0775, true);
            }

            $lockContent = "ITCRAFT CMS installed at " . date('Y-m-d H:i:s') . PHP_EOL;
            $lockContent .= "APP_URL=" . rtrim($input['app_url'], '/') . PHP_EOL;
            $lockContent .= "ADMIN_EMAIL=" . $input['admin_email'] . PHP_EOL;

            if (file_put_contents($lockFile, $lockContent) === false) {
                throw new RuntimeException('install.lock dosyası oluşturulamadı.');
            }

            $messages[] = 'Kurulum kilidi oluşturuldu: storage/install.lock';

            render_page('success', [
                'messages' => $messages,
                'adminUrl' => rtrim($input['app_url'], '/') . '/admin',
                'siteUrl' => rtrim($input['app_url'], '/'),
                'installPath' => $publicPath . '/install',
            ]);
            exit;
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }

    render_page('form', [
        'checks' => $checks,
        'errors' => $errors,
        'old' => $input,
        'basePath' => $basePath,
    ]);
    exit;
}

$defaultUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'example.com');

render_page('form', [
    'checks' => $checks,
    'errors' => [],
    'old' => [
        'app_name' => 'ITCRAFT CMS',
        'app_url' => $defaultUrl,
        'db_host' => 'localhost',
        'db_port' => '3306',
        'db_name' => '',
        'db_user' => '',
        'db_pass' => '',
        'admin_name' => '',
        'admin_email' => '',
        'admin_password' => '',
        'admin_password_confirmation' => '',
        'confirm_database_reset' => false,
    ],
    'basePath' => $basePath,
]);

function render_page(string $view, array $data = []): void
{
    extract($data);

    ?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>ITCRAFT CMS Kurulum</title>
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
        }

        .installer-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 34px 18px;
        }

        .installer-header {
            background: linear-gradient(135deg, #111827, #1d4ed8);
            color: #ffffff;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .20);
            margin-bottom: 22px;
        }

        .installer-header h1 {
            margin: 0 0 8px;
            font-size: 34px;
            letter-spacing: -0.06em;
        }

        .installer-header p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.6;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .08);
            margin-bottom: 18px;
        }

        .card h2 {
            margin: 0 0 14px;
            font-size: 22px;
            letter-spacing: -0.04em;
        }

        .checks-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .check-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 11px 12px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .check-item strong {
            font-size: 14px;
            color: #334155;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }

        .badge.ok {
            background: #dcfce7;
            color: #166534;
        }

        .badge.fail {
            background: #fee2e2;
            color: #991b1b;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 800;
            color: #334155;
            font-size: 14px;
        }

        input {
            width: 100%;
            min-height: 44px;
            border: 1px solid #cbd5e1;
            border-radius: 13px;
            padding: 10px 12px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .checkbox-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 15px;
            padding: 14px;
            line-height: 1.55;
            font-weight: 700;
        }

        .checkbox-row input {
            width: auto;
            min-height: auto;
            margin-top: 4px;
        }

        .btn {
            display: inline-flex;
            border: 0;
            min-height: 48px;
            padding: 12px 18px;
            border-radius: 14px;
            background: #1d4ed8;
            color: #fff;
            font-weight: 900;
            cursor: pointer;
            font-size: 15px;
            text-decoration: none;
            align-items: center;
            justify-content: center;
        }

        .btn:hover {
            background: #1e40af;
        }

        .alert {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 16px;
            line-height: 1.6;
            font-weight: 700;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-warning {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #fed7aa;
        }

        .success-list {
            margin: 0;
            padding-left: 20px;
            line-height: 1.8;
        }

        code {
            background: #f3f4f6;
            padding: 3px 6px;
            border-radius: 6px;
            word-break: break-word;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        @media (max-width: 820px) {
            .form-grid,
            .checks-grid {
                grid-template-columns: 1fr;
            }

            .installer-header h1 {
                font-size: 27px;
            }
        }
    </style>
</head>
<body>
<div class="installer-shell">
    <div class="installer-header">
        <h1>ITCRAFT CMS Kurulum</h1>
        <p>Bu sihirbaz ilk kurulum için .env, veritabanı, site ayarları ve ilk admin hesabını oluşturur.</p>
    </div>

    <?php if ($view === 'locked'): ?>
        <div class="card">
            <h2>Kurulum Zaten Tamamlanmış</h2>
            <div class="alert alert-warning">
                Bu sistemde <code>storage/install.lock</code> dosyası bulundu. Güvenlik için kurulum tekrar çalıştırılamaz.
                <br><br>
                Kurulum klasörünü sil:
                <br>
                <code>public/install/</code>
            </div>
        </div>
    <?php elseif ($view === 'success'): ?>
        <div class="card">
            <h2>Kurulum Tamamlandı</h2>

            <div class="alert alert-success">
                ITCRAFT CMS başarıyla kuruldu.
            </div>

            <ul class="success-list">
                <?php foreach (($messages ?? []) as $message): ?>
                    <li><?= h($message) ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="actions">
                <a class="btn" href="<?= h($adminUrl ?? '#') ?>">Admin Paneline Git</a>
                <a class="btn" href="<?= h($siteUrl ?? '#') ?>">Siteyi Aç</a>
            </div>
        </div>

        <div class="card">
            <h2>Güvenlik İçin Son Adım</h2>
            <div class="alert alert-warning">
                Şu klasörü mutlaka sil:
                <br>
                <code><?= h($installPath ?? 'public/install') ?></code>
                <br><br>
                Ayrıca GitHub release paketinde kurulumdan sonra <code>public/install</code> klasörünün erişilebilir kalmaması gerekir.
            </div>
        </div>
    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= h($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Sistem Kontrolü</h2>

            <div class="checks-grid">
                <?php foreach (($checks ?? []) as $check): ?>
                    <div class="check-item">
                        <strong><?= h($check['label']) ?></strong>
                        <span class="badge <?= $check['ok'] ? 'ok' : 'fail' ?>">
                            <?= h(human_status($check['ok'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <form method="post">
            <div class="card">
                <h2>Site Bilgileri</h2>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="app_name">Site Adı</label>
                        <input id="app_name" name="app_name" value="<?= h($old['app_name'] ?? 'ITCRAFT CMS') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="app_url">Site URL</label>
                        <input id="app_url" name="app_url" value="<?= h($old['app_url'] ?? '') ?>" placeholder="https://domain.com" required>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>Veritabanı Bilgileri</h2>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="db_host">DB Host</label>
                        <input id="db_host" name="db_host" value="<?= h($old['db_host'] ?? 'localhost') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="db_port">DB Port</label>
                        <input id="db_port" name="db_port" value="<?= h($old['db_port'] ?? '3306') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="db_name">DB Adı</label>
                        <input id="db_name" name="db_name" value="<?= h($old['db_name'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="db_user">DB Kullanıcı</label>
                        <input id="db_user" name="db_user" value="<?= h($old['db_user'] ?? '') ?>" required>
                    </div>

                    <div class="form-group full">
                        <label for="db_pass">DB Şifre</label>
                        <input id="db_pass" name="db_pass" type="password" value="<?= h($old['db_pass'] ?? '') ?>">
                    </div>

                    <div class="form-group full">
                        <label class="checkbox-row">
                            <input type="checkbox" name="confirm_database_reset" value="1" <?= !empty($old['confirm_database_reset']) ? 'checked' : '' ?>>
                            <span>
                                Bu veritabanının temiz kurulum için kullanılacağını onaylıyorum.
                                Base SQL import sırasında mevcut aynı isimli tablolar silinip yeniden oluşturulabilir.
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>İlk Admin Hesabı</h2>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="admin_name">Ad Soyad</label>
                        <input id="admin_name" name="admin_name" value="<?= h($old['admin_name'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="admin_email">E-posta</label>
                        <input id="admin_email" name="admin_email" type="email" value="<?= h($old['admin_email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="admin_password">Şifre</label>
                        <input id="admin_password" name="admin_password" type="password" required>
                    </div>

                    <div class="form-group">
                        <label for="admin_password_confirmation">Şifre Tekrar</label>
                        <input id="admin_password_confirmation" name="admin_password_confirmation" type="password" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn" onclick="return confirm('Kurulumu başlatmak istiyor musun? Veritabanına base SQL yüklenecek.');">
                Kurulumu Başlat
            </button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
<?php
}