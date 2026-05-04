<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContentVersionController extends Controller
{
    private array $labels = [
        'pages' => 'Sayfalar',
        'services' => 'Hizmetler',
        'blog_posts' => 'Blog Yazıları',
        'blogs' => 'Blog Yazıları',
        'posts' => 'Yazılar',
    ];

    public function index(Request $request): View
    {
        abort_if(!Schema::hasTable('content_versions'), 500, 'content_versions tablosu bulunamadı.');

        $query = DB::table('content_versions');

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->input('table_name'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($search) {
                $q->where('record_title', 'like', $search)
                    ->orWhere('record_slug', 'like', $search)
                    ->orWhere('changed_by', 'like', $search);
            });
        }

        $versions = $query
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        $summary = [
            'total' => DB::table('content_versions')->count(),
            'today' => DB::table('content_versions')->where('created_at', '>=', now()->startOfDay())->count(),
            'pages' => DB::table('content_versions')->where('table_name', 'pages')->count(),
            'services' => DB::table('content_versions')->where('table_name', 'services')->count(),
            'blogs' => DB::table('content_versions')->whereIn('table_name', ['blog_posts', 'blogs', 'posts'])->count(),
        ];

        return view('admin.content-versions.index', [
            'versions' => $versions,
            'summary' => $summary,
            'labels' => $this->labels,
            'filters' => [
                'table_name' => $request->input('table_name', ''),
                'search' => $request->input('search', ''),
            ],
        ]);
    }

    public function show(int $id): View
    {
        abort_if(!Schema::hasTable('content_versions'), 500, 'content_versions tablosu bulunamadı.');

        $version = DB::table('content_versions')->where('id', $id)->first();

        abort_if(!$version, 404);

        $data = json_decode((string) $version->version_data, true) ?: [];

        return view('admin.content-versions.show', [
            'version' => $version,
            'data' => $data,
            'label' => $this->labels[$version->table_name] ?? $version->table_name,
            'currentExists' => Schema::hasTable($version->table_name)
                ? DB::table($version->table_name)->where('id', $version->record_id)->exists()
                : false,
        ]);
    }

    public function restore(int $id): RedirectResponse
    {
        abort_if(!Schema::hasTable('content_versions'), 500, 'content_versions tablosu bulunamadı.');

        $version = DB::table('content_versions')->where('id', $id)->first();

        if (!$version) {
            return redirect()
                ->route('admin.content-versions.index')
                ->with('error', 'Sürüm kaydı bulunamadı.');
        }

        if (!Schema::hasTable($version->table_name)) {
            return redirect()
                ->route('admin.content-versions.show', $id)
                ->with('error', 'İlgili tablo bulunamadı: ' . $version->table_name);
        }

        $data = json_decode((string) $version->version_data, true) ?: [];

        if (!$data || !isset($data['id'])) {
            return redirect()
                ->route('admin.content-versions.show', $id)
                ->with('error', 'Sürüm verisi okunamadı.');
        }

        $recordExists = DB::table($version->table_name)->where('id', $version->record_id)->exists();

        if (!$recordExists) {
            return redirect()
                ->route('admin.content-versions.show', $id)
                ->with('error', 'Geri yüklenecek mevcut kayıt bulunamadı.');
        }

        unset($data['id']);

        foreach (array_keys($data) as $column) {
            if (!Schema::hasColumn($version->table_name, $column)) {
                unset($data[$column]);
            }
        }

        if (Schema::hasColumn($version->table_name, 'updated_at')) {
            $data['updated_at'] = now();
        }

        DB::table($version->table_name)
            ->where('id', $version->record_id)
            ->update($data);

        return redirect()
            ->route('admin.content-versions.show', $id)
            ->with('success', 'Seçilen sürüm mevcut kayda geri yüklendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_if(!Schema::hasTable('content_versions'), 500, 'content_versions tablosu bulunamadı.');

        DB::table('content_versions')->where('id', $id)->delete();

        return redirect()
            ->route('admin.content-versions.index')
            ->with('success', 'Sürüm kaydı silindi.');
    }
}
