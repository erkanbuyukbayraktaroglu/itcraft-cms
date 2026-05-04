@extends('frontend.layouts.app')

@section('title', seo_title(setting('site_slogan', 'Kurumsal Web Altyapısı')))
@section('meta_description', setting('site_description', 'SEO uyumlu, hızlı ve yönetilebilir kurumsal web sitesi altyapısı.'))

@push('head')
<style>
    .hero {
        position: relative;
        overflow: hidden;
        min-height: 620px;
        background: #0f172a;
        color: #ffffff;
        padding: 0;
    }

    .hero-carousel {
        position: relative;
        min-height: 620px;
        width: 100%;
        overflow: hidden;
    }

    .hero-slide {
        display: none;
        position: absolute;
        inset: 0;
        min-height: 620px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        animation: heroFade .55s ease-in-out;
    }

    .hero-slide.active {
        display: block;
    }

    .hero-slide::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(15, 23, 42, .92) 0%, rgba(15, 23, 42, .74) 42%, rgba(15, 23, 42, .38) 100%),
            linear-gradient(180deg, rgba(15, 23, 42, .20) 0%, rgba(15, 23, 42, .80) 100%);
        z-index: 1;
    }

    .hero-slide::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 20% 25%, rgba(201,162,39,.20), transparent 34%),
            radial-gradient(circle at 75% 65%, rgba(255,255,255,.08), transparent 30%);
        z-index: 2;
        pointer-events: none;
    }

    .hero-slide-content {
        position: relative;
        z-index: 3;
        min-height: 620px;
        display: flex;
        align-items: center;
    }

    .hero-content-inner {
        max-width: 760px;
        padding: 90px 0;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        color: rgba(255,255,255,.90);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .hero-eyebrow::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--color-accent);
        box-shadow: 0 0 0 5px rgba(201,162,39,.18);
    }

    .hero h1 {
        margin: 0;
        color: #ffffff;
        font-size: clamp(42px, 5vw, 72px);
        line-height: 1.02;
        letter-spacing: -0.055em;
        font-weight: 900;
        max-width: 760px;
    }

    .hero-subtitle {
        margin: 22px 0 0;
        color: rgba(255,255,255,.92);
        font-size: clamp(18px, 2vw, 24px);
        line-height: 1.45;
        font-weight: 700;
        max-width: 680px;
    }

    .hero-description {
        margin: 18px 0 0;
        color: rgba(255,255,255,.76);
        font-size: 17px;
        line-height: 1.75;
        max-width: 620px;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 34px;
        flex-wrap: wrap;
    }

    .hero .btn {
        min-height: 48px;
        padding-left: 22px;
        padding-right: 22px;
    }

    .hero-secondary-link {
        color: #ffffff;
        font-weight: 800;
        padding: 13px 18px;
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.16);
    }

    .hero-secondary-link:hover {
        background: rgba(255,255,255,.18);
    }

    @keyframes heroFade {
        from {
            opacity: 0;
            transform: scale(1.015);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 8;
        width: 52px;
        height: 52px;
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 999px;
        background: rgba(15,23,42,.42);
        color: #ffffff;
        cursor: pointer;
        font-size: 28px;
        font-weight: 400;
        line-height: 1;
        backdrop-filter: blur(8px);
        transition: .2s ease;
    }

    .hero-arrow:hover {
        background: rgba(15,23,42,.72);
        border-color: rgba(255,255,255,.36);
    }

    .hero-arrow.prev {
        left: 26px;
    }

    .hero-arrow.next {
        right: 26px;
    }

    .hero-dots {
        position: absolute;
        left: 50%;
        bottom: 34px;
        transform: translateX(-50%);
        z-index: 8;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 13px;
        border-radius: 999px;
        background: rgba(15,23,42,.35);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(8px);
    }

    .hero-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        border: 0;
        background: rgba(255,255,255,.45);
        cursor: pointer;
        padding: 0;
        transition: .2s ease;
    }

    .hero-dot.active {
        width: 30px;
        background: var(--color-accent);
    }

    @media (max-width: 900px) {
        .hero,
        .hero-carousel,
        .hero-slide,
        .hero-slide-content {
            min-height: 560px;
        }

        .hero-slide::before {
            background:
                linear-gradient(135deg, rgba(15,23,42,.94), rgba(15,23,42,.70)),
                linear-gradient(180deg, rgba(15,23,42,.25), rgba(15,23,42,.85));
        }

        .hero-content-inner {
            padding: 80px 0 100px;
        }

        .hero-arrow {
            display: none;
        }

        .hero-dots {
            bottom: 24px;
        }
    }

    @media (max-width: 640px) {
        .hero,
        .hero-carousel,
        .hero-slide,
        .hero-slide-content {
            min-height: 520px;
        }

        .hero h1 {
            font-size: 38px;
        }

        .hero-subtitle {
            font-size: 18px;
        }

        .hero-description {
            font-size: 15px;
        }

        .hero-content-inner {
            padding: 70px 0 90px;
        }
    }
</style>
@endpush

