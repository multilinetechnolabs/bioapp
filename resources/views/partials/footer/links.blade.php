@if (!in_array(Route::getCurrentRoute()->uri(), ['home', 'media', 'playlist']) && Auth::user())
    <div class="col-md-5 text-center jp-player-section">
        @include('partials.footer.player')
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top" title="Dashboard / Inicio">
        <a href="{{ route('app.dashboard') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/home80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top" title="Body Scan / Escaneo corporal">
        <a href="{{ route('app.bodyscan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/human-180.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top"
        title="Chakra Scan / Escaneo de chakra">
        <a href="{{ route('app.chakrascan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/human80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top" title="Data Cache / Caché de datos">
        <a href="{{ route('app.data_cache') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/briefcase80.png') }}"
                class="footer-images" alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top" title="Bio Connect / Bio Connect">
        <a href="{{ route('app.bioconnect') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/share80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-1 text-center" data-toggle="tooltip" data-placement="top"
        title="FREE PROTOCOL PAIRS / Pares Gratis de Protocolo">
        <a href="{{ route('app.dr_goiz_pairs') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/more80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
@elseif(in_array(Route::getCurrentRoute()->uri(), ['pricing']))
@else
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top" title="Dashboard / Inicio">
        <a href="{{ route('app.dashboard') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/home80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top" title="Body Scan / Escaneo corporal">
        <a href="{{ route('app.bodyscan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/human-180.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top"
        title="Chakra Scan / Escaneo de chakra">
        <a href="{{ route('app.chakrascan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/human80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top" title="Data Cache / Caché de datos">
        <a href="{{ route('app.data_cache') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/briefcase80.png') }}"
                class="footer-images" alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top" title="Bio Connect / Bio Connect">
        <a href="{{ route('app.bioconnect') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/share80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
    <div class="col-md-2 text-center" data-toggle="tooltip" data-placement="top"
        title="FREE PROTOCOL PAIRS / Pares Gratis de Protocolo">
        <a href="{{ route('app.dr_goiz_pairs') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/more80.png') }}" class="footer-images"
                alt="{{ env('APP_TITLE') }}">
        </a>
    </div>
@endif
