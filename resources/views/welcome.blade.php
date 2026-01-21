<!doctype html>

<html lang="en" class="layout-navbar-fixed layout-wide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets/') }}/" data-template="front-pages" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Milk Track - Sistem Monitoring Penyetoran Susu</title>

    <meta name="description" content="Sistem monitoring penyetoran susu untuk peternak dan koperasi" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/logo-air.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/nouislider/nouislider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/front-config.js') }}"></script>
</head>
<style>
    .faq-image {
        max-width: 60%;
        height: auto;
    }
    
    /* Table Card Hover Effect */
    .table-card-hover {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .table-card-hover::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }
    
    .table-card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }
    
    .table-card-hover:hover::before {
        opacity: 1;
    }
    
    .table-card-hover:hover .card-header {
        background: linear-gradient(135deg, var(--bs-primary) 0%, #6f42c1 100%) !important;
    }
    
    .table-card-hover:hover .bg-success {
        background: linear-gradient(135deg, var(--bs-success) 0%, #20c997 100%) !important;
    }
    
    .table-card-hover .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table-card-hover:hover .table tbody tr:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
    
    /* Responsive Table */
    @media (max-width: 991px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table th, .table td {
            padding: 0.5rem 0.25rem;
        }
    }
    
    /* Loading Animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .table tbody tr:empty {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    /* Card Shadow on Mobile */
    @media (max-width: 768px) {
        .table-card-hover {
            margin-bottom: 1.5rem;
        }
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* ========== FIX TABLER ICONS ========== */
    .ti {
        font-family: 'tabler-icons' !important;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        display: inline-block;
        vertical-align: middle;
    }
    
    /* Tabler Icon Classes */
    .ti-home-2::before { content: "🏠"; }
    .ti-users::before { content: "👥"; }
    .ti-sunrise::before { content: "🌅"; }
    .ti-sunset::before { content: "🌇"; }
    .ti-droplet::before { content: "💧"; }
    .ti-calendar::before { content: "📅"; }
    .ti-calendar-today::before { content: "📅"; }
    .ti-info-circle::before { content: "ℹ️"; }
    .ti-database-off::before { content: "📊"; }
    .ti-clock::before { content: "🕐"; }
    .ti-login::before { content: "🔑"; }
    .ti-chevron-left::before { content: "◄"; font-size: 1.2rem; }
    .ti-chevron-right::before { content: "►"; font-size: 1.2rem; }
    
    /* ========== CARD HEADER POS STYLING ========== */
    .card-header.bg-primary,
    .card-header.bg-success {
        text-align: center;
        padding: 0.75rem 1rem !important;
    }
    
    .card-header h5 {
        margin: 0;
        color: white !important;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-size: 1.1rem;
    }
    
    .card-header small {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    /* Badge Total Peternak di Pojok */
    .position-absolute .badge {
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        border-radius: 0.25rem;
    }
    
    /* ========== STICKY HEADER/FOOTER ========== */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa !important;
    }
    
    .sticky-bottom {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background-color: #f8f9fa !important;
    }
    
    /* ========== FIX HOVER TIDAK MENUTUPI TOTAL ========== */
    .table-hover tbody tr:hover {
        background-color: rgba(105, 108, 255, 0.08) !important;
    }
    
    /* Fix untuk kolom Total agar tidak tertutup hover */
    .table-hover tbody tr:hover td.bg-success {
        background-color: rgba(34, 197, 94, 0.15) !important;
    }
    
    .table tbody tr td.bg-success {
        background-color: rgba(34, 197, 94, 0.1) !important;
        position: relative;
        z-index: 1;
    }
    
    /* Pastikan text tetap visible */
    .table tbody tr:hover td strong {
        position: relative;
        z-index: 2;
    }
    
    /* ========== TABLE SCROLL STYLING ========== */
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f1f5f9;
    }
    
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* ========== DATE FILTER STYLING ========== */
    .date-filter-container {
        max-width: 100%;
    }
    
    .date-input {
        border: 2px solid #696cff;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 200px;
        min-width: 150px;
    }
    
    .date-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        border-color: #696cff;
    }
    
    .date-nav-btn, .today-btn {
        transition: all 0.3s ease;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .date-nav-btn {
        padding: 0.4rem 0.7rem !important;
        min-width: 40px;
    }
    
    .date-nav-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(105, 108, 255, 0.3);
    }
    
    .today-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(105, 108, 255, 0.3);
    }
    
    /* Mobile Responsive Date Filter */
    @media (max-width: 576px) {
        .date-filter-container {
            gap: 0.5rem !important;
        }
        
        .date-input {
            width: 100% !important;
            max-width: 180px;
            font-size: 0.85rem;
        }
        
        .date-nav-btn {
            padding: 0.35rem 0.6rem !important;
            min-width: 36px;
        }
        
        .today-btn {
            font-size: 0.85rem;
        }
        
        .today-text {
            display: inline;
        }
    }
    
    @media (max-width: 400px) {
        .date-input {
            max-width: 140px;
            font-size: 0.8rem;
        }
        
        .today-text {
            display: none;
        }
    }
    
    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    /* ========== MOBILE RESPONSIVE ========== */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.75rem;
        }
        
        .card-header h5 {
            font-size: 1rem;
        }
        
        .table th, .table td {
            padding: 0.3rem !important;
        }
        
        .d-flex.justify-content-center {
            flex-wrap: wrap;
        }
        
        #tanggalFilter {
            width: 100% !important;
            margin: 0.5rem 0;
        }
    }
    
    /* ========== ARTICLES CAROUSEL STYLING ========== */
    .landing-articles {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .articles-carousel-container {
        position: relative;
        margin: 0 60px;
    }
    
    @media (max-width: 991px) {
        .articles-carousel-container {
            margin: 0 40px;
        }
        
        .carousel-control-prev-article,
        .carousel-control-next-article {
            left: -40px !important;
        }
        
        .carousel-control-next-article {
            right: -40px !important;
            left: auto !important;
        }
    }
    
    @media (max-width: 768px) {
        .article-card-wrapper {
            min-width: 100% !important;
        }
        
        .articles-carousel-container {
            margin: 0 20px;
        }
        
        .carousel-control-prev-article,
        .carousel-control-next-article {
            left: -20px !important;
        }
        
        .carousel-control-next-article {
            right: -20px !important;
            left: auto !important;
        }
    }
    
    .article-card-wrapper .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .article-card-wrapper .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(105, 108, 255, 0.3) !important;
    }
    
    .article-card-wrapper a {
        color: inherit;
        text-decoration: none;
    }
    
    .article-card-wrapper a:hover {
        text-decoration: none;
    }
    
    .carousel-control-prev-article,
    .carousel-control-next-article {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .carousel-control-prev-article:hover,
    .carousel-control-next-article:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
    }
    
    .indicator-dot {
        transition: all 0.3s ease;
    }
    
    .indicator-dot:hover {
        transform: scale(1.3);
    }
    
    .indicator-dot.active {
        width: 24px !important;
        border-radius: 5px !important;
    }
