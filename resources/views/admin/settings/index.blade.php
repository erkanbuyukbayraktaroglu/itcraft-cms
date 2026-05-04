@extends('admin.layouts.app')

@section('title', 'Site Ayarları')
@section('topbar_title', 'Site Ayarları')

@section('content')
@php
    $getSetting = function (string $key, string $default = '') use ($settings) {
        return old($key, $settings[$key] ?? $default);
    };
@endphp

<div class="page-title">
    <h1>Site Ayarları</h1>
    <p>Genel bilgiler, logo, renkler, SEO, sosyal medya ve SMTP ayarlarını buradan yönetebilirsin.</p>
</div>

<form method="post" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="settings-grid">
        <div class="card">
            <h2>Genel Bilgiler</h2>

            <div class="form-grid">
                <div>
                    <label for="site_name">Site Adı</label>
                    <input class="form-control" type="text" id="site_name" name="site_name" value="{{ $getSetting('site_name') }}">
                </div>

                <div>
                    <label for="site_slogan">Site Sloganı</label>
                    <input class="form-control" type="text" id="site_slogan" name="site_slogan" value="{{ $getSetting('site_slogan') }}">
                </div>

                <div>
                    <label for="site_description">Site Açıklaması</label>
                    <textarea class="form-control" id="site_description" name="site_description" rows="4">{{ $getSetting('site_description') }}</textarea>
                </div>

                <div>
                    <label for="logo">Genel Logo Path/URL</label>
                    <input class="form-control" type="text" id="logo" name="logo" value="{{ $getSetting('logo') }}" placeholder="uploads/logo/logo.png">
                </div>

                <div>
                    <label for="header_logo">Header Logo Path/URL</label>
                    <input class="form-control" type="text" id="header_logo" name="header_logo" value="{{ $getSetting('header_logo') }}" placeholder="uploads/logo/header-logo.png">
                </div>

                <div>
                    <label for="footer_logo">Footer Logo Path/URL</label>
                    <input class="form-control" type="text" id="footer_logo" name="footer_logo" value="{{ $getSetting('footer_logo') }}" placeholder="uploads/logo/footer-logo.png">
                </div>

                <div>
                    <label for="favicon">Favicon Path/URL</label>
                    <input class="form-control" type="text" id="favicon" name="favicon" value="{{ $getSetting('favicon') }}" placeholder="uploads/logo/favicon.png">
                </div>
            </div>
        </div>

        <div class="card">
            <h2>İletişim Bilgileri</h2>

            <div class="form-grid">
                <div>
                    <label for="email">E-posta</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ $getSetting('email') }}">
                </div>

                <div>
                    <label for="phone">Telefon</label>
                    <input class="form-control" type="text" id="phone" name="phone" value="{{ $getSetting('phone') }}">
                </div>

                <div>
                    <label for="address">Adres</label>
                    <textarea class="form-control" id="address" name="address" rows="4">{{ $getSetting('address') }}</textarea>
                </div>

                <div>
                    <label for="working_hours">Çalışma Saatleri</label>
                    <input class="form-control" type="text" id="working_hours" name="working_hours" value="{{ $getSetting('working_hours') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>SMTP / E-posta Gönderim Ayarları</h2>
        <p style="color:#6b7280; margin-top:-6px;">
            İletişim formu gönderildiğinde belirlediğin e-posta adresine bildirim gitmesi için bu alanları doldur.
        </p>

        <div class="checkbox-row" style="margin-bottom:18px;">
            <label>
                <input type="checkbox" name="mail_enabled" value="1" {{ $getSetting('mail_enabled', '0') == '1' ? 'checked' : '' }}>
                SMTP mail gönderimini aktif et
            </label>
        </div>

        <div class="form-grid form-grid-2">
            <div>
                <label for="mail_host">SMTP Host</label>
                <input class="form-control" type="text" id="mail_host" name="mail_host" value="{{ $getSetting('mail_host') }}" placeholder="mail.domain.com veya smtp.gmail.com">
            </div>

            <div>
                <label for="mail_port">SMTP Port</label>
                <input class="form-control" type="text" id="mail_port" name="mail_port" value="{{ $getSetting('mail_port', '587') }}" placeholder="587">
            </div>

            <div>
                <label for="mail_username">SMTP Kullanıcı Adı</label>
                <input class="form-control" type="text" id="mail_username" name="mail_username" value="{{ $getSetting('mail_username') }}" autocomplete="off">
            </div>

            <div>
                <label for="mail_password">SMTP Şifre</label>
                <input class="form-control" type="password" id="mail_password" name="mail_password" value="" autocomplete="new-password" placeholder="Değiştirmeyeceksen boş bırak">
                <small style="display:block; color:#6b7280; margin-top:6px;">Boş bırakırsan kayıtlı şifre korunur.</small>
            </div>

            <div>
                <label for="mail_encryption">Şifreleme</label>
                <select class="form-control" id="mail_encryption" name="mail_encryption">
                    @php $encryption = $getSetting('mail_encryption', 'tls'); @endphp
                    <option value="" {{ $encryption === '' ? 'selected' : '' }}>Yok</option>
                    <option value="tls" {{ $encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ $encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>

            <div>
                <label for="contact_recipient_email">Form Bildirimi Gidecek E-posta</label>
                <input class="form-control" type="email" id="contact_recipient_email" name="contact_recipient_email" value="{{ $getSetting('contact_recipient_email') }}" placeholder="info@domain.com">
            </div>

            <div>
                <label for="mail_from_address">Gönderen E-posta</label>
                <input class="form-control" type="email" id="mail_from_address" name="mail_from_address" value="{{ $getSetting('mail_from_address') }}" placeholder="noreply@domain.com">
            </div>

            <div>
                <label for="mail_from_name">Gönderen Adı</label>
                <input class="form-control" type="text" id="mail_from_name" name="mail_from_name" value="{{ $getSetting('mail_from_name') }}" placeholder="{{ $getSetting('site_name', 'Web Sitesi') }}">
            </div>
        </div>

        <div style="background:#f9fafb; border:1px solid #e5e7eb; padding:14px 16px; border-radius:14px; margin-top:18px; color:#4b5563; line-height:1.6;">
            <strong>Not:</strong> cPanel mail kullanıyorsan genelde SMTP host <code>mail.domain.com</code>, port <code>587</code>, şifreleme <code>TLS</code> olur.
            Gmail kullanacaksan normal şifre değil, uygulama şifresi gerekir.
        </div>
    </div>

    <div class="settings-grid">
        <div class="card">
            <h2>Renk Ayarları</h2>

            <div class="form-grid form-grid-2">
                @php
                    $colors = [
                        'primary_color' => 'Ana Renk',
                        'secondary_color' => 'İkincil Renk',
                        'accent_color' => 'Vurgu Rengi',
                        'background_color' => 'Arka Plan Rengi',
                        'text_color' => 'Yazı Rengi',
                        'header_color' => 'Header Rengi',
                        'footer_color' => 'Footer Rengi',
                        'button_color' => 'Buton Rengi',
                    ];
                @endphp

                @foreach($colors as $key => $label)
                    <div>
                        <label for="{{ $key }}">{{ $label }}</label>
                        <div style="display:flex; gap:8px;">
                            <input type="color" value="{{ $getSetting($key, '#111827') }}" oninput="document.getElementById('{{ $key }}').value=this.value">
                            <input class="form-control" type="text" id="{{ $key }}" name="{{ $key }}" value="{{ $getSetting($key, '#111827') }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <h2>SEO Ayarları</h2>

            <div class="form-grid">
                <div>
                    <label for="default_meta_title">Varsayılan Meta Title</label>
                    <input class="form-control" type="text" id="default_meta_title" name="default_meta_title" value="{{ $getSetting('default_meta_title') }}">
                </div>

                <div>
                    <label for="default_meta_description">Varsayılan Meta Description</label>
                    <textarea class="form-control" id="default_meta_description" name="default_meta_description" rows="4">{{ $getSetting('default_meta_description') }}</textarea>
                </div>

                <div>
                    <label for="default_meta_keywords">Varsayılan Meta Keywords</label>
                    <input class="form-control" type="text" id="default_meta_keywords" name="default_meta_keywords" value="{{ $getSetting('default_meta_keywords') }}">
                </div>

                <div>
                    <label for="default_og_image">Varsayılan OG Image Path/URL</label>
                    <input class="form-control" type="text" id="default_og_image" name="default_og_image" value="{{ $getSetting('default_og_image') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Sosyal Medya</h2>

        <div class="form-grid form-grid-2">
            <div>
                <label for="linkedin_url">LinkedIn</label>
                <input class="form-control" type="url" id="linkedin_url" name="linkedin_url" value="{{ $getSetting('linkedin_url') }}">
            </div>

            <div>
                <label for="instagram_url">Instagram</label>
                <input class="form-control" type="url" id="instagram_url" name="instagram_url" value="{{ $getSetting('instagram_url') }}">
            </div>

            <div>
                <label for="facebook_url">Facebook</label>
                <input class="form-control" type="url" id="facebook_url" name="facebook_url" value="{{ $getSetting('facebook_url') }}">
            </div>

            <div>
                <label for="x_url">X</label>
                <input class="form-control" type="url" id="x_url" name="x_url" value="{{ $getSetting('x_url') }}">
            </div>

            <div>
                <label for="youtube_url">YouTube</label>
                <input class="form-control" type="url" id="youtube_url" name="youtube_url" value="{{ $getSetting('youtube_url') }}">
            </div>
        </div>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:18px;">
        <button type="submit" class="btn">Ayarları Kaydet</button>
    </div>
</form>
@endsection