@php
    $bcPath = request()->path();
    $iconBase = \App\Support\VersionedAsset::url('/images/iconimages');

    if (Auth::check()) {
        $activeProfile = $bcPath === 'bioconnect/profile';
        $activeActivities = $bcPath === 'bioconnect/activities';
        $activeFriends = in_array($bcPath, ['bioconnect', 'bioconnect/friends', 'bioconnect/friends/find', 'bioconnect/friends/request'], true);
        $activeGroups = \Illuminate\Support\Str::startsWith($bcPath, 'bioconnect/groups');
        $activeUsers = false;
        $activeDiscussionsGuest = false;
    } else {
        $activeUsers = $bcPath === 'bioconnect' || $bcPath === 'bioconnect/friends';
        $activeDiscussionsGuest = \Illuminate\Support\Str::startsWith($bcPath, 'bioconnect/groups');
        $activeProfile = $activeActivities = $activeFriends = $activeGroups = false;
    }
@endphp

<nav class="modern-bioconnect-subnav" aria-label="Bio Connect">
    <div class="modern-bioconnect-subnav__inner">
        @guest
            <ul class="modern-bioconnect-subnav__list modern-bioconnect-subnav__list--guest">
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect') }}" class="modern-bioconnect-subnav__link {{ $activeUsers ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/friendicon24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Users</span>
                            <span class="modern-bioconnect-subnav__label-es">Usuarios</span>
                        </span>
                    </a>
                </li>
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect/groups') }}" class="modern-bioconnect-subnav__link {{ $activeDiscussionsGuest ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/groupicon24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Discussions</span>
                            <span class="modern-bioconnect-subnav__label-es">Discusiones</span>
                        </span>
                    </a>
                </li>
            </ul>
        @else
            <ul class="modern-bioconnect-subnav__list">
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect/profile') }}" class="modern-bioconnect-subnav__link {{ $activeProfile ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/profile24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Profile</span>
                            <span class="modern-bioconnect-subnav__label-es">Perfil</span>
                        </span>
                    </a>
                </li>
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect/activities') }}" class="modern-bioconnect-subnav__link {{ $activeActivities ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/activity24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Activities</span>
                            <span class="modern-bioconnect-subnav__label-es">Actividades</span>
                        </span>
                    </a>
                </li>
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect/friends') }}" class="modern-bioconnect-subnav__link {{ $activeFriends ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/friendicon24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Friends</span>
                            <span class="modern-bioconnect-subnav__label-es">Amigos</span>
                        </span>
                    </a>
                </li>
                <li class="modern-bioconnect-subnav__item">
                    <a href="{{ url('/bioconnect/groups') }}" class="modern-bioconnect-subnav__link {{ $activeGroups ? 'is-active' : '' }}">
                        <img src="{{ $iconBase }}/groupicon24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Groups</span>
                            <span class="modern-bioconnect-subnav__label-es">Grupos</span>
                        </span>
                    </a>
                </li>
                <li class="modern-bioconnect-subnav__item modern-bioconnect-subnav__item--notifications">
                    <span class="notify-counter" style="display:none;"></span>
                    <a id="see_notifi" href="#" class="modern-bioconnect-subnav__link">
                        <img src="{{ $iconBase }}/notification24.png" width="24" height="24" class="modern-bioconnect-subnav__icon" alt="">
                        <span class="modern-bioconnect-subnav__labels">
                            <span class="modern-bioconnect-subnav__label-en">Notifications</span>
                            <span class="modern-bioconnect-subnav__label-es">Notificaciones</span>
                        </span>
                    </a>
                    <ul id="notifi_desc" class="notifi-container modern-bioconnect-subnav__notifi-dropdown"></ul>
                </li>
            </ul>
        @endguest
    </div>
</nav>
