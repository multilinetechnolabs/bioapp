@php
    $bcDiscussPath = Request::path();
@endphp

<aside class="modern-bioconnect-menu dis_menucontainer">
    <div class="modern-bioconnect-menu__header">
        <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
            <span class="bioconnect-label-en">Discussions</span>
            <span class="bioconnect-label-es">Discusiones</span>
        </span>
    </div>
    <ul class="modern-bioconnect-menu__list">
        <li>
            <a href="{{ url('/bioconnect/groups') }}"
               class="modern-bioconnect-menu__link {{ $bcDiscussPath == 'bioconnect/groups' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">Recent Discussions</span>
                    <span class="bioconnect-label-es">Discusiones recientes</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ url('/bioconnect/groups/mostcomments') }}"
               class="modern-bioconnect-menu__link {{ $bcDiscussPath == 'bioconnect/groups/mostcomments' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">Most Comments</span>
                    <span class="bioconnect-label-es">Más comentarios</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ url('/bioconnect/groups/mydiscussion') }}"
               class="modern-bioconnect-menu__link {{ $bcDiscussPath == 'bioconnect/groups/mydiscussion' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">My Discussions</span>
                    <span class="bioconnect-label-es">Mis discusiones</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ url('/bioconnect/groups/mycomment') }}"
               class="modern-bioconnect-menu__link {{ $bcDiscussPath == 'bioconnect/groups/mycomment' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--left">
                    <span class="bioconnect-label-en">My Comments</span>
                    <span class="bioconnect-label-es">Mis comentarios</span>
                </span>
            </a>
        </li>
    </ul>
</aside>
