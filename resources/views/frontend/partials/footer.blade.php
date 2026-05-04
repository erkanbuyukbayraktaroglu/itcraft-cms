<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-title">
                    @php
                        $footerLogo = setting('footer_logo') ?: setting('logo');
                    @endphp

                    @if($footerLogo)
                        <img src="{{ asset_upload($footerLogo) }}" alt="{{ setting('site_name', 'Corporate CMS') }}" style="max-height:44px; width:auto; display:block; margin-bottom:12px;">
                    @else
                        {{ setting('site_name', 'Corporate CMS') }}
                    @endif
                </div>

                <p>{{ setting('site_description', 'SEO uyumlu ve yönetilebilir kurumsal web altyapısı.') }}</p>

                <p>
                    <strong>E-posta:</strong> {{ setting('email', 'info@example.com') }}<br>
                    <strong>Telefon:</strong> {{ setting('phone', '+90 212 000 00 00') }}<br>
                    <strong>Adres:</strong> {{ setting('address', 'İstanbul, Türkiye') }}
                </p>

                @if(setting('working_hours'))
                    <p><strong>Çalışma Saatleri:</strong> {{ setting('working_hours') }}</p>
                @endif
            </div>

            <div>
                <div class="footer-title">Hızlı Menü</div>

                @php
                    $footerMenuItems = collect();

                    try {
                        if (class_exists(\App\Models\Menu::class)) {
                            $footerMenu = \App\Models\Menu::query()
                                ->where('slug', 'footer')
                                ->where('is_active', 1)
                                ->first();

                            if ($footerMenu) {
                                $footerMenuItems = \App\Models\MenuItem::query()
                                    ->where('menu_id', $footerMenu->id)
                                    ->whereNull('parent_id')
                                    ->where('is_active', 1)
                                    ->orderBy('sort_order')
                                    ->orderBy('title')
                                    ->get();
                            }
                        }
                    } catch (\Throwable $e) {
                        $footerMenuItems = collect();
                    }
                @endphp

                <div class="footer-links">
                    @if($footerMenuItems->count())
                        @foreach($footerMenuItems as $menuItem)
                            <a href="{{ $menuItem->url }}" target="{{ $menuItem->target ?: '_self' }}">{{ $menuItem->title }}</a>
                        @endforeach
                    @else
                        <a href="{{ route('home') }}">Anasayfa</a>
                        <a href="{{ url('/hakkimizda') }}">Hakkımızda</a>
                        <a href="{{ route('services.index') }}">Hizmetler</a>
                        <a href="{{ route('team.index') }}">Ekibimiz</a>
                        <a href="{{ route('blog.index') }}">Blog</a>
                        <a href="{{ route('contact.index') }}">İletişim</a>
                    @endif
                </div>
            </div>

            <div>
                <div class="footer-title">Sosyal Medya</div>
                <div class="footer-links">
                    @if(setting('linkedin_url')) <a href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener">LinkedIn</a> @endif
                    @if(setting('instagram_url')) <a href="{{ setting('instagram_url') }}" target="_blank" rel="noopener">Instagram</a> @endif
                    @if(setting('facebook_url')) <a href="{{ setting('facebook_url') }}" target="_blank" rel="noopener">Facebook</a> @endif
                    @if(setting('x_url')) <a href="{{ setting('x_url') }}" target="_blank" rel="noopener">X / Twitter</a> @endif
                    @if(setting('youtube_url')) <a href="{{ setting('youtube_url') }}" target="_blank" rel="noopener">YouTube</a> @endif

                    @if(!setting('linkedin_url') && !setting('instagram_url') && !setting('facebook_url') && !setting('x_url') && !setting('youtube_url'))
                        <span style="color:rgba(255,255,255,.65);">Henüz sosyal medya linki eklenmemiş.</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} {{ setting('site_name', 'Corporate CMS') }}. Tüm hakları saklıdır.
        </div>
    </div>
</footer>