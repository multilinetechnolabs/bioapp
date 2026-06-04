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

    <footer class="modern-legal-footer text-center py-2">
        <small>
            <a href="{{ route('app.terms') }}">Terms of Service</a> |
            <a href="{{ route('app.privacy') }}">Privacy Policy</a> |
            <a href="{{ route('app.refund-policy') }}">Refund Policy</a>
        </small>
        <div class="disclaimer-modal__body">
            <p class="disclaimer-modal__text">
                <strong>Disclaimer:</strong> This app is for self-education and research purposes only. Biomagnetic pairs are
                intended as biofeedback to support normal body function and are not a treatment, diagnosis, or prescription for any medical or psychological condition. These statements have not been evaluated by the FDA, and this tool is not intended to support or sustain human life or prevent health impairment. Users with existing medical conditions use this platform at their own risk.
            </p>
            <hr class="disclaimer-modal__divider">
            <p class="disclaimer-modal__text">
                <strong>Important Safety Notice:</strong> Do not use magnets if you have electronic medical implants, are pregnant, are currently undergoing chemotherapy or radiation, have active cancer, or suffer from severe low blood pressure.
            </p>
             <hr class="disclaimer-modal__divider">
             <p class="disclaimer-modal__text">
                <strong>Aviso legal:</strong> legal: Esta aplicación es solo para fines de autoeducación e investigación. Los pares biomagnéticos están destinados a ser una bioretroalimentación para apoyar el funcionamiento normal del cuerpo y no son un tratamiento, diagnóstico o prescripción para ninguna condición médica o psicológica. Estas declaraciones no han sido evaluadas por la FDA, y esta herramienta no está destinada a apoyar o sostener la vida humana ni a prevenir el deterioro de la salud. Los usuarios con condiciones médicas existentes utilizan esta plataforma bajo su propio riesgo.
            </p>
             <hr class="disclaimer-modal__divider">
            <p class="disclaimer-modal__text">
                <strong>Aviso de seguridad importante:</strong>No utilice imanes si tiene implantes médicos electrónicos,está embarazada, está recibiendo quimioterapia o radiación, tiene cáncer activo o sufre de presión arterial muy baja.
            </p>
             <hr class="disclaimer-modal__divider">
             <p><strong>© 2026 Anew Avenue Biomagnetism. All rights reserved.</strong></p>
        </div>
    </footer>

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
                var jPlayerStateKey = 'jplayerPlaylistState';
                var playerSelector = "#jquery_jplayer_all";
                var containerSelector = "#jp_container_all";

                function loadSavedJPlayerState() {
                    try {
                        return JSON.parse(localStorage.getItem(jPlayerStateKey)) || null;
                    } catch (e) {
                        return null;
                    }
                }

                function saveJPlayerState(state) {
                    localStorage.setItem(jPlayerStateKey, JSON.stringify(state));
                }

                function normalizeSrc(src) {
                    if (!src) { return '' }
                    try {
                        return new URL(src, location.origin).href.replace(/#.*$/, '');
                    } catch (e) {
                        return ('' + src).trim();
                    }
                }

                function findTrackIndex(playlist, trackSrc) {
                    if (!Array.isArray(playlist) || !playlist.length) {
                        return -1;
                    }
                    var target = normalizeSrc(trackSrc || '');
                    for (var i = 0; i < playlist.length; i++) {
                        var item = playlist[i] || {};
                        var candidates = [item.mp3, item.oga, item.m4a, item.wav, item.src];
                        for (var j = 0; j < candidates.length; j++) {
                            if (!candidates[j]) { continue; }
                            if (normalizeSrc(candidates[j]) === target) {
                                return i;
                            }
                        }
                    }
                    return -1;
                }

                function captureJPlayerState(playlistInstance) {
                    var status = $(playerSelector).data('jPlayer') && $(playerSelector).data('jPlayer').status;
                    if (!status || !playlistInstance) {
                        return;
                    }

                    var currentMedia = playlistInstance.playlist[playlistInstance.current] || {};
                    var currentSrc = status.src || currentMedia.mp3 || currentMedia.oga || currentMedia.m4a || currentMedia.wav || '';

                    var playlistOrder = (playlistInstance.playlist || []).map(function(it) {
                        return normalizeSrc(it && (it.mp3 || it.oga || it.m4a || it.wav || it.src) || '');
                    });

                    saveJPlayerState({
                        currentTrackIndex: playlistInstance.current,
                        currentTrackSrc: currentSrc,
                        currentTime: status.currentTime || 0,
                        isPlaying: !status.paused,
                        volume: status.volume,
                        isLoop: status.loop,
                        isShuffled: !!playlistInstance.shuffled,
                        playlistLength: playlistInstance.playlist.length,
                        playlistOrder: playlistOrder
                    });
                }

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

                var savedState = loadSavedJPlayerState();
                var allMediaUrl = '{{ url('/media/all') }}';
                
                if (savedState && savedState.currentTrackSrc) {
                    allMediaUrl += '?currentTrackSrc=' + encodeURIComponent(savedState.currentTrackSrc);
                }

                $.ajax({
                    url: allMediaUrl,
                    dataType: 'json',
                    cache: false
                }).done(function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        var playlist;
                        var restoreIndex = 0;
                        var restored = false;

                        function restoreState() {
                            if (restored || !playlist) {
                                return;
                            }
                            restored = true;

                            if (savedState && typeof savedState.volume === 'number') {
                                $(playerSelector).jPlayer("volume", Math.max(0, Math.min(1, savedState.volume)));
                            }

                            if (savedState && typeof savedState.isLoop === 'boolean') {
                                $(playerSelector).jPlayer("option", "loop", savedState.isLoop);
                            }

                            if (savedState) {
                                var foundIndex = -1;
                                if (savedState.currentTrackSrc) {
                                    foundIndex = findTrackIndex(playlist.playlist, savedState.currentTrackSrc);
                                }

                                if (savedState.playlistOrder && Array.isArray(savedState.playlistOrder) && savedState.playlistOrder.length === playlist.playlist.length) {
                                    var desiredOrder = savedState.playlistOrder.slice();
                                    var newPlaylist = [];
                                    for (var oi = 0; oi < desiredOrder.length; oi++) {
                                        var want = desiredOrder[oi];
                                        var idx = -1;
                                        for (var pi = 0; pi < playlist.playlist.length; pi++) {
                                            var it = playlist.playlist[pi] || {};
                                            var itSrc = normalizeSrc(it.mp3 || it.oga || it.m4a || it.wav || it.src || '');
                                            if (itSrc === want) { idx = pi; break; }
                                        }
                                        if (idx > -1) { newPlaylist.push(playlist.playlist[idx]); }
                                    }
                                    if (newPlaylist.length === playlist.playlist.length) {
                                        playlist.playlist = newPlaylist;
                                        playlist._refresh(true);
                                        playlist._updateControls();

                                        if (savedState.currentTrackSrc) {
                                            foundIndex = findTrackIndex(playlist.playlist, savedState.currentTrackSrc);
                                        }
                                    }
                                }

                                if (foundIndex >= 0) {
                                    restoreIndex = foundIndex;
                                } else {
                                    restoreIndex = Math.max(0, Math.min(parseInt(savedState.currentTrackIndex, 10) || 0, playlist.playlist.length - 1));
                                }
                            }
                            
                            if (savedState && savedState.isPlaying) {
                                playlist.play(restoreIndex);
                            } else {
                                playlist.select(restoreIndex);
                            }

                            if (savedState) {
                                var seekTime = Math.max(0, savedState.currentTime || 0);
                                if (seekTime > 0) {
                                    $(playerSelector).jPlayer(savedState.isPlaying ? "play" : "pause", seekTime);
                                }
                            }
                        }

                        playlist = new jPlayerPlaylist({
                            jPlayer: playerSelector,
                            cssSelectorAncestor: containerSelector
                        }, data, jPlayerConfig);

                        if (savedState) {
                            $(playerSelector).one($.jPlayer.event.loadeddata, restoreState);
                            setTimeout(restoreState, 250);
                        }
                        
                        $(window).on('beforeunload', function(e) {
                            captureJPlayerState(playlist);
                        });

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
