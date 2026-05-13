@auth
@php
    $menuUser = Auth::user();
    $currentRouteName = Route::currentRouteName();
@endphp

<div class="user-menu-backdrop" id="userMenuBackdrop" aria-hidden="true"></div>

<aside class="user-side-menu" id="userSideMenu" aria-label="User menu" role="dialog" aria-modal="true" aria-hidden="true">

    <div class="user-side-menu__header">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $menuUser->profilePictureUrl }}"
                 alt="{{ $menuUser->name }}"
                 class="user-side-menu__avatar">
            <div>
                <div class="user-side-menu__name">{{ $menuUser->name }}</div>
                <div class="user-side-menu__email">{{ $menuUser->email }}</div>
            </div>
        </div>
        <button class="user-side-menu__close" id="userMenuClose" type="button" aria-label="Close menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="user-side-menu__body">
        <ul class="user-side-menu__list">

            <li>
                <a href="{{ route('app.dashboard') }}"
                   class="user-side-menu__link {{ $currentRouteName === 'app.dashboard' ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Home / Inicio
                </a>
            </li>

            <li>
                <a href="{{ route('app.dashboard') }}"
                   class="user-side-menu__link">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="/bioconnect/profile"
                   class="user-side-menu__link {{ request()->is('bioconnect/profile*') ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile Account / Cuenta de perfil
                </a>
            </li>

            <li>
                <a href="/media"
                   class="user-side-menu__link {{ request()->is('media*') ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.899L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    Media Files / Archivos de medios
                </a>
            </li>

            <li>
                <a href="/playlist"
                   class="user-side-menu__link {{ request()->is('playlist*') ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10M15 14l5 3-5 3V14z"/></svg>
                    Playlists / Listas de reproducción
                </a>
            </li>

            <li class="user-side-menu__divider" role="separator"></li>

            <li>
                <a href="{{ route('app.pricing') }}"
                   class="user-side-menu__link {{ $currentRouteName === 'app.pricing' ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    Plans and Pricing / Planes y precios
                </a>
            </li>

            <li>
                <a href="{{ route('app.home') }}#contact" id="sidebarContactLink"
                   class="user-side-menu__link">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact Us / Contáctenos
                </a>
            </li>

            <li>
                <a href="{{ route('app.user.orders') }}"
                   class="user-side-menu__link {{ $currentRouteName === 'app.user.orders' ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    My Orders / Mis pedidos
                </a>
            </li>

            <li>
                <a href="{{ route('app.user.payments') }}"
                   class="user-side-menu__link {{ $currentRouteName === 'app.user.payments' ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    My Payments / Mis pagos
                </a>
            </li>

            <li>
                <a href="{{ route('app.user.subscriptions') }}"
                   class="user-side-menu__link {{ $currentRouteName === 'app.user.subscriptions' ? 'active' : '' }}">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    My Subscriptions / Mis suscripciones
                </a>
            </li>

            @if ($menuUser->isAdmin())
            <li class="user-side-menu__divider" role="separator"></li>
            <li>
                <a href="/admin" class="user-side-menu__link user-side-menu__link--admin">
                    <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Go to Admin Page / Ir a administración
                </a>
            </li>
            @endif

        </ul>
    </nav>

    <div class="user-side-menu__footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="user-side-menu__logout">
                <svg class="user-side-menu__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout / Cerrar sesión
            </button>
        </form>
    </div>

</aside>

<script>
(function () {
    var menu = document.getElementById('userSideMenu');
    var backdrop = document.getElementById('userMenuBackdrop');
    var closeBtn = document.getElementById('userMenuClose');
    var trigger = document.getElementById('userMenuTrigger');

    function openMenu() {
        document.body.classList.add('user-menu-open');
        menu.setAttribute('aria-hidden', 'false');
    }
    function closeMenu() {
        document.body.classList.remove('user-menu-open');
        menu.setAttribute('aria-hidden', 'true');
    }

    if (trigger) trigger.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (backdrop) backdrop.addEventListener('click', closeMenu);

    var contactLink = document.getElementById('sidebarContactLink');
    if (contactLink) {
        contactLink.addEventListener('click', function () { closeMenu(); });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
})();
</script>
@endauth
