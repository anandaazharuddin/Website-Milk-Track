<!doctype html>

<html lang="en" class="layout-navbar-fixed layout-wide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets/') }}/" data-template="front-pages" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $article->title }} - Milk Track</title>

    <meta name="description" content="{{ $article->excerpt }}" />

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
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/front-config.js') }}"></script>

    <style>
        .article-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 0;
            color: white;
            margin-bottom: 3rem;
        }

        .article-image {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 2rem 0;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .article-content h1, .article-content h2, .article-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #696cff;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-button {
            margin-bottom: 2rem;
        }

        .related-articles {
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 2px solid #e0e0e0;
        }
    </style>
</head>

<body>
    <!-- Navbar: Start -->
    <nav class="layout-navbar shadow-none py-0">
        <div class="container">
            <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8 ms-0">
                    <a href="{{ route('welcome') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Milk Track" width="80"
                                height="65" />
                        </span>
                        <span class="app-brand-text demo menu-text fw-semibold ms-2 ps-1 fs-6 text-wrap">
                            MILK TRACK
                        </span>
                    </a>
                </div>
                
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                            <span style="font-size: 1.2rem;">←</span> Kembali ke Beranda
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar: End -->

    <!-- Article Header -->
    <div class="article-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="display-4 fw-bold mb-3">{{ $article->title }}</h1>
                    <p class="lead mb-0">{{ $article->excerpt }}</p>
                    
                    <div class="article-meta">
                        <div class="article-meta-item">
                            <span style="font-size: 1.2rem;">👤</span>
                            <span>{{ $article->author->name }}</span>
                        </div>
                        <div class="article-meta-item">
                            <span style="font-size: 1.2rem;">📅</span>
                            <span>{{ $article->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        <div class="article-meta-item">
                            <span style="font-size: 1.2rem;">🕐</span>
                            <span>{{ $article->created_at->locale('id')->isoFormat('HH:mm') }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($article->image)
                    <img src="{{ Storage::url($article->image) }}" 
                         alt="{{ $article->title }}" 
                         class="article-image">
                @endif

                <div class="article-content">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <!-- Share & Back Section -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                    <a href="{{ route('welcome') }}#landingArticles" class="btn btn-primary">
                        <span style="font-size: 1.2rem;">←</span> Kembali ke Artikel Lainnya
                    </a>
                    
                    <div>
                        <span class="text-muted me-3">Bagikan:</span>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . route('article.show', $article->slug)) }}" 
                           target="_blank" 
                           class="btn btn-success btn-sm">
                            WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('article.show', $article->slug)) }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="landing-footer bg-body footer-text mt-5">
        <div class="footer-bottom py-3">
            <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
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

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/js/front-main.js') }}"></script>
</body>

</html>
