<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}"@if (!empty($seoPage) && ($locale ?? 'en') !== 'en') class="notranslate"@endif @if (!empty($useAppShell)) ng-app="AnewApp" @endif>

<head>
    @include('partials.shared.meta')

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

        $loadFoot = !empty($useAppShell) || $showPlayer || (!empty($isBioconnectRoute) && Auth::check()) || isset($seoPage);
    @endphp

    <link href="{{ \App\Support\VersionedAsset::url('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modern/theme.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body class="@yield('body-class', 'modern-theme')">
    <div class="gtranslate_wrapper"></div>
    <script>
        window.gtranslateSettings = {
            "default_language": "en",
            "detect_browser_language": {{ !empty($seoPage) ? 'false' : 'true' }},
            "languages": ["en","af","sq","am","ar","hy","az","eu","be","bn","bs","bg","ca","ceb","zh-CN","zh-TW","co","hr","cs","da","nl","eo","et","tl","fi","fr","fy","gl","ka","de","el","gu","ht","ha","haw","he","hi","hmn","hu","is","ig","id","ga","it","ja","jv","kn","kk","km","ko","ku","ky","lo","la","lv","lt","lb","mk","mg","ms","ml","mt","mi","mr","mn","my","ne","no","ny","ps","fa","pl","pt","pa","ro","ru","sm","gd","sr","st","sn","sd","si","sk","sl","so","es","su","sw","sv","tg","ta","te","th","tr","uk","ur","uz","vi","cy","xh","yi","yo","zu"],
            "wrapper_selector": ".gtranslate_wrapper",
            "flag_style": "3d"
        }
    </script>

    {{-- ============================================================================
         GLOBAL LOCALE PERSISTENCE
         The single source of truth for the user's chosen language is the private
         localStorage key `app_locale` ('es' | 'fr' | absent = English). GTranslate
         never reads or writes this key, so it cannot be clobbered when GTranslate
         resets its own googtrans cookie on a notranslate SEO page. The googtrans
         cookie is derived FROM app_locale on every page load so that GTranslate's
         native auto-translate applies the right language on non-SEO pages.
         ============================================================================ --}}
    @if (!empty($seoPage))
    {{-- SEO page: content is server-rendered in the correct locale (notranslate on
         <html>). We use app_locale to drive widget sync and URL navigation. --}}
    <script>
    (function () {
        var host = location.hostname, parts = host.split('.');
        var secure = location.protocol === 'https:' ? ' Secure;' : '';

        function _writeCookie(val) {
            var base = 'googtrans=' + val + '; path=/;';
            document.cookie = base + secure;
            document.cookie = base + ' domain=' + host + ';' + secure;
            document.cookie = base + ' domain=.' + host + ';' + secure;
            if (parts.length > 2) document.cookie = base + ' domain=.' + parts.slice(-2).join('.') + ';' + secure;
        }
        function _clearCookie() {
            var exp = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            document.cookie = exp + secure;
            document.cookie = exp + ' domain=' + host + ';' + secure;
            document.cookie = exp + ' domain=.' + host + ';' + secure;
            if (parts.length > 2) document.cookie = exp + ' domain=.' + parts.slice(-2).join('.') + ';' + secure;
        }
        function _getPref() { try { return localStorage.getItem('app_locale'); } catch (e) { return null; } }
        function _setPref(v) { try { localStorage.setItem('app_locale', v); } catch (e) {} }
        function _clrPref() { try { localStorage.removeItem('app_locale'); } catch (e) {} }

        var _seoPage     = '{{ $seoPage }}';
        var _currentLang = '{{ $locale ?? "en" }}';
        var _urls = {
            'home':                { en: '/home',                es: '/es/home',                fr: '/fr/home' },
            'pricing':             { en: '/pricing',             es: '/es/pricing',             fr: '/fr/pricing' },
            'contact':             { en: '/contact',             es: '/es/contact',             fr: '/fr/contact' },
            'free-protocol-pairs': { en: '/free-protocol-pairs', es: '/es/free-protocol-pairs', fr: '/fr/free-protocol-pairs' }
        };

        if (_currentLang !== 'en') {
            // ES/FR page: record preference + prime cookie for non-SEO pages that come next.
            _setPref(_currentLang);
            _writeCookie('/en/' + _currentLang);
        } else {
            // English SEO URL.
            var _pref = _getPref();
            if (_pref === 'es' || _pref === 'fr') {
                // Redirect to the server-rendered locale URL.
                var _lu = _urls[_seoPage] && _urls[_seoPage][_pref];
                if (_lu && _lu !== location.pathname) { window.location.replace(_lu); return; }
            } else if (_pref && _pref !== 'en') {
                // Any other language: prime cookie so GTranslate translates this English page.
                _writeCookie('/en/' + _pref);
            } else {
                // Genuinely English — clear any stale cookie.
                _clearCookie();
            }
        }

        var _timer = setInterval(function () {
            if (typeof window.doGTranslate !== 'function') return;
            clearInterval(_timer);
            var _orig = window.doGTranslate;

            if (_currentLang !== 'en') {
                // Sync widget flag; notranslate on <html> blocks actual DOM translation.
                _orig('en|' + _currentLang);
            } else {
                // EN URL with a non-es/fr language preference: translate the English content.
                var _timerPref = _getPref();
                if (_timerPref && _timerPref !== 'en' && _timerPref !== 'es' && _timerPref !== 'fr') {
                    _orig('en|' + _timerPref);
                }
            }

            window.doGTranslate = function (pair) {
                var lang = (pair || '').split('|').pop();

                if (lang === 'en') {
                    var _hadPref = _getPref();
                    _clrPref();
                    _clearCookie();
                    var _enUrl = _urls[_seoPage] && _urls[_seoPage]['en'];
                    if (_enUrl && _enUrl !== location.pathname) {
                        window.location.href = _enUrl;
                    } else if (_hadPref && _hadPref !== 'en') {
                        // Was showing translated content — reload to restore original English.
                        window.location.reload();
                    } else {
                        _orig(pair);
                    }
                    return;
                }

                if (lang === 'es' || lang === 'fr') {
                    _setPref(lang);
                    _writeCookie('/en/' + lang);
                    var _dest = _urls[_seoPage] && _urls[_seoPage][lang];
                    if (_dest && _dest !== location.pathname) {
                        window.location.href = _dest;
                    } else {
                        _orig('en|' + lang); // already on correct locale URL
                    }
                    return;
                }

                // Any other language: translate the English URL in-place; navigate there first
                // if currently on an ES/FR locale URL.
                _setPref(lang);
                _writeCookie('/en/' + lang);
                var _enUrl = _urls[_seoPage] && _urls[_seoPage]['en'];
                if (_enUrl && _enUrl !== location.pathname) {
                    window.location.href = _enUrl; // go to EN URL, page load will apply cookie
                } else {
                    _orig(pair); // already on EN URL, translate in-place
                }
            };
        }, 50);
        setTimeout(function () { clearInterval(_timer); }, 10000);
    }());
    </script>
    @endif

    @if (empty($seoPage))
    {{-- Non-SEO page: translation is done live by GTranslate. Read the global
         app_locale preference and prime the googtrans cookie BEFORE GTranslate's
         deferred bundle loads, so its native auto-translate (detect_browser_language
         reads the cookie) applies the right language on first paint. --}}
    <script>
    (function () {
        var host = location.hostname, parts = host.split('.');
        var secure = location.protocol === 'https:' ? ' Secure;' : '';

        function _writeCookie(val) {
            var base = 'googtrans=' + val + '; path=/;';
            document.cookie = base + secure;
            document.cookie = base + ' domain=' + host + ';' + secure;
            document.cookie = base + ' domain=.' + host + ';' + secure;
            if (parts.length > 2) document.cookie = base + ' domain=.' + parts.slice(-2).join('.') + ';' + secure;
        }
        function _clearCookie() {
            var exp = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            document.cookie = exp + secure;
            document.cookie = exp + ' domain=' + host + ';' + secure;
            document.cookie = exp + ' domain=.' + host + ';' + secure;
            if (parts.length > 2) document.cookie = exp + ' domain=.' + parts.slice(-2).join('.') + ';' + secure;
        }

        var _pref = null;
        try { _pref = localStorage.getItem('app_locale'); } catch (e) {}

        // Prime the cookie synchronously BEFORE GTranslate loads for any non-English preference.
        if (_pref && _pref !== 'en') _writeCookie('/en/' + _pref);

        var _timer = setInterval(function () {
            if (typeof window.doGTranslate !== 'function') return;
            clearInterval(_timer);
            var _orig = window.doGTranslate;

            window.doGTranslate = function (pair) {
                var lang = (pair || '').split('|').pop();
                if (lang === 'en') {
                    // GTranslate rewrites DOM in-place with no built-in undo; reload with
                    // /en/en cookie so detect_browser_language doesn't re-translate.
                    try { localStorage.removeItem('app_locale'); } catch (e) {}
                    _writeCookie('/en/en');
                    window.location.reload();
                    return;
                }
                try { localStorage.setItem('app_locale', lang); } catch (e) {}
                _orig(pair);
            };

            // Belt-and-suspenders: apply stored preference once GTranslate is ready.
            if (_pref && _pref !== 'en') _orig('en|' + _pref);
        }, 50);
        setTimeout(function () { clearInterval(_timer); }, 10000);
    }());
    </script>
    @endif

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
    @include('partials.shared.analytics')

    {{-- Rewrite links to the 4 SEO pages so they respect the active GTranslate locale.
         Runs on every page. Uses readyState check to execute immediately when the DOM
         is already available, and a MutationObserver to catch links added dynamically
         by AngularJS after initial render. --}}
    <script>
    (function () {
        // Use app_locale (our private source of truth) rather than the googtrans cookie,
        // which GTranslate may reset on notranslate SEO pages.
        var lang = null;
        try { lang = localStorage.getItem('app_locale'); } catch (e) {}
        if (lang !== 'es' && lang !== 'fr') return;

        var seoPages = ['/home', '/pricing', '/contact', '/free-protocol-pairs'];
        var pfx = '/' + lang;

        function _rewriteLink(a) {
            try {
                var url = new URL(a.href, location.origin);
                if (url.origin !== location.origin) return;
                var path = url.pathname;
                if (/^\/(es|fr)\//.test(path)) return;
                if (seoPages.indexOf(path) !== -1) {
                    a.href = pfx + path + url.search + url.hash;
                }
            } catch (e) {}
        }

        function _rewriteAll() {
            document.querySelectorAll('a[href]').forEach(_rewriteLink);
        }

        // Run immediately if DOM is already parsed; otherwise wait for DOMContentLoaded.
        if (document.readyState !== 'loading') {
            _rewriteAll();
        } else {
            document.addEventListener('DOMContentLoaded', _rewriteAll);
        }

        // Watch for links added dynamically by AngularJS templates.
        var _obs = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i++) {
                var added = mutations[i].addedNodes;
                for (var j = 0; j < added.length; j++) {
                    var node = added[j];
                    if (node.nodeType !== 1) continue;
                    if (node.tagName === 'A') {
                        _rewriteLink(node);
                    } else if (node.querySelectorAll) {
                        node.querySelectorAll('a[href]').forEach(_rewriteLink);
                    }
                }
            }
        });
        var _startObs = function () {
            if (document.body) {
                _obs.observe(document.body, { childList: true, subtree: true });
            }
        };
        if (document.body) {
            _startObs();
        } else {
            document.addEventListener('DOMContentLoaded', _startObs);
        }
    }());
    </script>
</body>

</html>
