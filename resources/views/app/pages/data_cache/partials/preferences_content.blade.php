<div id="tabs">
    <ul>
        <li><a href="#tabs-user">User / Usuario</a></li>
        <li><a href="#tabs-logos">Logos</a></li>
    </ul>
    <div id="tabs-user">
        <div class="alert alert-success alert-preferences-success" style="display: none;">
            <h5><i class="icon fa fa-info"></i> Save Success! / ¡Guardado con éxito! Your preferences have been updated. / Tus preferencias han sido actualizadas.</h5>
        </div>
        <div class="alert alert-danger alert-preferences-error" style="display: none;">
            <h5><i class="icon fa fa-info"></i> Save Failed! / ¡Error al guardar! An error occurred. / Ocurrió un error.</h5>
        </div>
        <form id="form-preferences" method="POST" action="/data_cache/preferences/update">
            @csrf
            <input id="pref_user_id" type="hidden" value="{{ Auth::user()->id }}" />
            <input id="pref_user_email" type="hidden" value="{{ Auth::user()->email }}" />
            <input id="pref_user_name" type="hidden" value="{{ Auth::user()->name }}" />
            <div class="form-group">
                <label for="company_name">Company Name / Nombre de la empresa</label>
                <input type="text" class="form-control" id="pref_company_name" name="pref_company_name" value="{{ Auth::user()->company_name }}" required>
            </div>
            <div class="form-group row">
                <div class="col" style="padding-left: 0;">
                    <label>First Name / Nombre</label>
                    <input type="text" class="form-control" id="pref_first_name" name="pref_first_name" value="{{ Auth::user()->first_name }}" required>
                </div>
                <div class="col" style="padding-right: 0;">
                    <label>Last Name / Apellido</label>
                    <input type="text" class="form-control" id="pref_last_name" name="pref_last_name" value="{{ Auth::user()->last_name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col" style="padding-left: 0;">
                    <label>Phone # / Teléfono</label>
                    <input type="text" class="form-control" id="pref_phone_no" name="pref_phone_no" value="{{ Auth::user()->phone_no }}" required>
                </div>
                <div class="col" style="padding-right: 0;">
                    <label>Fax # / Fax</label>
                    <input type="text" class="form-control" id="pref_fax_no" name="pref_fax_no" value="{{ Auth::user()->fax_no }}">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email / Correo electrónico</label>
                <input type="text" class="form-control" id="pref_alternate_email" name="pref_alternate_email" value="{{ Auth::user()->alternate_email ? Auth::user()->alternate_email : Auth::user()->email }}" required>
            </div>
            <div class="form-group">
                <label for="billing_title">Billing Title / Título de facturación</label>
                <input type="text" class="form-control" id="pref_billing_title" name="pref_billing_title" value="{{ Auth::user()->billing_title }}" required>
            </div>
            <div class="form-group">
                <label for="address">Address / Dirección</label>
                <input type="text" class="form-control" id="pref_address" name="pref_address" value="{{ Auth::user()->address }}" required>
            </div>
            <button type="button" id="submit_pref" class="form-control btn save-btn">Save / Guardar</button>
        </form>
    </div>
    <div id="tabs-logos">
        <form method="POST" enctype="multipart/form-data" id="logoForm" action="{{ url('/data_cache/upload_logo') }}">
            @csrf
            <div id="form-part" style="display: inline-flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <label class="bilingual-file-label" for="logo_file" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;">
                    <span class="bilingual-file-btn-text" style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:4px;padding:6px 12px;font-size:0.875rem;white-space:nowrap;">Choose File / Elegir archivo</span>
                    <span id="logo_file_name" style="font-size:0.82rem;color:#64748b;">No file chosen / Ningún archivo elegido</span>
                </label>
                <input type="file" id="logo_file" name="logo_file" accept=".jpg,.jpeg,.png" required="" style="display:none;" onchange="document.getElementById('logo_file_name').textContent = this.files[0] ? this.files[0].name : 'No file chosen / Ningún archivo elegido'">
                <button type="submit" id="logoSubmit" class="form-control btn btn-primary">Upload / Subir</button>
            </div>
            <div id="loader-part" style="display: none;">
                <br/><br/><div class="loader" style="margin:0 auto;"></div><br/>
                <p style="text-align:center">{{ __('Please wait... file upload is still being processed...') }}</p><br/>
            </div>
            <div>
                <div class="row" style="margin-top: 10px;">
                    @foreach(\App\Models\Logo::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $logo)
                        <div style="display: grid; margin: 5px;">
                            <img src="{{ $logo->file_url() }}" class="logo-box {{ Auth::user()->logo == $logo->s3_name ? 'active' : '' }}" alt="{{ env('APP_TITLE') }}" data-logo="{{ $logo->s3_name }}" data-user-id="{{ Auth::user()->id }}" />
                            @if (Auth::user()->logo != $logo->s3_name)
                                <button class="btn-danger logo-delete" style="cursor: pointer; width: 100%;" data-logo-id="{{ $logo->id }}">Delete / Eliminar</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</div>
