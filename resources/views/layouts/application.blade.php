<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="AnewApp">

<head>
    @include('partials.shared.meta')
    @include('partials.shared.link_fonts')

    @section('styles')
        <!-- Styles -->
        <link href="{{ \App\Support\VersionedAsset::url('css/app.css') }}" rel="stylesheet">
    @show

    <title>@yield('page-title')</title>
</head>

<body
    class="main-container {{ in_array(Route::getCurrentRoute()->uri(), ['dashboard', 'introduction', 'data_cache']) ? 'body-inner-brown' : '' }}">
    <div class="gtranslate_wrapper"></div>
    <script type="text/javascript">
        window.gtranslateSettings = {
            "default_language": "en",
            "detect_browser_language": true,
            "wrapper_selector": ".gtranslate_wrapper",
            "switcher_vertical_position": "top",
            "float_switcher_open_direction": "bottom",
            "flag_style": "3d"
        };
    </script>
    <script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>
    <div id="app">
        @yield('content')
        @if (Route::getCurrentRoute()->uri() !== '/')
            @include('partials.footer')
        @endif
    </div>
    @section('javascripts')
        @include('partials.shared.foot')

        <script type="text/javascript">
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();

                var supportsNativeDateInput = (function() {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'date');

                    return input.type === 'date';
                })();

                if (!supportsNativeDateInput) {
                    $('input[type="date"]').each(function(index, element) {
                        var $element = $(element);

                        $element.attr('type', 'text').addClass('date-fallback');
                    });

                    $('.date-fallback').datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                }

                $('.js-datepicker').datepicker({
                    dateFormat: 'mm/dd/yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1900:+0'
                });
            });
        </script>

        @if (
            !in_array(Route::getCurrentRoute()->uri(), ['home', 'media', 'playlist']) &&
                Auth::user() &&
                Auth::user()->hasVerifiedEmail() &&
                Auth::user()->hasValidSubscription())
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
                    }

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
                                }, data,
                                jPlayerConfig);
                            $('#jp_container_all').show();
                        } else {
                            $('#jp_container_all').hide();
                        }
                    }).fail(function(xhr) {
                        $('#jp_container_all').hide();
                        console.error('Unable to load media playlist.', xhr);
                    });
                });
            </script>
        @endif
    @show
</body>

</html>
