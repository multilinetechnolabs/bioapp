@extends('layouts.modern')

@section('page-title', 'Bio Connect Profile')

@php
    $activeNav = 'connect';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect.css') }}" rel="stylesheet">
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect/profile.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title">Bio Connect Profile / Perfil de Bio Connect</h1>
                <p class="modern-page-subtitle">Manage your shared profile and privacy / Gestiona tu perfil y privacidad</p>
            </div>
            <div class="modern-page-header__actions">
                <a href="{{ url('/bioconnect/friends') }}" class="modern-btn modern-btn--outline">
                    <span aria-hidden="true">&larr;</span> Back to Bio Connect / Volver
                </a>
            </div>
        </header>

        <section class="modern-info-card modern-bioconnect-profile">
            <div class="text-center mb-4">
                <a href="">
                    <img src="{{ $pro_info->profilePictureUrl() }}"
                         class="profile-image modern-bioconnect-profile__avatar"
                         alt="{{ env('APP_TITLE') }}">
                </a>
            </div>

            <form id="form-post" method="post" action="{{ URL::route('saveprofile') }}"
                  enctype="multipart/form-data" class="modern-bioconnect-profile__form">
                @csrf
                <input hidden id="user_id" name="user_id" type="text" value="{{ Auth::user()->id }}" />

                <div class="form-group row">
                    <label for="upload" class="col-sm-3 col-form-label">Profile Picture / Foto de perfil</label>
                    <div class="col-sm-9">
                        <label class="bilingual-file-label d-inline-flex align-items-center" style="gap:8px;cursor:pointer;flex-wrap:wrap;">
                            <span style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:4px;padding:6px 12px;font-size:0.875rem;white-space:nowrap;">Choose File / Elegir archivo</span>
                            <span id="upload_file_name" style="font-size:0.82rem;color:#64748b;">No file chosen / Ningún archivo elegido</span>
                        </label>
                        <input id="upload" name="upload" type="file" class="form-control-file" style="display:none;" onchange="document.getElementById('upload_file_name').textContent = this.files[0] ? this.files[0].name : 'No file chosen / Ningún archivo elegido'">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="togBtn" class="col-sm-3 col-form-label">Privacy / Privacidad</label>
                    <div class="col-sm-9">
                        <label class="switch mb-0">
                            <input type="checkbox" id="togBtn" name="togBtn" {{ Auth::user()->privacy ? 'checked' : '' }}>
                            <div class="slider round"></div>
                            @error('privacy')
                                <strong class="invalid">{{ $errors->first('privacy') }}</strong>
                            @enderror
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Name / Nombre</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="your name here" value="{{ Auth::user()->name }}" required>
                        @error('name')
                            <strong class="invalid">{{ $errors->first('name') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Email ID / Correo</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="your email here" value="{{ Auth::user()->email }}" required>
                        @error('email')
                            <strong class="invalid">{{ $errors->first('email') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="business" class="col-sm-3 col-form-label">Business / Negocio</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="business" name="business"
                               placeholder="your business name here" value="{{ Auth::user()->business }}">
                        @error('business')
                            <strong class="invalid">{{ $errors->first('business') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="age" class="col-sm-3 col-form-label">Age / Edad</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="age" name="age"
                               placeholder="age" value="{{ Auth::user()->age }}" min="0">
                        @error('age')
                            <strong class="invalid">{{ $errors->first('age') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="location" class="col-sm-3 col-form-label">Location / Ubicación</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="location" name="location"
                               placeholder="your location here" value="{{ Auth::user()->location }}">
                        @error('location')
                            <strong class="invalid">{{ $errors->first('location') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-3 col-form-label">Address / Dirección</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="address" name="address"
                               placeholder="your address here" value="{{ Auth::user()->address }}">
                        @error('address')
                            <strong class="invalid">{{ $errors->first('address') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="country" class="col-sm-3 col-form-label">Country / País</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="country" name="country"
                               placeholder="country" value="{{ Auth::user()->country }}">
                        @error('country')
                            <strong class="invalid">{{ $errors->first('country') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zip" class="col-sm-3 col-form-label">Zip / Código postal</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="zip" name="zip"
                               placeholder="zip" value="{{ Auth::user()->zip }}">
                        @error('zip')
                            <strong class="invalid">{{ $errors->first('zip') }}</strong>
                        @enderror
                    </div>
                </div>

                <div class="modern-bioconnect-profile__actions">
                    <button type="submit" id="submit" class="modern-btn modern-btn--primary">
                        {{ __('Save') }} / Guardar
                    </button>
                </div>
            </form>
        </section>

        <section class="modern-info-card modern-bioconnect-profile mt-4" id="change-password">
            <h5 class="mb-4" style="font-weight:600;">Change Password / Cambiar contraseña</h5>

            @if (session('password.success'))
                <div class="alert alert-success">{{ session('password.success') }}</div>
            @endif

            @if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
                <div class="alert alert-danger">
                    @foreach (['current_password', 'new_password', 'new_password_confirmation'] as $field)
                        @error($field)
                            <div>{{ $message }}</div>
                        @enderror
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('bioconnect.profile.password') }}" class="modern-bioconnect-profile__form">
                @csrf

                <div class="form-group row">
                    <label for="current_password" class="col-sm-3 col-form-label">
                        Current Password / Contraseña actual
                    </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required autocomplete="current-password">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_password" class="col-sm-3 col-form-label">
                        New Password / Nueva contraseña
                    </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                               id="new_password" name="new_password" required autocomplete="new-password"
                               minlength="8">
                        <small class="form-text text-muted">Minimum 8 characters / Mínimo 8 caracteres</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="new_password_confirmation" class="col-sm-3 col-form-label">
                        Confirm New Password / Confirmar nueva contraseña
                    </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="modern-bioconnect-profile__actions">
                    <button type="submit" class="modern-btn modern-btn--primary">
                        Update Password / Actualizar contraseña
                    </button>
                </div>
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {

            $('#form-post').on('submit',function(event){

                if($("#togBtn").is(":checked")){
                    var privacy = 1;
                }else{ privacy = 0;}

                var uage = $('#age').val();
                var ucountry = $('#country').val();
                var ulocation = $('#location').val();
                var uaddress = $('#address').val();
                var uzip = $('#zip').val();
                var ulogo = $('#upload').val() || '';
                var logo_name = '';
                var uname = $('#name').val();
                if (ulogo !== '') {
                    var fileExtension = ulogo.replace(/^.*\./, '');
                    logo_name = 'user-{{Auth::user()->id}}.'+fileExtension;
                }
                var fbinitupdataeuser = firebase.database().ref().child('post').orderByChild("post_byId").equalTo("{{Auth::user()->id}}");

                var fbinitcommentsupdate = firebase.database().ref().child('post');
                var fbinituserfriendupdate = firebase.database().ref().child('friends');

                fbinituserfriendupdate.on("value", function (snap3) {
                    snap3.forEach(function (childsnap3) {
                        var userParentId = childsnap3.key;
                        var userChildValue = childsnap3.val();
                        if( userParentId != "{{Auth::user()->id}}" ){
                            var userarray = Object.keys(userChildValue);
                            userarray.forEach(function(element) {
                                if(element === "{{Auth::user()->id}}"){

                                    firebase.database().ref().child('friends/'+ userParentId + '/' + element ).update({
                                        name: uname,
                                        age: uage,
                                        address: uaddress,
                                        country: ucountry,
                                        location: ulocation,
                                        zip: uzip,
                                        privacy: privacy
                                    });

                                    if( ulogo != ''){
                                        firebase.database().ref().child('friends/'+ userParentId + '/' + element ).update({
                                            logo: logo_name
                                        });
                                    }

                                }
                            });
                        }
                    });
                });

                fbinitupdataeuser.on("value", function (snap) {
                    snap.forEach(function (childsnap) {
                        firebase.database().ref().child('post/'+ childsnap.key ).update({
                            post_byName: uname
                        });
                        if( ulogo != ''){
                            firebase.database().ref().child('post/'+ childsnap.key ).update({
                                user_Logo: logo_name
                            });
                        }
                    });
                });

                fbinitcommentsupdate.on("value", function (snap) {
                    snap.forEach(function (childsnap) {
                        var parentkey = childsnap.key;
                        var fbinitcommentkey = firebase.database().ref().child('post/'+ parentkey +'/comments');

                        fbinitcommentkey.on("value", function (snap2) {
                            var getchild = snap2.val();
                            if(getchild != ''){
                                snap2.forEach(function (childsnap2) {
                                    var gettargetinfo = childsnap2.val();
                                    if( gettargetinfo.comment_byId === "{{Auth::user()->id}}" ){

                                        firebase.database().ref().child('post/'+ parentkey + '/comments/' + childsnap2.key ).update({
                                            comment_byName: uname
                                        });

                                        if( ulogo != ''){
                                            firebase.database().ref().child('post/'+ parentkey + '/comments/' + childsnap2.key ).update({
                                                user_Logo: logo_name
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    });
                });

                alert("success");
            });

        });
    </script>
@endpush
