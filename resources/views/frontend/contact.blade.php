@extends('frontend.layouts.app')

@section('title', seo_title('İletişim'))
@section('meta_description', 'Bizimle iletişime geçin.')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="eyebrow">İletişim</span>
        <h1>Bizimle İletişime Geçin</h1>
        <p>Sorularınız, talepleriniz ve iş birliği konuları için iletişim formunu doldurabilirsiniz.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-info-card">
                <h2>İletişim Bilgileri</h2>

                <div class="contact-info-item">
                    <strong>E-posta</strong>
                    <a href="mailto:{{ setting('email', 'info@example.com') }}">{{ setting('email', 'info@example.com') }}</a>
                </div>

                <div class="contact-info-item">
                    <strong>Telefon</strong>
                    <a href="tel:{{ clean_phone_link(setting('phone', '')) }}">{{ setting('phone', '-') }}</a>
                </div>

                <div class="contact-info-item">
                    <strong>Adres</strong>
                    <span>{{ setting('address', '-') }}</span>
                </div>

                <div class="contact-info-item">
                    <strong>Çalışma Saatleri</strong>
                    <span>{{ setting('working_hours', 'Hafta içi 09:00 - 18:00') }}</span>
                </div>
            </div>

            <div class="contact-form-card">
                <h2>İletişim Formu</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Lütfen form alanlarını kontrol edin.</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('contact.store') }}" class="contact-form">
                    @csrf

                    <div style="display:none;">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" value="">
                    </div>

                    <div class="form-grid form-grid-2">
                        <div>
                            <label for="name">Ad Soyad *</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autocomplete="name">
                            @error('name')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="email">E-posta *</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email">
                            @error('email')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid form-grid-2">
                        <div>
                            <label for="phone">Telefon</label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   autocomplete="tel">
                            @error('phone')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="subject">Konu</label>
                            <input type="text"
                                   id="subject"
                                   name="subject"
                                   value="{{ old('subject') }}"
                                   placeholder="Bilgi Talebi">
                            @error('subject')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message">Mesajınız *</label>
                        <textarea id="message"
                                  name="message"
                                  rows="7"
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-accent">Mesaj Gönder</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .contact-layout {
        display:grid;
        grid-template-columns: 0.9fr 1.4fr;
        gap:28px;
        align-items:start;
    }

    .contact-info-card,
    .contact-form-card {
        background:#ffffff;
        border:1px solid rgba(15, 23, 42, .08);
        border-radius:22px;
        padding:28px;
        box-shadow:0 18px 45px rgba(15, 23, 42, .06);
    }

    .contact-info-card h2,
    .contact-form-card h2 {
        margin-top:0;
    }

    .contact-info-item {
        display:grid;
        gap:5px;
        padding:16px 0;
        border-bottom:1px solid rgba(15, 23, 42, .08);
    }

    .contact-info-item:last-child {
        border-bottom:0;
    }

    .contact-info-item strong {
        color:var(--primary-color, #111827);
    }

    .contact-info-item a,
    .contact-info-item span {
        color:var(--text-color, #374151);
        text-decoration:none;
        line-height:1.6;
    }

    .contact-form {
        display:grid;
        gap:18px;
    }

    .form-grid {
        display:grid;
        gap:18px;
    }

    .form-grid-2 {
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .contact-form label {
        display:block;
        margin-bottom:8px;
        font-weight:700;
        color:#111827;
    }

    .contact-form input,
    .contact-form textarea {
        width:100%;
        border:1px solid #d1d5db;
        border-radius:14px;
        padding:13px 15px;
        font-size:15px;
        outline:none;
        background:#ffffff;
        box-sizing:border-box;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
        border-color:var(--primary-color, #111827);
        box-shadow:0 0 0 4px rgba(15, 23, 42, .08);
    }

    .alert {
        padding:14px 16px;
        border-radius:14px;
        margin-bottom:18px;
        line-height:1.6;
    }

    .alert-success {
        background:#dcfce7;
        border:1px solid #86efac;
        color:#166534;
    }

    .alert-error {
        background:#fee2e2;
        border:1px solid #fecaca;
        color:#991b1b;
    }

    .alert-error ul {
        margin:8px 0 0;
        padding-left:20px;
    }

    .form-error {
        display:block;
        color:#b91c1c;
        margin-top:6px;
        font-size:13px;
    }

    @media (max-width: 900px) {
        .contact-layout {
            grid-template-columns:1fr;
        }

        .form-grid-2 {
            grid-template-columns:1fr;
        }
    }
</style>
@endsection