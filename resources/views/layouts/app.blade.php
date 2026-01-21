<!doctype html>

<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard | MILKTRACK</title>

    <meta name="description" content="" />

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
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }} "></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->


    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }} "></script>
    
    <style>
        /* ========== SIDEBAR & LAYOUT FIX ========== */
        
        /* Sidebar Fixed Positioning */
        .layout-menu {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            z-index: 1039 !important;
            width: 260px !important;
        }
        
        /* Content Area Padding - Desktop */
        .layout-menu-fixed .layout-page {
            padding-left: 260px !important;
            width: 100% !important;
            min-height: 100vh;
        }
        
        /* Collapsed Sidebar State */
        .layout-menu-fixed.layout-menu-collapsed .layout-page {
            padding-left: 80px !important;
        }
        
        .layout-menu-fixed.layout-menu-collapsed .layout-menu {
            width: 80px !important;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1199.98px) {
            .layout-menu-fixed .layout-page {
                padding-left: 0 !important;
            }
            
            .layout-menu {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .layout-menu-expanded .layout-menu {
                transform: translateX(0);
            }
        }
        
        /* Overlay for Mobile Menu */
        .layout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1038;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .layout-menu-expanded .layout-overlay {
            display: block;
            opacity: 1;
        }
        
        @media (min-width: 1200px) {
            .layout-overlay {
                display: none !important;
            }
        }
        
        /* Content Wrapper */
        .content-wrapper {
            padding: 1.5rem;
        }
        
        /* Prevent Horizontal Scroll */
        body, html {
            overflow-x: hidden;
        }
        
        .layout-page {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }} "></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }} "></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }} "></script>

    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }} "></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }} "></script>

    <!-- endbuild -->
    @stack('scripts')

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }} "></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }} "></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }} "></script>
    
    <!-- Main JS -->
    
    <script src="{{ asset('assets/js/main.js') }} "></script>
    
    <!-- Page JS -->
    <script src="{{ asset('assets/js/tables-datatables-extensions.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }} "></script>
    {{-- <script src="{{ asset('assets/js/charts-apex.js') }} "></script> --}}
    
    <!-- Fix overlay and menu toggle issue -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove any stuck overlay classes on page load
            document.documentElement.classList.remove('layout-menu-expanded');
            document.body.classList.remove('layout-menu-expanded');
            
            // Remove any stuck modal backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            
            // Ensure overlay is hidden on desktop
            if (window.innerWidth >= 1200) {
                const overlay = document.querySelector('.layout-overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }
            
            // Get all menu toggle buttons (navbar and sidebar)
            const menuToggleButtons = document.querySelectorAll('.layout-menu-toggle');
            
            // Add click handler to all toggle buttons
            menuToggleButtons.forEach(function(menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (window.innerWidth < 1200) {
                        // Mobile: Toggle menu expansion
                        document.documentElement.classList.toggle('layout-menu-expanded');
                        document.body.classList.toggle('layout-menu-expanded');
                    } else {
                        // Desktop: Toggle menu collapse
                        document.documentElement.classList.toggle('layout-menu-collapsed');
                        document.body.classList.toggle('layout-menu-collapsed');
                    }
                });
            });
            
            // Click overlay to close menu on mobile
            const overlay = document.querySelector('.layout-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    document.documentElement.classList.remove('layout-menu-expanded');
                    document.body.classList.remove('layout-menu-expanded');
                });
            }
            
            // Close menu when window is resized to desktop
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth >= 1200) {
                        document.documentElement.classList.remove('layout-menu-expanded');
                        document.body.classList.remove('layout-menu-expanded');
                        
                        const overlay = document.querySelector('.layout-overlay');
                        if (overlay) {
                            overlay.style.display = 'none';
                        }
                    }
                }, 100);
            });
        });
    </script>
</body>

</html>
