<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MediaController extends Controller
{
    private int $maxImageWidth = 1920;
    private int $maxImageHeight = 1920;
    private int $jpegQuality = 84;
    private int $webpQuality = 82;
    private int $pngCompression = 6;

    private array $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'gif',
        'svg',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
    ];

    public function index(): View
    {
        $files = $this->listMediaFiles();

        return view('admin.media.index', compact('files'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'media_file' => ['required', 'file', 'max:15360'],
        ], [
            'media_file.required' => 'Lütfen bir dosya seçin.',
            'media_file.file' => 'Yüklenen içerik geçerli bir dosya olmalıdır.',
            'media_file.max' => 'Dosya boyutu en fazla 15 MB olabilir.',
        ]);

        $result = $this->handleUpload($request);

        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', $result['message'])
            ->with('uploaded_path', $result['path']);
    }

    public function picker(Request $request): View
    {
        $files = $this->listMediaFiles();
        $selected = $request->query('selected');

        return view('admin.media.picker', compact('files', 'selected'));
    }

    public function uploadOptimized(Request $request): RedirectResponse
    {
        $request->validate([
            'media_file' => ['required', 'file', 'max:15360'],
        ], [
            'media_file.required' => 'Lütfen bir dosya seçin.',
            'media_file.file' => 'Yüklenen içerik geçerli bir dosya olmalıdır.',
            'media_file.max' => 'Dosya boyutu en fazla 15 MB olabilir.',
        ]);

        $result = $this->handleUpload($request);

        if (!$result['success']) {
            return redirect()
                ->route('admin.media.picker')
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('admin.media.picker', ['selected' => $result['path']])
            ->with('success', $result['message']);
    }

    public function delete(Request $request): RedirectResponse
    {
        $path = trim((string) $request->input('path', ''));

        if ($path === '' || !str_starts_with($path, 'uploads/media/')) {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz dosya yolu.');
        }

        $fullPath = public_path($path);

        if (!File::exists($fullPath) || !is_file($fullPath)) {
            return redirect()
                ->back()
                ->with('error', 'Dosya bulunamadı.');
        }

        File::delete($fullPath);

        return redirect()
            ->back()
            ->with('success', 'Dosya silindi.');
    }

    private function handleUpload(Request $request): array
    {
        $file = $request->file('media_file');

        if (!$file || !$file->isValid()) {
            return [
                'success' => false,
                'message' => 'Dosya yüklenemedi.',
                'path' => null,
            ];
        }

        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $this->allowedExtensions, true)) {
            return [
                'success' => false,
                'message' => 'Bu dosya türüne izin verilmiyor.',
                'path' => null,
            ];
        }

        $uploadDir = public_path('uploads/media/' . date('Y/m'));

        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->safeFileName($originalName);
        $fileName = date('His') . '-' . substr(md5((string) microtime(true)), 0, 8) . '-' . $safeName . '.' . $extension;
        $targetPath = $uploadDir . '/' . $fileName;

        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);

        $originalSize = $file->getSize();

        if ($isImage && extension_loaded('gd')) {
            $optimized = $this->optimizeImage($file->getRealPath(), $targetPath, $extension);

            if (!$optimized) {
                $file->move($uploadDir, $fileName);
            }
        } else {
            $file->move($uploadDir, $fileName);
        }

        $relativePath = 'uploads/media/' . date('Y/m') . '/' . $fileName;
        $finalSize = file_exists($targetPath) ? filesize($targetPath) : null;

        $message = 'Dosya başarıyla yüklendi.';

        if ($isImage && $finalSize !== null && $originalSize > 0) {
            $saving = max(0, 100 - (($finalSize / $originalSize) * 100));
            $message .= ' Optimize edildi. Yaklaşık küçülme: %' . round($saving, 1) . '.';
        }

        return [
            'success' => true,
            'message' => $message,
            'path' => $relativePath,
        ];
    }

    private function optimizeImage(string $sourcePath, string $targetPath, string $extension): bool
    {
        $info = @getimagesize($sourcePath);

        if (!$info || empty($info[0]) || empty($info[1])) {
            return false;
        }

        $width = (int) $info[0];
        $height = (int) $info[1];

        $newWidth = $width;
        $newHeight = $height;

        if ($width > $this->maxImageWidth || $height > $this->maxImageHeight) {
            $ratio = min($this->maxImageWidth / $width, $this->maxImageHeight / $height);
            $newWidth = (int) floor($width * $ratio);
            $newHeight = (int) floor($height * $ratio);
        }

        $source = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
            'png' => @imagecreatefrompng($sourcePath),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (!$source) {
            return false;
        }

        if ($newWidth !== $width || $newHeight !== $height) {
            $canvas = imagecreatetruecolor($newWidth, $newHeight);

            if (in_array($extension, ['png', 'webp'], true)) {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $canvas;
        }

        $saved = false;

        if (in_array($extension, ['jpg', 'jpeg'], true)) {
            imageinterlace($source, true);
            $saved = imagejpeg($source, $targetPath, $this->jpegQuality);
        }

        if ($extension === 'png') {
            $saved = imagepng($source, $targetPath, $this->pngCompression);
        }

        if ($extension === 'webp' && function_exists('imagewebp')) {
            $saved = imagewebp($source, $targetPath, $this->webpQuality);
        }

        imagedestroy($source);

        return $saved && file_exists($targetPath);
    }

    private function listMediaFiles(): array
    {
        $root = public_path('uploads/media');

        if (!File::isDirectory($root)) {
            File::makeDirectory($root, 0755, true);
        }

        $items = [];

        foreach (File::allFiles($root) as $file) {
            $relative = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
            $extension = strtolower($file->getExtension());

            $items[] = [
                'name' => $file->getFilename(),
                'path' => $relative,
                'url' => asset($relative),
                'extension' => $extension,
                'is_image' => in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true),
                'size' => $this->humanFileSize($file->getSize()),
                'modified' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($b['modified'], $a['modified']);
        });

        return $items;
    }

    private function safeFileName(string $name): string
    {
        $name = trim($name);

        $map = [
            'ş' => 's',
            'Ş' => 's',
            'ı' => 'i',
            'İ' => 'i',
            'ğ' => 'g',
            'Ğ' => 'g',
            'ü' => 'u',
            'Ü' => 'u',
            'ö' => 'o',
            'Ö' => 'o',
            'ç' => 'c',
            'Ç' => 'c',
        ];

        $name = strtr($name, $map);
        $name = strtolower($name);
        $name = preg_replace('/[^a-z0-9]+/i', '-', $name) ?: 'file';
        $name = trim($name, '-');

        return $name !== '' ? $name : 'file';
    }

    private function humanFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        }

        if ($bytes < 1073741824) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        return round($bytes / 1073741824, 2) . ' GB';
    }
}
