<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeoCheckController extends Controller
{
    public function index(Request $request): View
    {
        $type = (string) $request->input('type', '');
        $status = (string) $request->input('status', '');

        $items = collect($this->collectItems());

        if ($type !== '') {
            $items = $items->where('type_key', $type);
        }

        if ($status !== '') {
            $items = $items->where('status', $status);
        }

        $items = $items->sortBy('score')->values();

        $summary = [
            'total' => $items->count(),
            'good' => $items->where('status', 'good')->count(),
            'warning' => $items->where('status', 'warning')->count(),
            'danger' => $items->where('status', 'danger')->count(),
            'average' => $items->count() > 0 ? round($items->avg('score')) : 0,
        ];

        return view('admin.seo-checks.index', [
            'items' => $items,
            'summary' => $summary,
            'filters' => [
                'type' => $type,
                'status' => $status,
            ],
            'types' => $this->availableTypes(),
        ]);
    }

    private function collectItems(): array
    {
        $items = [];

        foreach ($this->sources() as $source) {
            if (!Schema::hasTable($source['table'])) {
                continue;
            }

            $rows = DB::table($source['table'])
                ->orderByDesc($this->safeOrderColumn($source['table']))
                ->limit(500)
                ->get();

            foreach ($rows as $row) {
                $items[] = $this->analyzeRow($source, (array) $row);
            }
        }

        return $items;
    }

    private function sources(): array
    {
        return [
            [
                'type_key' => 'pages',
                'type_label' => 'Sayfalar',
                'table' => 'pages',
                'admin_edit_base' => '/admin/pages',
            ],
            [
                'type_key' => 'services',
                'type_label' => 'Hizmetler',
                'table' => 'services',
                'admin_edit_base' => '/admin/services',
            ],
            [
                'type_key' => 'blog_posts',
                'type_label' => 'Blog Yazıları',
                'table' => 'blog_posts',
                'admin_edit_base' => '/admin/blog-posts',
            ],
            [
                'type_key' => 'blogs',
                'type_label' => 'Blog Yazıları',
                'table' => 'blogs',
                'admin_edit_base' => '/admin/blogs',
            ],
            [
                'type_key' => 'posts',
                'type_label' => 'Yazılar',
                'table' => 'posts',
                'admin_edit_base' => '/admin/posts',
            ],
        ];
    }

    private function availableTypes(): array
    {
        $types = [];

        foreach ($this->sources() as $source) {
            if (Schema::hasTable($source['table'])) {
                $types[$source['type_key']] = $source['type_label'];
            }
        }

        return $types;
    }

    private function analyzeRow(array $source, array $row): array
    {
        $title = $this->firstValue($row, ['title', 'name', 'page_title', 'heading']);
        $slug = $this->firstValue($row, ['slug', 'url_slug', 'permalink']);
        $metaTitle = $this->firstValue($row, ['meta_title', 'seo_title']);
        $metaDescription = $this->firstValue($row, ['meta_description', 'seo_description', 'description']);
        $content = $this->firstValue($row, ['content', 'body', 'description', 'short_description']);
        $image = $this->firstValue($row, ['image', 'featured_image', 'cover_image', 'thumbnail', 'photo']);
        $isActive = $this->firstValue($row, ['is_active', 'status', 'published', 'is_published']);

        $plainContent = trim(strip_tags((string) $content));
        $wordCount = str_word_count($plainContent);
        $issues = [];
        $success = [];
        $score = 100;

        if (trim((string) $title) === '') {
            $score -= 20;
            $issues[] = 'Başlık boş görünüyor.';
        } elseif (mb_strlen((string) $title) < 10) {
            $score -= 8;
            $issues[] = 'Başlık çok kısa.';
        } else {
            $success[] = 'Başlık mevcut.';
        }

        if (trim((string) $slug) === '') {
            $score -= 18;
            $issues[] = 'Slug boş.';
        } elseif (!preg_match('/^[a-z0-9\-\/]+$/', (string) $slug)) {
            $score -= 8;
            $issues[] = 'Slug içinde Türkçe karakter, boşluk veya özel karakter olabilir.';
        } else {
            $success[] = 'Slug temiz görünüyor.';
        }

        if (trim((string) $metaTitle) === '') {
            $score -= 16;
            $issues[] = 'Meta title boş.';
        } else {
            $metaTitleLength = mb_strlen((string) $metaTitle);

            if ($metaTitleLength < 30) {
                $score -= 6;
                $issues[] = 'Meta title kısa. Öneri: 30-60 karakter.';
            } elseif ($metaTitleLength > 65) {
                $score -= 6;
                $issues[] = 'Meta title uzun. Öneri: 30-60 karakter.';
            } else {
                $success[] = 'Meta title uzunluğu uygun.';
            }
        }

        if (trim((string) $metaDescription) === '') {
            $score -= 18;
            $issues[] = 'Meta description boş.';
        } else {
            $metaDescriptionLength = mb_strlen((string) $metaDescription);

            if ($metaDescriptionLength < 80) {
                $score -= 7;
                $issues[] = 'Meta description kısa. Öneri: 120-160 karakter.';
            } elseif ($metaDescriptionLength > 170) {
                $score -= 7;
                $issues[] = 'Meta description uzun. Öneri: 120-160 karakter.';
            } else {
                $success[] = 'Meta description uzunluğu uygun.';
            }
        }

        if ($wordCount < 80) {
            $score -= 12;
            $issues[] = 'İçerik kısa görünüyor. Daha açıklayıcı içerik önerilir.';
        } else {
            $success[] = 'İçerik uzunluğu kabul edilebilir.';
        }

        if (trim((string) $image) === '') {
            $score -= 8;
            $issues[] = 'Kapak/öne çıkan görsel bulunamadı.';
        } else {
            $success[] = 'Görsel mevcut.';
        }

        $score = max(0, min(100, $score));

        if ($score >= 80) {
            $status = 'good';
            $statusLabel = 'İyi';
        } elseif ($score >= 55) {
            $status = 'warning';
            $statusLabel = 'Geliştirilmeli';
        } else {
            $status = 'danger';
            $statusLabel = 'Kritik';
        }

        $id = $row['id'] ?? null;

        return [
            'id' => $id,
            'type_key' => $source['type_key'],
            'type_label' => $source['type_label'],
            'table' => $source['table'],
            'title' => $title ?: '(Başlıksız içerik)',
            'slug' => $slug,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_title_length' => mb_strlen((string) $metaTitle),
            'meta_description_length' => mb_strlen((string) $metaDescription),
            'word_count' => $wordCount,
            'image' => $image,
            'score' => $score,
            'status' => $status,
            'status_label' => $statusLabel,
            'issues' => $issues,
            'success' => $success,
            'edit_url' => $id ? url($source['admin_edit_base'] . '/' . $id . '/edit') : null,
            'public_url' => $slug ? url('/' . ltrim((string) $slug, '/')) : null,
            'is_active' => $isActive,
        ];
    }

    private function firstValue(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return null;
    }

    private function safeOrderColumn(string $table): string
    {
        foreach (['updated_at', 'created_at', 'id'] as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return 'id';
    }
}
