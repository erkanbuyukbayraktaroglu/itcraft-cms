<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CaptureContentVersion
{
    private array $resources = [
        'pages' => [
            'table' => 'pages',
            'label' => 'Sayfalar',
        ],
        'services' => [
            'table' => 'services',
            'label' => 'Hizmetler',
        ],
        'blog-posts' => [
            'table' => 'blog_posts',
            'label' => 'Blog Yazıları',
        ],
        'blogs' => [
            'table' => 'blogs',
            'label' => 'Blog Yazıları',
        ],
        'posts' => [
            'table' => 'posts',
            'label' => 'Yazılar',
        ],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $this->captureBeforeUpdate($request);

        return $next($request);
    }

    private function captureBeforeUpdate(Request $request): void
    {
        try {
            if (!$this->shouldCapture($request)) {
                return;
            }

            if (!Schema::hasTable('content_versions')) {
                return;
            }

            [$resource, $recordId] = $this->detectResourceAndId($request);

            if (!$resource || !$recordId) {
                return;
            }

            $resourceConfig = $this->resources[$resource] ?? null;

            if (!$resourceConfig) {
                return;
            }

            $table = $resourceConfig['table'];

            if (!Schema::hasTable($table)) {
                return;
            }

            $row = DB::table($table)->where('id', $recordId)->first();

            if (!$row) {
                return;
            }

            $rowArray = (array) $row;

            $title = $this->firstValue($rowArray, ['title', 'name', 'page_title', 'heading']);
            $slug = $this->firstValue($rowArray, ['slug', 'url_slug', 'permalink']);

            /*
            |--------------------------------------------------------------------------
            | Aynı saniyede aynı kayıt için gereksiz çift kayıt oluşmasını azalt
            |--------------------------------------------------------------------------
            */

            $recentExists = DB::table('content_versions')
                ->where('table_name', $table)
                ->where('record_id', $recordId)
                ->where('created_at', '>=', now()->subSeconds(3))
                ->exists();

            if ($recentExists) {
                return;
            }

            DB::table('content_versions')->insert([
                'table_name' => $table,
                'record_id' => $recordId,
                'record_title' => $title ? mb_substr((string) $title, 0, 255) : null,
                'record_slug' => $slug ? mb_substr((string) $slug, 0, 255) : null,
                'version_data' => json_encode($rowArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'changed_by' => $this->currentUserLabel(),
                'ip_address' => mb_substr((string) $request->ip(), 0, 45),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Sürüm kaydı alınamasa bile içerik güncelleme işlemini bozma
            |--------------------------------------------------------------------------
            */
        }
    }

    private function shouldCapture(Request $request): bool
    {
        if (!$request->isMethod('POST') && !$request->isMethod('PUT') && !$request->isMethod('PATCH')) {
            return false;
        }

        if (!$request->is('admin/*')) {
            return false;
        }

        $path = trim($request->path(), '/');

        if (str_contains($path, 'content-versions')) {
            return false;
        }

        if (str_contains($path, 'login') || str_contains($path, 'logout')) {
            return false;
        }

        return true;
    }

    private function detectResourceAndId(Request $request): array
    {
        $segments = $request->segments();

        /*
        |--------------------------------------------------------------------------
        | Beklenen örnekler:
        | admin/pages/5
        | admin/pages/5/update
        | admin/blog-posts/3
        |--------------------------------------------------------------------------
        */

        if (($segments[0] ?? null) !== 'admin') {
            return [null, null];
        }

        $resource = $segments[1] ?? null;

        if (!$resource || !isset($this->resources[$resource])) {
            return [null, null];
        }

        $recordId = null;

        foreach ($segments as $segment) {
            if (ctype_digit((string) $segment)) {
                $recordId = (int) $segment;
                break;
            }
        }

        if (!$recordId) {
            $routeId = $request->route('id')
                ?: $request->route('page')
                ?: $request->route('service')
                ?: $request->route('blog_post')
                ?: $request->route('blog')
                ?: $request->route('post');

            if (is_object($routeId) && isset($routeId->id)) {
                $recordId = (int) $routeId->id;
            } elseif (is_numeric($routeId)) {
                $recordId = (int) $routeId;
            }
        }

        return [$resource, $recordId];
    }

    private function currentUserLabel(): ?string
    {
        try {
            if (function_exists('auth') && auth()->check()) {
                $user = auth()->user();

                return $user->email ?? $user->name ?? ('User #' . $user->id);
            }
        } catch (\Throwable $exception) {
            //
        }

        foreach (['admin_email', 'admin_username', 'admin_name', 'email', 'username'] as $key) {
            $value = session($key);

            if ($value) {
                return (string) $value;
            }
        }

        return null;
    }

    private function firstValue(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }

        return null;
    }
}
