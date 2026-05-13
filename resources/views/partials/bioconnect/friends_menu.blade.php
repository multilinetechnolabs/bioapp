@php
    $bcMenuPath = Request::path();
@endphp

<aside class="modern-bioconnect-menu" id="navbarContainer">
    <div class="modern-bioconnect-menu__header">
        <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
            <span class="bioconnect-label-en">Friends</span>
            <span class="bioconnect-label-es">Amigos</span>
        </span>
    </div>
    <ul class="modern-bioconnect-menu__list" id="sidebarWrapper">
        <li>
            <a href="{{ url('/bioconnect/friends') }}"
               class="modern-bioconnect-menu__link {{ $bcMenuPath == 'bioconnect' || $bcMenuPath == 'bioconnect/friends' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">All Friends</span>
                    <span class="bioconnect-label-es">Todos los amigos</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ url('/bioconnect/friends/find') }}"
               class="modern-bioconnect-menu__link {{ $bcMenuPath == 'bioconnect/friends/find' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">Find Friends</span>
                    <span class="bioconnect-label-es">Buscar amigos</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ url('/bioconnect/friends/request') }}"
               class="modern-bioconnect-menu__link {{ $bcMenuPath == 'bioconnect/friends/request' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">Friend Requests</span>
                    <span class="bioconnect-label-es">Solicitudes de amistad</span>
                </span>
            </a>
        </li>
    </ul>
</aside>
