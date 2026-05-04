<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    /**
     * Site ayarlarını key-value mantığıyla döndürür.
     *
     * Örnek:
     * setting('site_name')
     * setting('primary_color', '#0f172a')
     */
    function setting(string $key, mixed $default = null): mixed
    {
        static $settings = null;

        if ($settings === null) {
            try {
                $settings = SiteSetting::query()
                    ->where('is_public', true)
                    ->pluck('setting_value', 'setting_key')
                    ->toArray();
            } catch (Throwable $e) {
                $settings = [];
            }
        }

        return $settings[$key] ?? $default;
    }
}

if (! function_exists('asset_upload')) {
    /**
     * public/uploads içindeki dosyalar için URL üretir.
     *
     * Örnek:
     * asset_upload('logo/logo.png')
     */
    function asset_upload(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        return asset('uploads/' . $path);
    }
}

if (! function_exists('seo_title')) {
    /**
     * Sayfa meta title üretir.
     */
    function seo_title(?string $title = null): string
    {
        $siteName = setting('site_name', config('app.name'));

        if (! empty($title)) {
            return $title . ' | ' . $siteName;
        }

        return setting('default_meta_title', $siteName);
    }
}

if (! function_exists('seo_description')) {
    /**
     * Sayfa meta description üretir.
     */
    function seo_description(?string $description = null): string
    {
        if (! empty($description)) {
            return $description;
        }

        return setting('default_meta_description', '');
    }
}

if (! function_exists('clean_phone_link')) {
    /**
     * Telefon numarasını tel: linkine uygun hale getirir.
     */
    function clean_phone_link(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        return preg_replace('/[^0-9+]/', '', $phone);
    }
}

if (! function_exists('active_menu')) {
    /**
     * Aktif menü class kontrolü.
     */
    function active_menu(string $url): string
    {
        $currentPath = '/' . request()->path();

        if ($currentPath === '//') {
            $currentPath = '/';
        }

        return $currentPath === $url ? 'active' : '';
    }
}