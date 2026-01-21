<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo Milk Track" width="80" height="65" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-1 fs-6">MILK TRACK</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti tabler-x d-block d-xl-none"></i>
            <i class="ti tabler-circle d-none d-xl-block"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ">
        {{-- Menu untuk ADMIN --}}
        @if(auth()->check() && auth()->user()->isAdmin())
            
            {{-- ========== SUPER ADMIN ONLY ========== --}}
            @if(auth()->user()->isSuperAdmin())
                <li class="menu-header mt-7">
                    <span class="menu-header-text">Manajemen</span>
                </li>

                {{-- Manajemen User - SUPER ADMIN ONLY --}}
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <i class="menu-icon ti tabler-users"></i>
                        <div data-i18n="Users">Manajemen User</div>
                    </a>
                </li>

                {{-- Penyetoran Harian --}}
                <li class="menu-item {{ request()->routeIs('penyetoran.*') ? 'active' : '' }}">
                    <a href="{{ route('penyetoran.index') }}" class="menu-link">
                        <i class="menu-icon ti tabler-milk"></i>
                        <div>Penyetoran Harian</div>
                    </a>
                </li>

                {{-- Artikel - CRUD Full --}}
                <li class="menu-item {{ request()->routeIs('articles.index', 'articles.create', 'articles.edit') ? 'active' : '' }}">
                    <a href="{{ route('articles.index') }}" class="menu-link">
                        <i class="menu-icon ti tabler-news"></i>
                        <div>Manajemen Artikel</div>
                    </a>
                </li>
            @endif

            {{-- ========== ADMIN POS ONLY ========== --}}
            @if(auth()->user()->isAdminPos())
                <li class="menu-header mt-7">
                    <span class="menu-header-text">Menu Utama</span>
                </li>

                {{-- Penyetoran Harian --}}
                <li class="menu-item {{ request()->routeIs('penyetoran.*') ? 'active' : '' }}">
                    <a href="{{ route('penyetoran.index') }}" class="menu-link">
                        <i class="menu-icon ti tabler-milk"></i>
                        <div>Penyetoran Harian</div>
                    </a>
                </li>

                <li class="menu-header mt-7">
                    <span class="menu-header-text">D-Learn</span>
                </li>

                {{-- Artikel - READ ONLY --}}
                <li class="menu-item {{ request()->routeIs('articles.list') ? 'active' : '' }}">
                    <a href="{{ route('articles.list') }}" class="menu-link">
                        <i class="menu-icon ti tabler-news"></i>
                        <div>Artikel & Informasi</div>
                        <span class="badge bg-info rounded-pill ms-auto" style="font-size: 0.65rem;">Baca</span>
                    </a>
                </li>
            @endif

        {{-- Menu untuk PETERNAK --}}
        @elseif(auth()->check() && auth()->user()->isPeternak())
            {{-- Menu peternak di sini --}}
        @endif
    </ul>
</aside>