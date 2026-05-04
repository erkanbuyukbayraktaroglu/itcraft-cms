<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [];

        try {
            if (Schema::hasTable('site_settings')) {
                $keyColumn = Schema::hasColumn('site_settings', 'key') ? 'key' : 'setting_key';
                $valueColumn = Schema::hasColumn('site_settings', 'value') ? 'value' : 'setting_value';

                $rows = DB::table('site_settings')->get();

                foreach ($rows as $row) {
                    $settings[$row->{$keyColumn}] = $row->{$valueColumn};
                }
            }
        } catch (\Throwable $e) {
            $settings = [];
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            if (!Schema::hasTable('site_settings')) {
                return redirect()
                    ->route('admin.settings.index')
                    ->with('error', 'Ayar tablosu bulunamadı.');
            }

            $keyColumn = Schema::hasColumn('site_settings', 'key') ? 'key' : 'setting_key';
            $valueColumn = Schema::hasColumn('site_settings', 'value') ? 'value' : 'setting_value';

            $except = [
                '_token',
                '_method',
            ];

            $items = $request->except($except);

            /*
            |--------------------------------------------------------------------------
            | Checkbox alanları request içinde gelmezse kapalı kabul edilir.
            |--------------------------------------------------------------------------
            */

            $checkboxKeys = [
                'mail_enabled',
            ];

            foreach ($checkboxKeys as $checkboxKey) {
                if (!$request->has($checkboxKey)) {
                    $items[$checkboxKey] = '0';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SMTP şifresi boş gönderildiyse mevcut şifreyi koru.
            |--------------------------------------------------------------------------
            */

            if (array_key_exists('mail_password', $items) && trim((string) $items['mail_password']) === '') {
                unset($items['mail_password']);
            }

            foreach ($items as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }

                $value = $value === null ? '' : (string) $value;

                $exists = DB::table('site_settings')
                    ->where($keyColumn, $key)
                    ->exists();

                if ($exists) {
                    DB::table('site_settings')
                        ->where($keyColumn, $key)
                        ->update([
                            $valueColumn => $value,
                            'updated_at' => now(),
                        ]);
                } else {
                    $data = [
                        $keyColumn => $key,
                        $valueColumn => $value,
                    ];

                    if (Schema::hasColumn('site_settings', 'created_at')) {
                        $data['created_at'] = now();
                    }

                    if (Schema::hasColumn('site_settings', 'updated_at')) {
                        $data['updated_at'] = now();
                    }

                    DB::table('site_settings')->insert($data);
                }
            }

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Ayarlar başarıyla güncellendi.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Ayarlar güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
}