@section('content')
<section class="hero">
    <div class="hero-carousel" id="heroCarousel">
        @if($sliders->count())
            @foreach($sliders as $index => $slider)
                @php
                    $backgroundImage = $slider->image ? asset_upload($slider->image) : '';
                @endphp

                <div
                    class="hero-slide {{ $index === 0 ? 'active' : '' }}"
                    data-slide="{{ $index }}"
                    @if($backgroundImage)
                        style="background-image: url('{{ $backgroundImage }}');"
                    @endif
                >
                    <div class="container hero-slide-content">
                        <div class="hero-content-inner">
                            <div class="hero-eyebrow">
                                {{ setting('site_name', 'Corporate CMS') }}
                            </div>

                            <h1>{{ $slider->title }}</h1>

                            @if($slider->subtitle)
                                <p class="hero-subtitle">{{ $slider->subtitle }}</p>
                            @endif

                            @if($slider->description)
                                <p class="hero-description">{{ $slider->description }}</p>
                            @endif

                            <div class="hero-actions">
                                @if($slider->button_text && $slider->button_url)
                                    <a href="{{ $slider->button_url }}" class="btn btn-accent">
                                        {{ $slider->button_text }}
                                    </a>
                                @endif

                                <a href="{{ route('contact.index') }}" class="hero-secondary-link">
                                    İletişime Geç
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($sliders->count() > 1)
                <button type="button" class="hero-arrow prev" id="heroPrev" aria-label="Önceki slider">‹</button>
                <button type="button" class="hero-arrow next" id="heroNext" aria-label="Sonraki slider">›</button>

                <div class="hero-dots" id="heroDots">
                    @foreach($sliders as $index => $slider)
                        <button type="button"
                                class="hero-dot {{ $index === 0 ? 'active' : '' }}"
                                data-slide-target="{{ $index }}"
                                aria-label="Slider {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="hero-slide active">
                <div class="container hero-slide-content">
                    <div class="hero-content-inner">
                        <div class="hero-eyebrow">
                            {{ setting('site_name', 'Corporate CMS') }}
                        </div>

                        <h1>{{ setting('site_slogan', 'Laravel tabanlı kurumsal web altyapısı') }}</h1>
                        <p class="hero-description">
                            {{ setting('site_description', 'Sınırsız sayfa destekli, SEO uyumlu, dinamik renk yönetimli kurumsal web sitesi altyapısı.') }}
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('services.index') }}" class="btn btn-accent">Hizmetleri İncele</a>
                            <a href="{{ route('contact.index') }}" class="hero-secondary-link">İletişime Geç</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@if($featuredServices->count())
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Hizmetler</h2>
            <p>Kurumsal yapınıza uygun, yönetilebilir ve geliştirilebilir hizmet modülleri.</p>
        </div>

        <div class="grid grid-3">
            @foreach($featuredServices as $service)
                <article class="card">
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->summary }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="btn">Detay</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($aboutPage)
<section class="section section-muted">
    <div class="container">
        <div class="section-title">
            <h2>{{ $aboutPage->title }}</h2>
            <p>{{ $aboutPage->summary }}</p>
        </div>

        <div class="content">
            {!! $aboutPage->content !!}
        </div>

        <br>
        <a href="{{ url('/hakkimizda') }}" class="btn">Devamını Oku</a>
    </div>
</section>
@endif

@if($latestPosts->count())
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Blog</h2>
            <p>Güncel içerikler, duyurular ve bilgilendirici yazılar.</p>
        </div>

        <div class="grid grid-3">
            @foreach($latestPosts as $post)
                <article class="card">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->summary }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn">Oku</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.getElementById('heroCarousel');

        if (!carousel) {
            return;
        }

        const slides = carousel.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const prevButton = document.getElementById('heroPrev');
        const nextButton = document.getElementById('heroNext');

        if (slides.length <= 1) {
            return;
        }

        let currentIndex = 0;
        let timer = null;
        const intervalMs = 6000;

        function showSlide(index) {
            if (index < 0) {
                index = slides.length - 1;
            }

            if (index >= slides.length) {
                index = 0;
            }

            slides.forEach(function (slide) {
                slide.classList.remove('active');
            });

            dots.forEach(function (dot) {
                dot.classList.remove('active');
            });

            slides[index].classList.add('active');

            const activeDot = document.querySelector('.hero-dot[data-slide-target="' + index + '"]');

            if (activeDot) {
                activeDot.classList.add('active');
            }

            currentIndex = index;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        function startTimer() {
            stopTimer();
            timer = setInterval(nextSlide, intervalMs);
        }

        function stopTimer() {
            if (timer) {
                clearInterval(timer);
            }
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                nextSlide();
                startTimer();
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                prevSlide();
                startTimer();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const target = parseInt(dot.getAttribute('data-slide-target'), 10);
                showSlide(target);
                startTimer();
            });
        });

        carousel.addEventListener('mouseenter', stopTimer);
        carousel.addEventListener('mouseleave', startTimer);

        startTimer();
    });
</script>
@endpush