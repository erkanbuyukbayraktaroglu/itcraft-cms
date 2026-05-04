<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ThemePresetController extends Controller
{
    private string $table = 'site_settings';

    public function index(): View
    {
        abort_if(!Schema::hasTable($this->table), 500, 'site_settings tablosu bulunamadı.');

        $settings = DB::table($this->table)->orderBy('id')->first();

        return view('admin.theme-presets.index', [
            'presets' => $this->presets(),
            'settings' => $settings,
        ]);
    }

    public function apply(Request $request): RedirectResponse
    {
        abort_if(!Schema::hasTable($this->table), 500, 'site_settings tablosu bulunamadı.');

        $request->validate([
            'preset' => ['required', 'string'],
        ]);

        $presets = $this->presets();
        $key = $request->input('preset');

        if (!isset($presets[$key])) {
            return redirect()
                ->route('admin.theme-presets.index')
                ->with('error', 'Seçilen tema preseti bulunamadı.');
        }

        $data = [];

        foreach ($presets[$key]['values'] as $column => $value) {
            if (Schema::hasColumn($this->table, $column)) {
                $data[$column] = $value;
            }
        }

        if (Schema::hasColumn($this->table, 'updated_at')) {
            $data['updated_at'] = now();
        }

        $row = DB::table($this->table)->orderBy('id')->first();

        if ($row) {
            DB::table($this->table)->where('id', $row->id)->update($data);
        } else {
            if (Schema::hasColumn($this->table, 'created_at')) {
                $data['created_at'] = now();
            }

            DB::table($this->table)->insert($data);
        }

        $this->clearViewCache();

        return redirect()
            ->route('admin.theme-presets.index')
            ->with('success', $presets[$key]['name'] . ' teması uygulandı.');
    }

    private function presets(): array
    {
        return [
            'law-bordeaux' => [
                'name' => 'Hukuk & Danışmanlık',
                'sector' => 'Hukuk Bürosu / Danışmanlık',
                'description' => 'Bordo, lacivert ve altın vurgularla ciddi, güven veren ve premium bir görünüm.',
                'badge' => 'Hukuk',
                'values' => [
                    'theme_primary_color' => '#7f1d1d',
                    'theme_secondary_color' => '#b45309',
                    'theme_accent_color' => '#d97706',
                    'theme_button_color' => '#7f1d1d',
                    'theme_button_hover_color' => '#450a0a',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#111827',
                    'theme_body_bg' => '#ffffff',
                    'theme_heading_color' => '#111827',
                    'theme_text_color' => '#374151',
                    'theme_link_color' => '#7f1d1d',
                    'theme_font_family' => 'Georgia, serif',
                    'theme_button_radius' => '8px',
                    'theme_card_radius' => '14px',
                    'theme_container_width' => '1180px',
                    'theme_shadow_level' => 'medium',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#d1d5db',
                    'theme_footer_link_color' => '#f3f4f6',
                    'theme_footer_link_hover_color' => '#fbbf24',
                    'theme_footer_border_color' => '#374151',
                    'theme_footer_social_color' => '#fbbf24',
                                    'theme_slider_badge_bg' => '#450a0a',
                    'theme_slider_badge_text' => '#ffffff',
                    'theme_slider_badge_dot' => '#d97706',
                    'theme_slider_dot_active' => '#d97706',
                    'theme_slider_dot_passive' => '#9ca3af',
                    'theme_slider_accent_color' => '#d97706',
],
            ],

            'corporate-tech-blue' => [
                'name' => 'Kurumsal Teknoloji',
                'sector' => 'IT / Yazılım / Teknoloji',
                'description' => 'Mavi ve cyan tonlarıyla modern, güvenilir ve teknoloji odaklı kurumsal görünüm.',
                'badge' => 'Teknoloji',
                'values' => [
                    'theme_primary_color' => '#1d4ed8',
                    'theme_secondary_color' => '#0ea5e9',
                    'theme_accent_color' => '#06b6d4',
                    'theme_button_color' => '#2563eb',
                    'theme_button_hover_color' => '#1e40af',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#0f172a',
                    'theme_body_bg' => '#f8fafc',
                    'theme_heading_color' => '#0f172a',
                    'theme_text_color' => '#334155',
                    'theme_link_color' => '#2563eb',
                    'theme_font_family' => 'Inter, Arial, sans-serif',
                    'theme_button_radius' => '12px',
                    'theme_card_radius' => '20px',
                    'theme_container_width' => '1200px',
                    'theme_shadow_level' => 'medium',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#cbd5e1',
                    'theme_footer_link_color' => '#e2e8f0',
                    'theme_footer_link_hover_color' => '#38bdf8',
                    'theme_footer_border_color' => '#1e293b',
                    'theme_footer_social_color' => '#38bdf8',
                ],
            ],

            'health-clinic-green' => [
                'name' => 'Sağlık & Klinik',
                'sector' => 'Klinik / Sağlık / Diyetisyen',
                'description' => 'Yeşil ve turkuaz tonlarıyla temiz, ferah, sağlıklı ve güven verici görünüm.',
                'badge' => 'Sağlık',
                'values' => [
                    'theme_primary_color' => '#047857',
                    'theme_secondary_color' => '#14b8a6',
                    'theme_accent_color' => '#22c55e',
                    'theme_button_color' => '#059669',
                    'theme_button_hover_color' => '#047857',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#064e3b',
                    'theme_body_bg' => '#f0fdf4',
                    'theme_heading_color' => '#064e3b',
                    'theme_text_color' => '#365346',
                    'theme_link_color' => '#047857',
                    'theme_font_family' => 'Poppins, Arial, sans-serif',
                    'theme_button_radius' => '999px',
                    'theme_card_radius' => '24px',
                    'theme_container_width' => '1180px',
                    'theme_shadow_level' => 'soft',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#bbf7d0',
                    'theme_footer_link_color' => '#dcfce7',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#047857',
                    'theme_footer_social_color' => '#bbf7d0',
                ],
            ],

            'logistics-navy-orange' => [
                'name' => 'Lojistik & Taşımacılık',
                'sector' => 'Lojistik / Shipping / Taşımacılık',
                'description' => 'Lacivert ve turuncu kontrastıyla güçlü, operasyonel ve dinamik görünüm.',
                'badge' => 'Lojistik',
                'values' => [
                    'theme_primary_color' => '#0f172a',
                    'theme_secondary_color' => '#f97316',
                    'theme_accent_color' => '#fb923c',
                    'theme_button_color' => '#f97316',
                    'theme_button_hover_color' => '#ea580c',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#020617',
                    'theme_body_bg' => '#ffffff',
                    'theme_heading_color' => '#0f172a',
                    'theme_text_color' => '#334155',
                    'theme_link_color' => '#ea580c',
                    'theme_font_family' => 'Montserrat, Arial, sans-serif',
                    'theme_button_radius' => '10px',
                    'theme_card_radius' => '18px',
                    'theme_container_width' => '1240px',
                    'theme_shadow_level' => 'medium',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#cbd5e1',
                    'theme_footer_link_color' => '#fed7aa',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#1e293b',
                    'theme_footer_social_color' => '#fb923c',
                ],
            ],

            'education-purple-blue' => [
                'name' => 'Eğitim & Akademi',
                'sector' => 'Okul / Kurs / Akademi',
                'description' => 'Mor ve mavi tonlarıyla öğrenme, gelişim ve yaratıcı eğitim algısı.',
                'badge' => 'Eğitim',
                'values' => [
                    'theme_primary_color' => '#6d28d9',
                    'theme_secondary_color' => '#2563eb',
                    'theme_accent_color' => '#a855f7',
                    'theme_button_color' => '#7c3aed',
                    'theme_button_hover_color' => '#5b21b6',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#2e1065',
                    'theme_body_bg' => '#faf5ff',
                    'theme_heading_color' => '#1e1b4b',
                    'theme_text_color' => '#4c1d95',
                    'theme_link_color' => '#6d28d9',
                    'theme_font_family' => 'Poppins, Arial, sans-serif',
                    'theme_button_radius' => '16px',
                    'theme_card_radius' => '24px',
                    'theme_container_width' => '1180px',
                    'theme_shadow_level' => 'soft',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#ddd6fe',
                    'theme_footer_link_color' => '#ede9fe',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#5b21b6',
                    'theme_footer_social_color' => '#c4b5fd',
                ],
            ],

            'construction-architect-slate' => [
                'name' => 'İnşaat & Mimarlık',
                'sector' => 'İnşaat / Mimarlık / Gayrimenkul',
                'description' => 'Antrasit, beyaz ve sarı vurguyla güçlü, teknik ve prestijli görünüm.',
                'badge' => 'Mimarlık',
                'values' => [
                    'theme_primary_color' => '#334155',
                    'theme_secondary_color' => '#eab308',
                    'theme_accent_color' => '#facc15',
                    'theme_button_color' => '#334155',
                    'theme_button_hover_color' => '#0f172a',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#18181b',
                    'theme_body_bg' => '#f4f4f5',
                    'theme_heading_color' => '#18181b',
                    'theme_text_color' => '#3f3f46',
                    'theme_link_color' => '#ca8a04',
                    'theme_font_family' => 'Montserrat, Arial, sans-serif',
                    'theme_button_radius' => '6px',
                    'theme_card_radius' => '12px',
                    'theme_container_width' => '1260px',
                    'theme_shadow_level' => 'strong',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#d4d4d8',
                    'theme_footer_link_color' => '#fef08a',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#3f3f46',
                    'theme_footer_social_color' => '#facc15',
                ],
            ],

            'finance-green-gold' => [
                'name' => 'Finans & Muhasebe',
                'sector' => 'Finans / Muhasebe / Sigorta',
                'description' => 'Koyu yeşil ve altın tonlarıyla güven, istikrar ve finansal ciddiyet.',
                'badge' => 'Finans',
                'values' => [
                    'theme_primary_color' => '#14532d',
                    'theme_secondary_color' => '#ca8a04',
                    'theme_accent_color' => '#eab308',
                    'theme_button_color' => '#166534',
                    'theme_button_hover_color' => '#052e16',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#052e16',
                    'theme_body_bg' => '#f7fee7',
                    'theme_heading_color' => '#052e16',
                    'theme_text_color' => '#365314',
                    'theme_link_color' => '#166534',
                    'theme_font_family' => 'Inter, Arial, sans-serif',
                    'theme_button_radius' => '10px',
                    'theme_card_radius' => '16px',
                    'theme_container_width' => '1180px',
                    'theme_shadow_level' => 'medium',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#d9f99d',
                    'theme_footer_link_color' => '#fef08a',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#166534',
                    'theme_footer_social_color' => '#facc15',
                ],
            ],

            'restaurant-cafe-warm' => [
                'name' => 'Restoran & Cafe',
                'sector' => 'Restoran / Cafe / Gıda',
                'description' => 'Sıcak kahve, amber ve krem tonlarıyla samimi, iştah açıcı ve butik görünüm.',
                'badge' => 'Cafe',
                'values' => [
                    'theme_primary_color' => '#92400e',
                    'theme_secondary_color' => '#f97316',
                    'theme_accent_color' => '#fb923c',
                    'theme_button_color' => '#b45309',
                    'theme_button_hover_color' => '#78350f',
                    'theme_header_bg' => '#fffbeb',
                    'theme_footer_bg' => '#451a03',
                    'theme_body_bg' => '#fffbeb',
                    'theme_heading_color' => '#451a03',
                    'theme_text_color' => '#78350f',
                    'theme_link_color' => '#b45309',
                    'theme_font_family' => 'Georgia, serif',
                    'theme_button_radius' => '999px',
                    'theme_card_radius' => '24px',
                    'theme_container_width' => '1140px',
                    'theme_shadow_level' => 'soft',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#fde68a',
                    'theme_footer_link_color' => '#fed7aa',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#92400e',
                    'theme_footer_social_color' => '#fdba74',
                ],
            ],

            'beauty-aesthetic-rose' => [
                'name' => 'Güzellik & Estetik',
                'sector' => 'Güzellik / Estetik / Wellness',
                'description' => 'Rose, pembe ve şampanya tonlarıyla zarif, modern ve premium görünüm.',
                'badge' => 'Estetik',
                'values' => [
                    'theme_primary_color' => '#be185d',
                    'theme_secondary_color' => '#f9a8d4',
                    'theme_accent_color' => '#ec4899',
                    'theme_button_color' => '#be185d',
                    'theme_button_hover_color' => '#831843',
                    'theme_header_bg' => '#fff1f2',
                    'theme_footer_bg' => '#500724',
                    'theme_body_bg' => '#fff7f9',
                    'theme_heading_color' => '#831843',
                    'theme_text_color' => '#6b213f',
                    'theme_link_color' => '#be185d',
                    'theme_font_family' => 'Poppins, Arial, sans-serif',
                    'theme_button_radius' => '999px',
                    'theme_card_radius' => '28px',
                    'theme_container_width' => '1160px',
                    'theme_shadow_level' => 'soft',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#fbcfe8',
                    'theme_footer_link_color' => '#fce7f3',
                    'theme_footer_link_hover_color' => '#ffffff',
                    'theme_footer_border_color' => '#9d174d',
                    'theme_footer_social_color' => '#f9a8d4',
                ],
            ],

            'minimal-premium-black' => [
                'name' => 'Minimal Premium',
                'sector' => 'Premium Marka / Ajans / Portföy',
                'description' => 'Siyah, beyaz ve gold vurgularla sade, lüks ve zamansız görünüm.',
                'badge' => 'Premium',
                'values' => [
                    'theme_primary_color' => '#111827',
                    'theme_secondary_color' => '#b45309',
                    'theme_accent_color' => '#d97706',
                    'theme_button_color' => '#111827',
                    'theme_button_hover_color' => '#000000',
                    'theme_header_bg' => '#ffffff',
                    'theme_footer_bg' => '#000000',
                    'theme_body_bg' => '#ffffff',
                    'theme_heading_color' => '#000000',
                    'theme_text_color' => '#374151',
                    'theme_link_color' => '#111827',
                    'theme_font_family' => 'Inter, Arial, sans-serif',
                    'theme_button_radius' => '0px',
                    'theme_card_radius' => '8px',
                    'theme_container_width' => '1200px',
                    'theme_shadow_level' => 'none',
                    'theme_footer_heading_color' => '#ffffff',
                    'theme_footer_text_color' => '#d1d5db',
                    'theme_footer_link_color' => '#f5f5f5',
                    'theme_footer_link_hover_color' => '#fbbf24',
                    'theme_footer_border_color' => '#27272a',
                    'theme_footer_social_color' => '#fbbf24',
                ],
            ],
        ];
    }

    private function clearViewCache(): void
    {
        $viewPath = storage_path('framework/views');

        if (!is_dir($viewPath)) {
            return;
        }

        $files = glob($viewPath . '/*.php');

        if (!$files) {
            return;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}
