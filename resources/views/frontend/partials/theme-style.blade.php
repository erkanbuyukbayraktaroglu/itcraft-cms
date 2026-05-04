<style>
    :root {
        --color-primary: {{ setting('primary_color', '#0f172a') }};
        --color-secondary: {{ setting('secondary_color', '#1e293b') }};
        --color-accent: {{ setting('accent_color', '#c9a227') }};
        --color-bg: {{ setting('background_color', '#ffffff') }};
        --color-text: {{ setting('text_color', '#111827') }};
        --color-header: {{ setting('header_color', '#ffffff') }};
        --color-footer: {{ setting('footer_color', '#0f172a') }};
        --color-button: {{ setting('button_color', '#0f172a') }};
        --color-button-text: {{ setting('button_text_color', '#ffffff') }};
        --container: 1180px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: var(--color-bg);
        color: var(--color-text);
        line-height: 1.65;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .container {
        width: min(var(--container), calc(100% - 32px));
        margin: 0 auto;
    }

    .site-header {
        background: var(--color-header);
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .header-inner {
        min-height: 76px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .logo {
        font-size: 22px;
        font-weight: 800;
        color: var(--color-primary);
        letter-spacing: -0.03em;
    }

    .logo span {
        color: var(--color-accent);
    }

    .nav {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .nav a {
        font-size: 15px;
        font-weight: 700;
        color: #374151;
    }

    .nav a:hover,
    .nav a.active {
        color: var(--color-accent);
    }

    .hero {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: #fff;
        padding: 90px 0;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 48px;
        align-items: center;
    }

    .hero h1 {
        font-size: clamp(36px, 5vw, 60px);
        line-height: 1.05;
        margin: 0 0 20px;
        letter-spacing: -0.05em;
    }

    .hero p {
        font-size: 18px;
        color: rgba(255,255,255,.82);
        margin: 0 0 28px;
        max-width: 680px;
    }

    .hero-card {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 24px;
        padding: 28px;
        backdrop-filter: blur(8px);
    }

    .hero-card strong {
        display: block;
        font-size: 18px;
        margin-bottom: 10px;
        color: #fff;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--color-button);
        color: var(--color-button-text);
        padding: 12px 18px;
        border-radius: 999px;
        font-weight: 800;
        border: 0;
        cursor: pointer;
    }

    .btn-accent {
        background: var(--color-accent);
        color: #111827;
    }

    .section {
        padding: 72px 0;
    }

    .section-muted {
        background: #f8fafc;
    }

    .section-title {
        max-width: 760px;
        margin-bottom: 34px;
    }

    .section-title h2 {
        margin: 0 0 12px;
        font-size: clamp(28px, 3vw, 42px);
        color: var(--color-primary);
        letter-spacing: -0.04em;
    }

    .section-title p {
        margin: 0;
        color: #6b7280;
        font-size: 17px;
    }

    .grid {
        display: grid;
        gap: 24px;
    }

    .grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 12px 30px rgba(15,23,42,.05);
    }

    .card h3 {
        margin: 0 0 10px;
        color: var(--color-primary);
        font-size: 21px;
    }

    .card p {
        margin: 0 0 18px;
        color: #6b7280;
    }

    .page-hero {
        background: #f8fafc;
        padding: 58px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .page-hero h1 {
        margin: 0 0 12px;
        font-size: clamp(34px, 4vw, 52px);
        color: var(--color-primary);
        letter-spacing: -0.04em;
    }

    .page-hero p {
        margin: 0;
        color: #6b7280;
        font-size: 17px;
        max-width: 780px;
    }

    .content {
        font-size: 17px;
    }

    .content h2,
    .content h3 {
        color: var(--color-primary);
    }

    .content a {
        color: var(--color-accent);
        font-weight: 700;
    }

    .contact-box {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 26px;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 15px;
        margin-bottom: 14px;
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    .alert-success {
        padding: 14px 16px;
        border-radius: 12px;
        background: #dcfce7;
        color: #166534;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .alert-error {
        padding: 14px 16px;
        border-radius: 12px;
        background: #fee2e2;
        color: #991b1b;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .site-footer {
        background: var(--color-footer);
        color: rgba(255,255,255,.78);
        padding: 46px 0 28px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.4fr .6fr;
        gap: 32px;
    }

    .footer-title {
        color: #fff;
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .footer-links {
        display: grid;
        gap: 10px;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.12);
        margin-top: 28px;
        padding-top: 18px;
        font-size: 14px;
    }

    .pagination {
        margin-top: 30px;
    }

    @media (max-width: 900px) {
        .hero-grid,
        .footer-grid,
        .grid-3,
        .grid-2 {
            grid-template-columns: 1fr;
        }

        .header-inner {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px 0;
        }

        .nav {
            gap: 12px;
        }

        .hero {
            padding: 64px 0;
        }

        .section {
            padding: 52px 0;
        }
    }
</style>