</style>

<body>
    <script src="{{ asset('assets/vendor/js/dropdown-hover.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/mega-dropdown.js') }}"></script>
    
    <!-- Navbar: Start -->
    <nav class="layout-navbar shadow-none py-0">
        <div class="container">
            <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <!-- Menu logo wrapper: Start -->
                <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8 ms-0">
                    <!-- Mobile menu toggle: Start-->
                    <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icon-base ti tabler-menu-2 icon-lg align-middle text-heading fw-medium"></i>
                    </button>
                    <!-- Mobile menu toggle: End-->
                    <a href="{{ url('/') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Milk Track" width="80"
                                height="65" />
                        </span>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2 ps-1 fs-6 text-wrap">
                            MILK TRACK
                        </span>
                    </a>
                </div>
                <!-- Menu logo wrapper: End -->
                
                <!-- Menu wrapper: Start -->
                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icon-base ti tabler-x icon-lg"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-medium" aria-current="page" href="#landingHero">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="#landingArticles">D-Learn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="#landingContact">Contact us</a>
                        </li>
                    </ul>
                </div>

                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                
                <!-- Toolbar: Start -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <!-- Theme Switcher: Start -->
                    <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-1">
                        <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <i class="icon-base ti tabler-sun icon-lg theme-icon-active"></i>
                            <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                            <li>
                                <button type="button" class="dropdown-item align-items-center active"
                                    data-bs-theme-value="light" aria-pressed="false">
                                    <span><i class="icon-base ti tabler-sun icon-md me-3"
                                            data-icon="sun"></i>Light</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item align-items-center"
                                    data-bs-theme-value="dark" aria-pressed="true">
                                    <span><i class="icon-base ti tabler-moon-stars icon-md me-3"
                                            data-icon="moon-stars"></i>Dark</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item align-items-center"
                                    data-bs-theme-value="system" aria-pressed="false">
                                    <span><i class="icon-base ti tabler-device-desktop-analytics icon-md me-3"
                                            data-icon="device-desktop-analytics"></i>System</span>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!-- Theme Switcher: End -->
                    
                    <!-- Login Button: Start -->
                    <li class="nav-item">
                        <div class="landing-hero-btn d-inline-block position-relative">
                            <span class="hero-btn-item position-absolute d-none d-md-flex fw-medium">Join Now!
                                <img src="{{ asset('assets/img/front-pages/icons/Join-community-arrow.png') }}"
                                    alt="Join community arrow" class="scaleX-n1-rtl" />
                            </span>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
                        </div>
                    </li>
                    <!-- Login Button: End -->
                    
                    <!-- navbar button: End -->
                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>
    <!-- Navbar: End -->

    <!-- Sections:Start -->
    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                    class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
                    data-speed="1" />
                <div class="container">
                    <div class="hero-text-box text-center position-relative">
                        <h1 class="text-primary hero-title display-6 fw-extrabold">
                            Sistem Monitoring Penyetoran Susu 
                        </h1>
                        <h2 class="hero-sub-title h6 mb-6">
                            ( MILK TRACK )<br class="d-none d-lg-block" />
                            Sistem Informasi Monitoring Penyetoran Susu bertujuan memantau aktivitas penyetoran susu secara real-time,
                            mencakup data volume, kualitas, dan waktu setoran untuk memastikan transparansi dan efisiensi 
                            dalam pengelolaan susu dari peternak ke koperasi.
                        </h2>
                    </div>
                    <br><br>
                    <div id="heroDashboardAnimation" class="hero-animation-img">
                      <a href="../vertical-menu-template/app-ecommerce-dashboard.html" target="_blank">
                        <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                          <img
                            src="{{ asset('assets/img/SIKUALA/33.png') }}"
                            alt="hero dashboard"
                            class="animation-img"
                            data-app-light-img="SIKUALA/33.png"
                            data-app-dark-img="SIKUALA/33.png" />
                          <img
                            src="{{ asset('assets/img/SIKUALA/44.png') }}"
                            alt="hero elements"
                            class="position-absolute hero-elements-img animation-img top-0 start-0"
                            data-app-light-img="SIKUALA/44.png"
                            data-app-dark-img="SIKUALA/44.png" />
                        </div>
                      </a>
                    </div>
                  </div>
                  <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->

        <!-- Articles: Start -->
        <section id="landingArticles" class="section-py landing-articles">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Artikel & Pembelajaran</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">Artikel Terkini untuk Peternak
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}"
                            alt="articles"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-12">
                    Dapatkan informasi dan edukasi terbaru seputar peternakan dan pengelolaan susu berkualitas
                </p>

                @if($articles && $articles->count() > 0)
                    <!-- Articles Carousel -->
                    <div class="position-relative">
                        <!-- Navigation Buttons -->
                        <button class="btn btn-icon btn-primary carousel-control-prev-article" type="button" style="position: absolute; left: -50px; top: 50%; transform: translateY(-50%); z-index: 10; border-radius: 50%;">
                            <span style="font-size: 1.5rem; font-weight: bold;">‹</span>
                        </button>
                        <button class="btn btn-icon btn-primary carousel-control-next-article" type="button" style="position: absolute; right: -50px; top: 50%; transform: translateY(-50%); z-index: 10; border-radius: 50%;">
                            <span style="font-size: 1.5rem; font-weight: bold;">›</span>
                        </button>

                        <!-- Articles Container -->
                        <div class="articles-carousel-container" style="overflow: hidden;">
                            <div class="articles-carousel-track" style="display: flex; transition: transform 0.5s ease;">
                                @foreach($articles as $article)
                                <div class="article-card-wrapper" style="min-width: 33.333%; padding: 0 15px;">
                                    <a href="{{ route('article.show', $article->slug) }}" class="text-decoration-none">
                                        <div class="card shadow-sm border-0 h-100 article-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer;">
                                            <!-- Article Image -->
                                            <div class="card-img-top" style="height: 200px; overflow: hidden; background: #f5f5f5;">
                                                @if($article->image)
                                                    <img src="{{ Storage::url($article->image) }}" 
                                                         alt="{{ $article->title }}"
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 bg-primary bg-opacity-10">
                                                        <span style="font-size: 4rem;">📰</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Article Content -->
                                            <div class="card-body">
                                                <h5 class="card-title mb-3" style="font-weight: 700; color: #696cff; font-size: 1.1rem; line-height: 1.4;">
                                                    {{ Str::limit($article->title, 60) }}
                                                </h5>
                                                <p class="card-text text-muted mb-3" style="font-size: 0.9rem; line-height: 1.6;">
                                                    {{ Str::limit($article->excerpt, 100) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary-subtle text-primary">Baca Selengkapnya</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="text-center mt-4">
                            <div class="carousel-indicators-custom">
                                @for($i = 0; $i < ceil($articles->count() / 3); $i++)
                                    <button class="indicator-dot {{ $i == 0 ? 'active' : '' }}" 
                                            data-slide="{{ $i }}"
                                            style="width: 10px; height: 10px; border-radius: 50%; border: none; background: {{ $i == 0 ? '#696cff' : '#ddd' }}; margin: 0 5px; cursor: pointer; transition: background 0.3s ease;">
                                    </button>
                                @endfor
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="ti ti-article-off mb-3" style="font-size: 5rem; color: #ddd;"></i>
                        <h5 class="text-muted">Belum ada artikel tersedia</h5>
                        <p class="text-muted">Artikel terbaru akan ditampilkan di sini</p>
                    </div>
                @endif
            </div>
        </section>
        <!-- Articles: End -->

        <!-- CTA: Start -->
        <section id="landingCTA" class="section-py landing-cta position-relative">
            <img src="{{ asset('assets/img/front-pages/backgrounds/cta-bg-light.png') }}"
                class="position-absolute bottom-0 end-0 scaleX-n1-rtl h-100 w-100 z-n1" alt="cta image"
                data-app-light-img="front-pages/backgrounds/cta-bg-light.png"
                data-app-dark-img="front-pages/backgrounds/cta-bg-dark.png" />
            <div class="container">
                <!-- Header Section -->
                <div class="text-center mb-4">
                    <h3 class="cta-title text-primary fw-bold mb-2">Visualisasi Data Penyetoran Real-Time!</h3>
                    <p class="text-body mb-3">Data jelas dan intuitif yang menggambarkan aktivitas penyetoran susu dari setiap pos.</p>
                    
                    <!-- Date Filter -->
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-3 date-filter-container">
                        <button type="button" class="btn btn-sm btn-outline-primary date-nav-btn" id="btnPrevDate">
                            <span style="font-size: 1.2rem; font-weight: bold;">‹</span>
                        </button>
                        <input type="date" 
                               class="form-control form-control-sm text-center date-input" 
                               id="tanggalFilter" 
                               value="{{ $tanggal }}">
                        <button type="button" class="btn btn-sm btn-outline-primary date-nav-btn" id="btnNextDate">
                            <span style="font-size: 1.2rem; font-weight: bold;">›</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary today-btn" id="btnToday">
                            📅 <span class="today-text">Hari Ini</span>
                        </button>
                    </div>
                    
                    <p class="text-muted small mb-2">
                        <i class="ti ti-calendar me-1"></i>
                        <strong>{{ $tanggalCarbon->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</strong>
                    </p>
                    
                    <p class="text-muted small">
                        <i class="ti ti-info-circle me-1"></i>
                        Data di bawah hanya untuk preview. Klik pada tabel untuk login dan melihat detail lengkap.
                    </p>
                </div>

                <!-- Tables Section -->
                <div class="row g-4">
                    <!-- Pos 1 Table -->
                    <div class="col-lg-6">
                        <div class="card shadow-lg border-0 h-100 table-card-hover" onclick="window.location.href='{{ route('login') }}'" style="cursor: pointer; transition: transform 0.3s ease;">
                            <div class="card-header bg-primary text-white text-center py-2">
                                <h5 class="mb-0">
                                    <i class="ti ti-home-2 me-2"></i>
                                    {{ $pos1->nama_pos ?? 'Pos 1' }}
                                </h5>
                                <small class="d-block">{{ $pos1->lokasi ?? 'Wilayah Pos 1' }}</small>
                            </div>
                            <div class="card-body p-0 position-relative">
                                <!-- Badge Total Peternak di Pojok -->
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 5;">
                                    <span class="badge bg-primary text-white shadow-sm">
                                        <i class="ti ti-users me-1"></i>{{ count($dataPos1) }} Peternak
                                    </span>
                                </div>
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="font-size: 0.75rem; padding: 0.5rem;">No</th>
                                                <th style="font-size: 0.75rem; padding: 0.5rem;">Peternak</th>
                                                <th class="text-center bg-info bg-opacity-10" colspan="2" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-sunrise me-1"></i>Pagi
                                                </th>
                                                <th class="text-center bg-warning bg-opacity-10" colspan="2" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-sunset me-1"></i>Sore
                                                </th>
                                                <th class="text-center bg-success bg-opacity-10" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-droplet me-1"></i>Total
                                                </th>
                                            </tr>
                                            <tr class="table-light">
                                                <th></th>
                                                <th></th>
                                                <th class="text-center bg-info bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">Vol (L)</th>
                                                <th class="text-center bg-info bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">BJ</th>
                                                <th class="text-center bg-warning bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">Vol (L)</th>
                                                <th class="text-center bg-warning bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">BJ</th>
                                                <th class="text-center bg-success bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">(L)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dataPos1 as $index => $item)
                                            <tr>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">{{ $index + 1 }}</td>
                                                <td style="font-size: 0.8rem; padding: 0.4rem;">
                                                    <div class="text-truncate" style="max-width: 150px;" title="{{ $item['nama_peternak'] }}">
                                                        <strong>{{ $item['nama_peternak'] }}</strong>
                                                    </div>
                                                    <small class="text-muted">{{ $item['kode_peternak'] }}</small>
                                                </td>
                                                <td class="text-center" style="font-size: 0.8rem; padding: 0.4rem;">
                                                    {{ $item['volume_pagi'] ? number_format($item['volume_pagi'], 2) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">
                                                    {{ $item['bj_pagi'] ? number_format($item['bj_pagi'] / 1000, 3) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.8rem; padding: 0.4rem;">
                                                    {{ $item['volume_sore'] ? number_format($item['volume_sore'], 2) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">
                                                    {{ $item['bj_sore'] ? number_format($item['bj_sore'] / 1000, 3) : '-' }}
                                                </td>
                                                <td class="text-center bg-success bg-opacity-10" style="font-size: 0.85rem; padding: 0.4rem;">
                                                    <strong class="text-dark">{{ number_format($item['volume_pagi'] + $item['volume_sore'], 2) }}</strong>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="ti ti-database-off fs-3 d-block mb-2"></i>
                                                    Belum ada data penyetoran untuk tanggal ini
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if(count($dataPos1) > 0)
                                        <tfoot class="table-light border-top border-2 sticky-bottom">
                                            <tr class="fw-bold">
                                                <td colspan="2" class="text-end" style="font-size: 0.8rem; padding: 0.5rem;">TOTAL:</td>
                                                <td class="text-center text-info" style="font-size: 0.85rem; padding: 0.5rem;">
                                                    {{ number_format($totalVolumePagiPos1, 2) }}
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.7rem;">-</td>
                                                <td class="text-center text-warning" style="font-size: 0.85rem; padding: 0.5rem;">
                                                    {{ number_format($totalVolumeSorePos1, 2) }}
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.7rem;">-</td>
                                                <td class="text-center text-dark bg-success bg-opacity-10" style="font-size: 0.9rem; padding: 0.5rem; font-weight: 700;">
                                                    {{ number_format($totalVolumePos1, 2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-center">
                                <small class="text-muted">
                                    <i class="ti ti-droplet me-1"></i>
                                    Total: <strong class="text-primary">{{ number_format($totalVolumePos1, 2) }} Liter</strong>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Pos 2 Table -->
                    <div class="col-lg-6">
                        <div class="card shadow-lg border-0 h-100 table-card-hover" onclick="window.location.href='{{ route('login') }}'" style="cursor: pointer; transition: transform 0.3s ease;">
                            <div class="card-header bg-primary text-white text-center py-2">
                                <h5 class="mb-0">
                                    <i class="ti ti-home-2 me-2"></i>
                                    {{ $pos2->nama_pos ?? 'Pos 2' }}
                                </h5>
                                <small class="d-block">{{ $pos2->lokasi ?? 'Wilayah Pos 2' }}</small>
                            </div>
                            <div class="card-body p-0 position-relative">
                                <!-- Badge Total Peternak di Pojok -->
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 5;">
                                    <span class="badge bg-primary text-white shadow-sm">
                                        <i class="ti ti-users me-1"></i>{{ count($dataPos2) }} Peternak
                                    </span>
                                </div>
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="font-size: 0.75rem; padding: 0.5rem;">No</th>
                                                <th style="font-size: 0.75rem; padding: 0.5rem;">Peternak</th>
                                                <th class="text-center bg-info bg-opacity-10" colspan="2" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-sunrise me-1"></i>Pagi
                                                </th>
                                                <th class="text-center bg-warning bg-opacity-10" colspan="2" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-sunset me-1"></i>Sore
                                                </th>
                                                <th class="text-center bg-success bg-opacity-10" style="font-size: 0.75rem; padding: 0.5rem;">
                                                    <i class="ti ti-droplet me-1"></i>Total
                                                </th>
                                            </tr>
                                            <tr class="table-light">
                                                <th></th>
                                                <th></th>
                                                <th class="text-center bg-info bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">Vol (L)</th>
                                                <th class="text-center bg-info bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">BJ</th>
                                                <th class="text-center bg-warning bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">Vol (L)</th>
                                                <th class="text-center bg-warning bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">BJ</th>
                                                <th class="text-center bg-success bg-opacity-10" style="font-size: 0.65rem; padding: 0.3rem;">(L)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dataPos2 as $index => $item)
                                            <tr>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">{{ $index + 1 }}</td>
                                                <td style="font-size: 0.8rem; padding: 0.4rem;">
                                                    <div class="text-truncate" style="max-width: 150px;" title="{{ $item['nama_peternak'] }}">
                                                        <strong>{{ $item['nama_peternak'] }}</strong>
                                                    </div>
                                                    <small class="text-muted">{{ $item['kode_peternak'] }}</small>
                                                </td>
                                                <td class="text-center" style="font-size: 0.8rem; padding: 0.4rem;">
                                                    {{ $item['volume_pagi'] ? number_format($item['volume_pagi'], 2) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">
                                                    {{ $item['bj_pagi'] ? number_format($item['bj_pagi'] / 1000, 3) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.8rem; padding: 0.4rem;">
                                                    {{ $item['volume_sore'] ? number_format($item['volume_sore'], 2) : '-' }}
                                                </td>
                                                <td class="text-center" style="font-size: 0.75rem; padding: 0.4rem;">
                                                    {{ $item['bj_sore'] ? number_format($item['bj_sore'] / 1000, 3) : '-' }}
                                                </td>
                                                <td class="text-center bg-success bg-opacity-10" style="font-size: 0.85rem; padding: 0.4rem;">
                                                    <strong class="text-dark">{{ number_format($item['volume_pagi'] + $item['volume_sore'], 2) }}</strong>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="ti ti-database-off fs-3 d-block mb-2"></i>
                                                    Belum ada data penyetoran untuk tanggal ini
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if(count($dataPos2) > 0)
                                        <tfoot class="table-light border-top border-2 sticky-bottom">
                                            <tr class="fw-bold">
                                                <td colspan="2" class="text-end" style="font-size: 0.8rem; padding: 0.5rem;">TOTAL:</td>
                                                <td class="text-center text-info" style="font-size: 0.85rem; padding: 0.5rem;">
                                                    {{ number_format($totalVolumePagiPos2, 2) }}
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.7rem;">-</td>
                                                <td class="text-center text-warning" style="font-size: 0.85rem; padding: 0.5rem;">
                                                    {{ number_format($totalVolumeSorePos2, 2) }}
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.7rem;">-</td>
                                                <td class="text-center text-dark bg-success bg-opacity-10" style="font-size: 0.9rem; padding: 0.5rem; font-weight: 700;">
                                                    {{ number_format($totalVolumePos2, 2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-center">
                                <small class="text-muted">
                                    <i class="ti ti-droplet me-1"></i>
                                    Total: <strong class="text-primary">{{ number_format($totalVolumePos2, 2) }} Liter</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Button -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                        <i class="ti ti-login me-2"></i>
                        Login untuk Melihat Data Lengkap & Edit
                    </a>
                </div>
            </div>
        </section>
        <!-- CTA: End -->
    </div>

    <!-- / Sections:End -->

    <!-- Footer: Start -->
    <footer class="landing-footer bg-body footer-text">
        <div class="footer-top position-relative overflow-hidden z-1">
            <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg.png') }}" alt="footer bg"
                class="footer-bg banner-bg-img z-n1" />
            <div class="container">
                <div class="row gx-0 gy-6 g-lg-10">
                    <div class="col-lg-5">
                        <a href="landing-page.html" class="app-brand-link mb-6">
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Milk Track"
                                        width="80" height="65" />
                                </span>
                            </span>
                            <span class="app-brand-text demo footer-link fw-bold ms-2 ps-1">MILK TRACK | Sistem Monitoring Penyetoran Susu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom py-3 py-md-5">
            <div
                class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
                <div class="mb-2 mb-md-0">
                    <span class="footer-bottom-text">©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                    </span>
                    <a href="https://polbangtanmalang.ac.id" target="_blank" class="fw-medium text-white">POLBANGTAN</a>
                    <span class="footer-bottom-text"> Made By POLBANGTAN</span>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer: End -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/front-main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/front-page-landing.js') }}"></script>

    <!-- Date Filter JS with AJAX -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalFilter = document.getElementById('tanggalFilter');
        const btnPrevDate = document.getElementById('btnPrevDate');
        const btnNextDate = document.getElementById('btnNextDate');
        const btnToday = document.getElementById('btnToday');
        const section = document.getElementById('landingCTA');

        // Function to load data via AJAX without page reload
        function loadDataForDate(date) {
            // Show loading indicator
            section.style.opacity = '0.6';
            section.style.pointerEvents = 'none';
            
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tanggal', date);
            window.history.pushState({}, '', url);
            
            // Fetch new data
            fetch('{{ route("welcome") }}?tanggal=' + date, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse HTML and extract only the CTA section
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newSection = doc.getElementById('landingCTA');
                
                if (newSection) {
                    // Replace content smoothly
                    section.innerHTML = newSection.innerHTML;
                    
                    // Restore opacity
                    section.style.opacity = '1';
                    section.style.pointerEvents = 'auto';
                    
                    // Reinitialize event listeners for new content
                    initializeDateFilter();
                    
                    // Smooth scroll to section
                    section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                // Fallback to full page reload
                window.location.href = '{{ route("welcome") }}?tanggal=' + date;
            });
        }

        // Initialize date filter controls
        function initializeDateFilter() {
            const tanggalFilter = document.getElementById('tanggalFilter');
            const btnPrevDate = document.getElementById('btnPrevDate');
            const btnNextDate = document.getElementById('btnNextDate');
            const btnToday = document.getElementById('btnToday');
            
            // Date picker change event
            if (tanggalFilter) {
                tanggalFilter.addEventListener('change', function() {
                    loadDataForDate(this.value);
                });
            }

            // Previous date button
            if (btnPrevDate) {
                btnPrevDate.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const currentDate = new Date(tanggalFilter.value);
                    currentDate.setDate(currentDate.getDate() - 1);
                    const newDate = currentDate.toISOString().split('T')[0];
                    tanggalFilter.value = newDate;
                    loadDataForDate(newDate);
                });
            }

            // Next date button
            if (btnNextDate) {
                btnNextDate.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const currentDate = new Date(tanggalFilter.value);
                    currentDate.setDate(currentDate.getDate() + 1);
                    const newDate = currentDate.toISOString().split('T')[0];
                    tanggalFilter.value = newDate;
                    loadDataForDate(newDate);
                });
            }

            // Today button
            if (btnToday) {
                btnToday.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const today = new Date().toISOString().split('T')[0];
                    tanggalFilter.value = today;
                    loadDataForDate(today);
                });
            }
        }
        
        // Initial setup
        initializeDateFilter();

        // ========== ARTICLES CAROUSEL FUNCTIONALITY ==========
        const articlesCarousel = {
            track: document.querySelector('.articles-carousel-track'),
            prevBtn: document.querySelector('.carousel-control-prev-article'),
            nextBtn: document.querySelector('.carousel-control-next-article'),
            indicators: document.querySelectorAll('.indicator-dot'),
            currentSlide: 0,
            totalSlides: 0,
            
            init() {
                if (!this.track) return;
                
                const cards = this.track.querySelectorAll('.article-card-wrapper');
                this.totalSlides = Math.ceil(cards.length / 3);
                
                // Add hover effect to cards
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.querySelector('.card').style.transform = 'translateY(-10px)';
                        this.querySelector('.card').style.boxShadow = '0 10px 30px rgba(105, 108, 255, 0.3)';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        this.querySelector('.card').style.transform = 'translateY(0)';
                        this.querySelector('.card').style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                    });
                });
                
                // Event listeners for navigation
                if (this.prevBtn) {
                    this.prevBtn.addEventListener('click', () => this.prevSlide());
                }
                
                if (this.nextBtn) {
                    this.nextBtn.addEventListener('click', () => this.nextSlide());
                }
                
                // Event listeners for indicators
                this.indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => this.goToSlide(index));
                });
                
                this.updateButtons();
            },
            
            nextSlide() {
                if (this.currentSlide < this.totalSlides - 1) {
                    this.currentSlide++;
                    this.updateCarousel();
                }
            },
            
            prevSlide() {
                if (this.currentSlide > 0) {
                    this.currentSlide--;
                    this.updateCarousel();
                }
            },
            
            goToSlide(slideIndex) {
                this.currentSlide = slideIndex;
                this.updateCarousel();
            },
            
            updateCarousel() {
                const translateX = -this.currentSlide * 100;
                this.track.style.transform = `translateX(${translateX}%)`;
                
                // Update indicators
                this.indicators.forEach((indicator, index) => {
                    if (index === this.currentSlide) {
                        indicator.style.background = '#696cff';
                        indicator.classList.add('active');
                    } else {
                        indicator.style.background = '#ddd';
                        indicator.classList.remove('active');
                    }
                });
                
                this.updateButtons();
            },
            
            updateButtons() {
                // Update prev button
                if (this.prevBtn) {
                    this.prevBtn.style.opacity = this.currentSlide === 0 ? '0.5' : '1';
                    this.prevBtn.style.cursor = this.currentSlide === 0 ? 'not-allowed' : 'pointer';
                }
                
                // Update next button
                if (this.nextBtn) {
                    this.nextBtn.style.opacity = this.currentSlide === this.totalSlides - 1 ? '0.5' : '1';
                    this.nextBtn.style.cursor = this.currentSlide === this.totalSlides - 1 ? 'not-allowed' : 'pointer';
                }
            }
        };
        
        // Initialize articles carousel
        articlesCarousel.init();
        
        // Auto-play carousel (optional, every 5 seconds)
        setInterval(() => {
            if (articlesCarousel.track && articlesCarousel.currentSlide < articlesCarousel.totalSlides - 1) {
                articlesCarousel.nextSlide();
            } else if (articlesCarousel.track) {
                articlesCarousel.goToSlide(0); // Loop back to start
            }
        }, 5000);
    });
    </script>

</body>

</html>