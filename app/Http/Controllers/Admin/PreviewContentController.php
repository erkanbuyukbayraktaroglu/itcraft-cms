<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PreviewContentController extends Controller
{
    private array $types = [
        'pages' => [
            'table' => 'pages',
            'prefix' => '',
        ],
        'services' => [
            'table' => 'services',
            'prefix' => 'hizmetler',
        ],
        'posts' => [
            'table' => 'posts',
            'prefix' => 'blog',
        ],
    ];

    public function show(string $type, int $id): RedirectResponse
    {
        if (!isset($this->types[$type])) {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz içerik tipi.');
        }

        $config = $this->types[$type];
        $table = $config['table'];

        if (!Schema::hasTable($table)) {
            return redirect()
                ->back()
                ->with('error', 'İlgili tablo bulunamadı.');
        }

        if (!Schema::hasColumn($table, 'slug')) {
            return redirect()
                ->back()
                ->with('error', 'Bu içerik tipi için slug alanı bulunamadı.');
        }

        $record = DB::table($table)->where('id', $id)->first();

        if (!$record || empty($record->slug)) {
            return redirect()
                ->back()
                ->with('error', 'Önizlenecek kayıt bulunamadı.');
        }

        $slug = trim((string) $record->slug, '/');
        $prefix = trim((string) $config['prefix'], '/');

        $path = $prefix !== ''
            ? '/' . $prefix . '/' . $slug
            : '/' . $slug;

        return redirect()->to(url($path));
    }
}
