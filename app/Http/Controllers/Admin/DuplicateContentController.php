<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DuplicateContentController extends Controller
{
    private array $allowedTypes = [
        'pages' => [
            'table' => 'pages',
            'redirect' => 'admin.pages.index',
            'title_columns' => ['title', 'name'],
        ],
        'services' => [
            'table' => 'services',
            'redirect' => 'admin.services.index',
            'title_columns' => ['title', 'name'],
        ],
        'posts' => [
            'table' => 'posts',
            'redirect' => 'admin.posts.index',
            'title_columns' => ['title', 'name'],
        ],
    ];

    public function duplicate(string $type, int $id): RedirectResponse
    {
        if (!isset($this->allowedTypes[$type])) {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz içerik tipi.');
        }

        $config = $this->allowedTypes[$type];
        $table = $config['table'];

        if (!Schema::hasTable($table)) {
            return redirect()
                ->back()
                ->with('error', 'İlgili tablo bulunamadı.');
        }

        $record = DB::table($table)->where('id', $id)->first();

        if (!$record) {
            return redirect()
                ->back()
                ->with('error', 'Kopyalanacak kayıt bulunamadı.');
        }

        $data = (array) $record;

        unset($data['id']);

        $titleColumn = $this->findFirstColumn($table, $config['title_columns']);

        if ($titleColumn) {
            $originalTitle = trim((string) ($record->{$titleColumn} ?? ''));

            if ($originalTitle !== '') {
                $data[$titleColumn] = $this->makeCopyTitle($originalTitle);
            }
        }

        if (Schema::hasColumn($table, 'slug')) {
            $baseTitle = $titleColumn ? (string) ($data[$titleColumn] ?? '') : '';
            $baseSlug = $baseTitle !== ''
                ? Str::slug($this->turkishToAscii($baseTitle))
                : Str::slug($this->turkishToAscii((string) ($record->slug ?? 'icerik-kopya')));

            if ($baseSlug === '') {
                $baseSlug = 'icerik-kopya';
            }

            $data['slug'] = $this->uniqueSlug($table, $baseSlug);
        }

        if (Schema::hasColumn($table, 'is_active') && array_key_exists('is_active', $data)) {
            $data['is_active'] = 0;
        }

        if (Schema::hasColumn($table, 'status') && array_key_exists('status', $data)) {
            $currentStatus = strtolower((string) $data['status']);

            if (in_array($currentStatus, ['published', 'active', 'yayinda', 'yayında'], true)) {
                $data['status'] = 'draft';
            }
        }

        if (Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = now();
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $data['updated_at'] = now();
        }

        DB::table($table)->insert($data);

        return redirect()
            ->route($config['redirect'])
            ->with('success', 'Kayıt başarıyla kopyalandı.');
    }

    private function findFirstColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function makeCopyTitle(string $title): string
    {
        if (str_contains(mb_strtolower($title), 'kopya')) {
            return $title;
        }

        return $title . ' Kopya';
    }

    private function uniqueSlug(string $table, string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 2;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function turkishToAscii(string $value): string
    {
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

        return strtr($value, $map);
    }
}
