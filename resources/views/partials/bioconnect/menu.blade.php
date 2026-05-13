@if (empty(Auth::user()))
<div class="row row-3 bioconnect-menu">
    <div class="col-md-6 text-center">
        <a href="{{ url('/bioconnect') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/friendicon24.png') }}" class="headerimageicon">
            <div class="headericontext {{ Request::path() == 'bioconnect' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Users</span>
                    <span class="bioconnect-label-es">Usuarios</span>
                </span>
            </div>
        </a>
    </div>
    <div class="col-md-6 text-center">
        <a href="{{ url('/bioconnect/groups') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/groupicon24.png') }}" class="headerimageicon">
            <div class="headericontext {{ Request::path() == 'bioconnect/groups' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Discussions</span>
                    <span class="bioconnect-label-es">Discusiones</span>
                </span>
            </div>
        </a>
    </div>
</div>
@else 
<div class="row row-3 bioconnect-menu">
    <div class="col-md-1"></div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/bioconnect/profile') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/profile24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext {{ Request::path() == 'bioconnect/profile' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Profile</span>
                    <span class="bioconnect-label-es">Perfil</span>
                </span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/bioconnect/activities') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/activity24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext {{ Request::path() == 'bioconnect/activities' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Activities</span>
                    <span class="bioconnect-label-es">Actividades</span>
                </span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/bioconnect/friends') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/friendicon24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext {{ Request::path() == 'bioconnect/friends' || Request::path() == 'bioconnect/friends/find' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Friends</span>
                    <span class="bioconnect-label-es">Amigos</span>
                </span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center">
        <a href="{{ url('/bioconnect/groups') }}">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/groupicon24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext {{ Request::path() == 'bioconnect/groups' ? 'active' : '' }}">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Groups</span>
                    <span class="bioconnect-label-es">Grupos</span>
                </span>
            </div>
        </a>
    </div>
    <div class="col-md-2 text-center"> 		
        <span class="notify-counter" style="display:none;"></span>
        <a id="see_notifi" href="#">
            <img src="{{ \App\Support\VersionedAsset::url('/images/iconimages/notification24.png') }}" class="headerimageicon" alt="{{ env('APP_TITLE') }}">
            <div class="headericontext">
                <span class="bioconnect-bilingual-label bioconnect-bilingual-label--center">
                    <span class="bioconnect-label-en">Notifications</span>
                    <span class="bioconnect-label-es">Notificaciones</span>
                </span>
            </div>
        </a>  
        <ul id="notifi_desc" class="notifi-container"> 
        </ul>		
    </div>
</div>
@endif
