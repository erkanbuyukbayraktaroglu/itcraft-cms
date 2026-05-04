<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    private string $backupDirectory = 'app/backups';

    public function index(): View
    {
        $this->ensureBackupDirectory();

        return view('admin.backups.index', [
            'backups' => $this->backupFiles(),
            'stats' => $this->stats(),
            'zipAvailable' => class_exists(\ZipArchive::class),
        ]);
    }

    public function database(): RedirectResponse
    {
        $this->ensureBackupDirectory();

        try {
            $databaseName = (string) config('database.connections.mysql.database', 'database');
            $safeDatabaseName = Str::slug($databaseName) ?: 'database';

            $fileName = 'database-' . $safeDatabaseName . '-' . date('Ymd-His') . '.sql';
            $filePath = $this->backupPath($fileName);

            $this->createDatabaseDump($filePath);

            return redirect()
                ->route('admin.backups.index')
                ->with('success', 'Veritabanı yedeği oluşturuldu: ' . $fileName);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'Veritabanı yedeği alınamadı: ' . $exception->getMessage());
        }
    }

    public function uploads(): RedirectResponse
    {
        $this->ensureBackupDirectory();

        if (!class_exists(\ZipArchive::class)) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'ZIP yedeği alınamadı. Sunucuda ZipArchive PHP eklentisi aktif değil.');
        }

        $uploadsPath = public_path('uploads');

        if (!is_dir($uploadsPath)) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'public/uploads klasörü bulunamadı.');
        }

        try {
            $fileName = 'uploads-' . date('Ymd-His') . '.zip';
            $filePath = $this->backupPath($fileName);

            $this->zipDirectory($uploadsPath, $filePath, 'uploads');

            return redirect()
                ->route('admin.backups.index')
                ->with('success', 'Uploads yedeği oluşturuldu: ' . $fileName);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'Uploads yedeği alınamadı: ' . $exception->getMessage());
        }
    }

    public function download(string $file): BinaryFileResponse
    {
        $filePath = $this->safeBackupFilePath($file);

        abort_if(!$filePath || !is_file($filePath), 404);

        return response()->download($filePath);
    }

    public function destroy(Request $request, string $file): RedirectResponse
    {
        $filePath = $this->safeBackupFilePath($file);

        if (!$filePath || !is_file($filePath)) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'Yedek dosyası bulunamadı.');
        }

        @unlink($filePath);

        return redirect()
            ->route('admin.backups.index')
            ->with('success', 'Yedek dosyası silindi: ' . basename($filePath));
    }

    private function createDatabaseDump(string $filePath): void
    {
        $pdo = DB::connection()->getPdo();
        $databaseName = (string) config('database.connections.mysql.database');

        $handle = fopen($filePath, 'wb');

        if (!$handle) {
            throw new \RuntimeException('Yedek dosyası oluşturulamadı.');
        }

        fwrite($handle, "-- Database Backup\n");
        fwrite($handle, "-- Database: {$databaseName}\n");
        fwrite($handle, "-- Created At: " . date('Y-m-d H:i:s') . "\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
        fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

        $tables = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');

        foreach ($tables as $row) {
            $tableName = array_values((array) $row)[0];

            fwrite($handle, "\n-- --------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$tableName}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");

            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTableResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableRow = (array) $createTableResult[0];
            $createSql = $createTableRow['Create Table'] ?? array_values($createTableRow)[1] ?? '';

            fwrite($handle, $createSql . ";\n\n");

            fwrite($handle, "-- Data for `{$tableName}`\n\n");

            $rows = DB::table($tableName)->get();

            foreach ($rows as $dataRow) {
                $rowArray = (array) $dataRow;

                if (!$rowArray) {
                    continue;
                }

                $columns = array_map(function ($column) {
                    return '`' . str_replace('`', '``', (string) $column) . '`';
                }, array_keys($rowArray));

                $values = array_map(function ($value) use ($pdo) {
                    if ($value === null) {
                        return 'NULL';
                    }

                    if (is_bool($value)) {
                        return $value ? '1' : '0';
                    }

                    return $pdo->quote((string) $value);
                }, array_values($rowArray));

                fwrite(
                    $handle,
                    'INSERT INTO `' . $tableName . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ");\n"
                );
            }

            fwrite($handle, "\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");

        fclose($handle);
    }

    private function zipDirectory(string $sourcePath, string $zipPath, string $rootName = ''): void
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('ZIP dosyası oluşturulamadı.');
        }

        $sourcePath = realpath($sourcePath);

        if (!$sourcePath) {
            throw new \RuntimeException('Kaynak klasör okunamadı.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $filePath = $file->getRealPath();

            if (!$filePath) {
                continue;
            }

            $relativePath = ltrim(str_replace($sourcePath, '', $filePath), DIRECTORY_SEPARATOR);
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
            $zipRelativePath = $rootName !== '' ? $rootName . '/' . $relativePath : $relativePath;

            if ($file->isDir()) {
                $zip->addEmptyDir($zipRelativePath);
            } else {
                $zip->addFile($filePath, $zipRelativePath);
            }
        }

        $zip->close();
    }

    private function backupFiles(): array
    {
        $path = storage_path($this->backupDirectory);

        if (!is_dir($path)) {
            return [];
        }

        $files = glob($path . '/*.{sql,zip}', GLOB_BRACE) ?: [];

        usort($files, function (string $a, string $b) {
            return filemtime($b) <=> filemtime($a);
        });

        return array_map(function (string $file) {
            return [
                'name' => basename($file),
                'type' => str_ends_with($file, '.sql') ? 'Veritabanı' : 'Uploads',
                'extension' => strtoupper(pathinfo($file, PATHINFO_EXTENSION)),
                'size' => $this->humanSize((int) filesize($file)),
                'bytes' => (int) filesize($file),
                'created_at' => date('Y-m-d H:i:s', (int) filemtime($file)),
            ];
        }, $files);
    }

    private function stats(): array
    {
        $files = $this->backupFiles();

        $totalBytes = array_sum(array_column($files, 'bytes'));
        $databaseCount = count(array_filter($files, fn ($file) => $file['extension'] === 'SQL'));
        $uploadsCount = count(array_filter($files, fn ($file) => $file['extension'] === 'ZIP'));

        return [
            'total' => count($files),
            'database' => $databaseCount,
            'uploads' => $uploadsCount,
            'total_size' => $this->humanSize($totalBytes),
            'last_backup' => $files[0]['created_at'] ?? '-',
        ];
    }

    private function ensureBackupDirectory(): void
    {
        $path = storage_path($this->backupDirectory);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $htaccess = $path . '/.htaccess';

        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\n");
        }
    }

    private function backupPath(string $fileName): string
    {
        return storage_path($this->backupDirectory . '/' . basename($fileName));
    }

    private function safeBackupFilePath(string $file): ?string
    {
        $file = basename($file);

        if (!preg_match('/^[a-zA-Z0-9._-]+\.(sql|zip)$/', $file)) {
            return null;
        }

        $path = storage_path($this->backupDirectory . '/' . $file);
        $realBase = realpath(storage_path($this->backupDirectory));
        $realFile = realpath($path);

        if (!$realBase || !$realFile || !str_starts_with($realFile, $realBase)) {
            return null;
        }

        return $realFile;
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
