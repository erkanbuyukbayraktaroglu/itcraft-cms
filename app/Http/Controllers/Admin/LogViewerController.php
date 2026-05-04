<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class LogViewerController extends Controller
{
    public function index(Request $request): View
    {
        $logFiles = $this->logFiles();

        $selectedFile = $request->input('file');

        if (!$selectedFile || !array_key_exists($selectedFile, $logFiles)) {
            $selectedFile = array_key_first($logFiles);
        }

        $lineCount = (int) $request->input('lines', 500);

        if (!in_array($lineCount, [100, 200, 500, 1000, 2000, 5000], true)) {
            $lineCount = 500;
        }

        $level = strtoupper((string) $request->input('level', ''));

        if (!in_array($level, ['', 'ERROR', 'WARNING', 'INFO', 'DEBUG', 'CRITICAL', 'ALERT', 'EMERGENCY'], true)) {
            $level = '';
        }

        $content = '';
        $fileSize = null;
        $lastModified = null;
        $selectedPath = null;

        if ($selectedFile && isset($logFiles[$selectedFile])) {
            $selectedPath = $logFiles[$selectedFile]['path'];
            $fileSize = $this->humanSize((int) filesize($selectedPath));
            $lastModified = date('Y-m-d H:i:s', (int) filemtime($selectedPath));

            $content = $this->readLastLines($selectedPath, $lineCount);

            if ($level !== '') {
                $content = $this->filterByLevel($content, $level);
            }

            $content = $this->maskSensitive($content);
        }

        $summary = $this->summary($selectedPath);

        return view('admin.logs.index', [
            'logFiles' => $logFiles,
            'selectedFile' => $selectedFile,
            'content' => $content,
            'lineCount' => $lineCount,
            'level' => $level,
            'fileSize' => $fileSize,
            'lastModified' => $lastModified,
            'summary' => $summary,
        ]);
    }

    private function logFiles(): array
    {
        $path = storage_path('logs');

        if (!is_dir($path)) {
            return [];
        }

        $files = glob($path . '/*.log') ?: [];

        usort($files, function (string $a, string $b) {
            return filemtime($b) <=> filemtime($a);
        });

        $items = [];

        foreach ($files as $file) {
            if (!is_file($file) || !is_readable($file)) {
                continue;
            }

            $basename = basename($file);

            $items[$basename] = [
                'name' => $basename,
                'path' => $file,
                'size' => $this->humanSize((int) filesize($file)),
                'modified' => date('Y-m-d H:i:s', (int) filemtime($file)),
            ];
        }

        return $items;
    }

    private function readLastLines(string $filePath, int $lines): string
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return '';
        }

        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();

        $start = max(0, $lastLine - $lines);
        $content = [];

        $file->seek($start);

        while (!$file->eof()) {
            $content[] = (string) $file->current();
            $file->next();
        }

        return implode('', $content);
    }

    private function filterByLevel(string $content, string $level): string
    {
        if ($content === '') {
            return '';
        }

        $lines = explode("\n", $content);
        $filtered = [];
        $keepCurrentBlock = false;

        foreach ($lines as $line) {
            $isNewLogLine = preg_match('/^\[\d{4}-\d{2}-\d{2}/', $line) === 1;

            if ($isNewLogLine) {
                $keepCurrentBlock = str_contains(strtoupper($line), '.' . $level . ':')
                    || str_contains(strtoupper($line), ' ' . $level . ':')
                    || str_contains(strtoupper($line), $level . ':');
            }

            if ($keepCurrentBlock) {
                $filtered[] = $line;
            }
        }

        return implode("\n", $filtered);
    }

    private function summary(?string $filePath): array
    {
        $summary = [
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'debug' => 0,
            'critical' => 0,
        ];

        if (!$filePath || !is_file($filePath) || !is_readable($filePath)) {
            return $summary;
        }

        $content = $this->readLastLines($filePath, 5000);
        $upper = strtoupper($content);

        $summary['error'] = substr_count($upper, '.ERROR:') + substr_count($upper, ' ERROR:');
        $summary['warning'] = substr_count($upper, '.WARNING:') + substr_count($upper, ' WARNING:');
        $summary['info'] = substr_count($upper, '.INFO:') + substr_count($upper, ' INFO:');
        $summary['debug'] = substr_count($upper, '.DEBUG:') + substr_count($upper, ' DEBUG:');
        $summary['critical'] = substr_count($upper, '.CRITICAL:') + substr_count($upper, ' CRITICAL:');

        return $summary;
    }

    private function maskSensitive(string $content): string
    {
        $patterns = [
            '/APP_KEY=base64:[A-Za-z0-9+\/=]+/i' => 'APP_KEY=base64:***MASKED***',
            '/DB_PASSWORD=([^\s]+)/i' => 'DB_PASSWORD=***MASKED***',
            '/MAIL_PASSWORD=([^\s]+)/i' => 'MAIL_PASSWORD=***MASKED***',
            '/password["\']?\s*[:=]\s*["\']?([^"\',\s]+)/i' => 'password=***MASKED***',
            '/api[_-]?key["\']?\s*[:=]\s*["\']?([^"\',\s]+)/i' => 'api_key=***MASKED***',
            '/token["\']?\s*[:=]\s*["\']?([^"\',\s]+)/i' => 'token=***MASKED***',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content) ?? $content;
        }

        return $content;
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
