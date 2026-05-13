<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('partials.shared.meta')
        @include('partials.shared.link_fonts')

        @section('styles')
        <link href="{{ \App\Support\VersionedAsset::url('css/app.css') }}" rel="stylesheet">
        <link href="{{ \App\Support\VersionedAsset::url('css/app/admin.css') }}" rel="stylesheet">
        @show

        <title>Admin — {{ config('app.name') }}</title>
    </head>
    <body class="admin-layout">

        {{-- Sidebar --}}
        @include('partials.admin.topnav')

        {{-- Main area --}}
        <div class="admin-main" id="adminMain">

            {{-- Top bar --}}
            <header class="admin-topbar">
                <button class="admin-sidebar-toggle" id="adminSidebarToggle" type="button" aria-label="Toggle sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="admin-topbar__title">@yield('page-title', config('app.name') . ' — Admin')</span>
                <div class="admin-topbar__right">
                    <a href="{{ url('/dashboard') }}" class="admin-topbar__link">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        App
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="admin-topbar__link admin-topbar__logout">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Content --}}
            <div class="admin-content">
                @include('partials.admin.messages')
                @yield('content')
            </div>

        </div>

        @section('javascripts')
        <script src="{{ \App\Support\VersionedAsset::url('js/manifest.js') }}"></script>
        <script src="{{ \App\Support\VersionedAsset::url('js/vendor.js') }}"></script>
        <script src="{{ \App\Support\VersionedAsset::url('js/app.js') }}"></script>
        <script>
        (function () {
            var sidebar   = document.getElementById('adminSidebar');
            var main      = document.getElementById('adminMain');
            var toggle    = document.getElementById('adminSidebarToggle');
            var closeBtn  = document.getElementById('adminSidebarClose');
            var KEY       = 'adminSidebarOpen';
            var BREAKPOINT = 768;

            // Backdrop element for mobile overlay
            var backdrop = document.createElement('div');
            backdrop.id = 'adminMobileBackdrop';
            backdrop.className = 'admin-mobile-backdrop';
            document.body.appendChild(backdrop);

            function isMobile() { return window.innerWidth <= BREAKPOINT; }

            /* ── Desktop logic ── */
            function desktopIsOpen() { return localStorage.getItem(KEY) !== 'false'; }

            function applyDesktop(open) {
                sidebar.classList.toggle('collapsed', !open);
                main.classList.toggle('expanded', !open);
                localStorage.setItem(KEY, open ? 'true' : 'false');
            }

            /* ── Mobile logic ── */
            function mobileSidebarOpen() { return sidebar.classList.contains('mobile-open'); }

            function openMobile() {
                sidebar.classList.add('mobile-open');
                backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobile() {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }

            /* ── Init ── */
            function init() {
                if (isMobile()) {
                    sidebar.classList.remove('collapsed');
                    main.classList.remove('expanded');
                    closeMobile();
                } else {
                    closeMobile();
                    applyDesktop(desktopIsOpen());
                }
            }

            init();

            /* ── Toggle button ── */
            toggle.addEventListener('click', function () {
                if (isMobile()) {
                    mobileSidebarOpen() ? closeMobile() : openMobile();
                } else {
                    applyDesktop(!desktopIsOpen());
                }
            });

            /* ── Close button inside sidebar ── */
            if (closeBtn) closeBtn.addEventListener('click', closeMobile);

            /* ── Backdrop tap closes sidebar on mobile ── */
            backdrop.addEventListener('click', closeMobile);

            /* ── Escape key closes on mobile ── */
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && isMobile()) closeMobile();
            });

            /* ── Re-init on resize ── */
            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    closeMobile();
                    applyDesktop(desktopIsOpen());
                }
            });
        })();
        </script>
        @show
    </body>
</html>
