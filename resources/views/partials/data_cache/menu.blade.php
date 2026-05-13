<div class="row row-3" style="margin-bottom: 30px;">
    <div class="col-md-2 text-center">
        <a href="{{ url('/data_cache') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/home.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache' ? 'active' : '' }}">
                <span class="data-cache-label-en">Home</span>
                <span class="data-cache-label-es">Inicio</span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/data_cache/client_info') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/groupicon24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache/client_info' ? 'active' : '' }}">
                <span class="data-cache-label-en">Client Info</span>
                <span class="data-cache-label-es">Información del cliente</span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ route('app.bodyscan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/activity24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache/bio' ? 'active' : '' }}">
                <span class="data-cache-label-en">Bio</span>
                <span class="data-cache-label-es">Bio</span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ route('app.chakrascan') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/activity24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache/chakra' ? 'active' : '' }}">
                <span class="data-cache-label-en">Chakra</span>
                <span class="data-cache-label-es">Chakra</span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/data_cache/preferences') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/friendicon24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache/preferences' ? 'active' : '' }}">
                <span class="data-cache-label-en">Preferences</span>
                <span class="data-cache-label-es">Preferencias</span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/data_cache/help') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/notification24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext data-cache-menu-label {{ Request::path() == 'data_cache/help' ? 'active' : '' }}">
                <span class="data-cache-label-en">Help</span>
                <span class="data-cache-label-es">Ayuda</span>
            </div>
        </a>    
    </div>
</div>
