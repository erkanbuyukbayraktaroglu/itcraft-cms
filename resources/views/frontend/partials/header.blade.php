@php
    $headerLogo = setting('header_logo') ?: setting('logo');
    $siteName = setting('site_name', 'Web Sitesi');

    $menuItems = [];

    try {
        if (
            \Illuminate\Support\Facades\Schema::hasTable('menus') &&
            \Illuminate\Support\Facades\Schema::hasTable('menu_items')
        ) {
            $menuQuery = \Illuminate\Support\Facades\DB::table('menus')
                ->where('slug', 'header');

            if (\Illuminate\Support\Facades\Schema::hasColumn('menus', 'is_active')) {
                $menuQuery->where('is_active', 1);
            }

            $headerMenu = $menuQuery->first();

            if ($headerMenu) {
                $itemQuery = \Illuminate\Support\Facades\DB::table('menu_items')
                    ->where('menu_id', $headerMenu->id);

                if (\Illuminate\Support\Facades\Schema::hasColumn('menu_items', 'is_active')) {
                    $itemQuery->where('is_active', 1);
                }

                $items = $itemQuery
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

                foreach ($items->whereNull('parent_id') as $item) {
                    $children = [];

                    foreach ($items->where('parent_id', $item->id) as $child) {
                        $children[] = [
                            'title' => $child->title ?? '',
                            'url' => $child->url ?? '#',
                            'target' => $child->target ?? '_self',
                        ];
                    }

                    $menuItems[] = [
                        'title' => $item->title ?? '',
                        'url' => $item->url ?? '#',
                        'target' => $item->target ?? '_self',
                        'children' => $children,
                    ];
                }
            }
        }
    } catch (\Throwable $e) {
        $menuItems = [];
    }

    if (empty($menuItems)) {
        $menuItems = [
            ['title' => 'Anasayfa', 'url' => '/', 'target' => '_self', 'children' => []],
            ['title' => 'Hakkımızda', 'url' => '/hakkimizda', 'target' => '_self', 'children' => []],
            ['title' => 'Hizmetler', 'url' => '/hizmetler', 'target' => '_self', 'children' => []],
            ['title' => 'Ekibimiz', 'url' => '/ekibimiz', 'target' => '_self', 'children' => []],
            ['title' => 'Blog', 'url' => '/blog', 'target' => '_self', 'children' => []],
            ['title' => 'İletişim', 'url' => '/iletisim', 'target' => '_self', 'children' => []],
        ];
    }

    $resolveUrl = function (?string $url) {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        if ($url === '#') {
            return '#';
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) {
            return $url;
        }

        return url($url);
    };
@endphp

<header class="site-header" id="siteHeader">
    <div class="container">
        <div class="site-header-inner">
            <a href="{{ url('/') }}" class="site-logo" aria-label="{{ $siteName }}">
                @if(!empty($headerLogo))
                    <img src="{{ asset_upload($headerLogo) }}" alt="{{ $siteName }}">
                @else
                    <span>{{ $siteName }}</span>
                @endif
            </a>

            <nav class="site-nav desktop-nav" aria-label="Ana Menü">
                @foreach($menuItems as $item)
                    <div class="nav-item {{ !empty($item['children']) ? 'has-dropdown' : '' }}">
                        <a href="{{ $resolveUrl($item['url']) }}"
                           target="{{ $item['target'] ?? '_self' }}"
                           class="{{ active_menu($item['url']) }}">
                            {{ $item['title'] }}
                        </a>

                        @if(!empty($item['children']))
                            <div class="nav-dropdown">
                                @foreach($item['children'] as $child)
                                    <a href="{{ $resolveUrl($child['url']) }}"
                                       target="{{ $child['target'] ?? '_self' }}">
                                        {{ $child['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>

            <button type="button"
                    class="mobile-menu-toggle"
                    id="mobileMenuToggle"
                    aria-label="Menüyü aç/kapat"
                    aria-controls="mobileMenu"
                    aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <nav class="mobile-menu" id="mobileMenu" aria-label="Mobil Menü">
            @foreach($menuItems as $item)
                <div class="mobile-nav-item {{ !empty($item['children']) ? 'has-children' : '' }}">
                    <a href="{{ $resolveUrl($item['url']) }}"
                       target="{{ $item['target'] ?? '_self' }}"
                       class="{{ active_menu($item['url']) }}">
                        {{ $item['title'] }}
                    </a>

                    @if(!empty($item['children']))
                        <div class="mobile-submenu">
                            @foreach($item['children'] as $child)
                                <a href="{{ $resolveUrl($child['url']) }}"
                                   target="{{ $child['target'] ?? '_self' }}">
                                    {{ $child['title'] }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </div>
</header>