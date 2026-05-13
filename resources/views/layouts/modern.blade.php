<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"@if (!empty($useAppShell)) ng-app="AnewApp" @endif>

<head>
    @if (!empty($useAppShell))
        @include('partials.shared.meta')
    @else
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif

    @php
        $siteTitle = config('app.title');
        $pageTitle = trim((string) $__env->yieldContent('page-title'));
        $isBioconnectRoute = \Illuminate\Support\Str::startsWith(request()->path(), 'bioconnect');

        $currentRouteUri = optional(Route::getCurrentRoute())->uri() ?? '';
        $authUser = Auth::user();
        $showPlayer =
            $authUser &&
            method_exists($authUser, 'hasVerifiedEmail') &&
            $authUser->hasVerifiedEmail() &&
            method_exists($authUser, 'hasValidSubscription') &&
            $authUser->hasValidSubscription() &&
            !in_array($currentRouteUri, ['home', 'media', 'playlist'], true) &&
            (!isset($hideBottomNav) || !$hideBottomNav) &&
            (!isset($hidePlayer) || !$hidePlayer);

        $loadFoot = !empty($useAppShell) || $showPlayer || (!empty($isBioconnectRoute) && Auth::check());
    @endphp
    <title>{{ $pageTitle !== '' ? $pageTitle . ' - ' . $siteTitle : $siteTitle }}</title>

    <link href="{{ \App\Support\VersionedAsset::url('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modern/theme.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body class="@yield('body-class', 'modern-theme')">
    <div class="gtranslate_wrapper"></div>
    <script>
        window.gtranslateSettings = {
            "default_language": "en",
            "detect_browser_language": true,
            "wrapper_selector": ".gtranslate_wrapper",
            "flag_style": "3d"
        }
    </script>
    <script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>

    @if (!isset($hideBrandBar) || !$hideBrandBar)
        @include('partials.modern.brand_bar')
        @include('partials.modern.user_menu')
    @endif

    @if (!empty($isBioconnectRoute))
        @include('partials.modern.bioconnect_subnav')
    @endif

    @if (!empty($isBioconnectRoute) && Auth::check())
        @push('scripts')
            @include('partials.bioconnect.firebase_config')
        @endpush
    @endif

    @yield('content')

    @if (!isset($hideBottomNav) || !$hideBottomNav)
        @include('partials.modern.bottom_nav')
    @endif

    @if ($loadFoot)
        @include('partials.shared.foot')
        <script type="text/javascript">
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    @endif

    @if ($showPlayer)
        <script src="{{ \App\Support\VersionedAsset::url('js/jquery.jplayer.js') }}" type="text/javascript"></script>
        <script src="{{ \App\Support\VersionedAsset::url('js/jplayer.playlist.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var jPlayerConfig = {
                    swfPath: "../../dist/jplayer",
                    supplied: "mp3",
                    wmode: "window",
                    useStateClassSkin: true,
                    autoBlur: true,
                    smoothPlayBar: true,
                    keyEnabled: false,
                    playlistOptions: {
                        autoPlay: false,
                        enableRemoveControls: false
                    },
                    loop: true
                };

                var allMediaUrl = '{{ url('/media/all') }}';
                $.ajax({
                    url: allMediaUrl,
                    dataType: 'json',
                    cache: false
                }).done(function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        new jPlayerPlaylist({
                            jPlayer: "#jquery_jplayer_all",
                            cssSelectorAncestor: "#jp_container_all"
                        }, data, jPlayerConfig);
                        $('#jp_container_all').show();
                        $('body').addClass('player-active');
                    } else {
                        $('.modern-nav-player').hide();
                    }
                }).fail(function(xhr) {
                    $('.modern-nav-player').hide();
                    console.error('Unable to load media playlist.', xhr);
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
