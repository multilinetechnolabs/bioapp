<!-- Client Info Modal -->
<style>
    #clientInfoModal .modal-content {
        border-radius: 8px;
        overflow: hidden;
    }

    #clientInfoModal .modal-header {
        align-items: flex-start;
        padding: 22px 26px 18px;
    }

    #clientInfoModal .modal-body {
        padding: 26px;
    }

    #clientInfoModal .modal-footer {
        align-items: stretch;
        gap: 12px;
        padding: 24px 26px;
    }

    #clientInfoModal .client-info-feedback {
        margin-bottom: 16px;
    }

    #clientInfoModal .client-info-feedback ul {
        margin: 8px 0 0 18px;
        padding: 0;
    }

    #clientInfoModal .field-label,
    #clientInfoModal .checkbox-label,
    #clientInfoModal .btn-label,
    #clientInfoModal .save-btn-label {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    #clientInfoModal .field-label .label-en,
    #clientInfoModal .checkbox-label .label-en,
    #clientInfoModal .btn-label .label-en,
    #clientInfoModal .save-btn-label .label-en {
        color: #111;
        display: block;
        order: 1;
    }

    #clientInfoModal .modal-title {
        color: #000;
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        font-size: 1.9rem;
        font-weight: 400;
        line-height: 1.1;
        margin: 0;
    }

    #clientInfoModal .modal-title-en,
    #clientInfoModal .modal-title-es {
        display: block;
    }

    #clientInfoModal .modal-title-en {
        color: #000;
    }

    #clientInfoModal .field-label {
        color: #111;
        font-size: 0.95rem;
        font-weight: 400;
        margin-bottom: 10px;
    }

    #clientInfoModal .field-label .translation,
    #clientInfoModal .checkbox-label .translation,
    #clientInfoModal .btn-label .translation,
    #clientInfoModal .save-btn-label .translation {
        color: #16a34a;
        display: block;
        font-size: 0.88em;
        font-weight: 400;
        line-height: 1.15;
        margin-top: 6px;
        order: 2;
    }

    #clientInfoModal .modal-title .translation {
        color: #16a34a;
        display: block;
        font-size: 0.62em;
        font-weight: 400;
        line-height: 1.1;
        margin-top: 8px;
    }

    #clientInfoModal .save-btn[disabled],
    #clientInfoModal .clear-btn[disabled] {
        cursor: not-allowed;
        opacity: 0.7;
    }

    #clientInfoModal .save-btn {
        align-items: center;
        background: #3b8617;
        border-color: #3b8617;
        border-radius: 4px;
        color: #fff;
        display: inline-flex;
        gap: 8px;
        justify-content: center;
        min-height: 64px;
        min-width: 120px;
        position: relative;
        width: 100%;
    }

    #clientInfoModal .save-btn:hover,
    #clientInfoModal .save-btn:focus {
        background: #367b15;
        border-color: #367b15;
        color: #fff;
    }

    #clientInfoModal .clear-btn {
        border-radius: 4px;
        min-width: 116px;
    }

    #clientInfoModal .save-btn-spinner {
        animation: spin 1s linear infinite;
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-radius: 50%;
        border-top-color: #fff;
        display: none;
        height: 14px;
        left: 50%;
        margin-left: -54px;
        margin-top: -7px;
        position: absolute;
        top: 50%;
        width: 14px;
    }

    #clientInfoModal .save-btn.is-loading .save-btn-spinner {
        display: inline-block;
    }

    #clientInfoModal .btn-label,
    #clientInfoModal .save-btn-label {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }

    #clientInfoModal .checkbox-label {
        margin: 0;
    }

    #clientInfoModal .field-label-spacer {
        color: transparent;
    }

    #clientInfoModal .field-label-spacer .label-en,
    #clientInfoModal .placeholder-translation {
        visibility: hidden;
    }

    #clientInfoModal .paid-field {
        display: flex;
        flex-direction: column;
    }

    #clientInfoModal .paid-toggle {
        align-items: flex-start;
        display: flex;
        gap: 12px;
        padding-top: 6px;
    }

    #clientInfoModal .paid-toggle .form-check-input {
        margin: 6px 0 0;
        position: static;
    }

    #clientInfoModal .form-control {
        border-radius: 4px;
        min-height: 46px;
    }

    #clientInfoModal .close {
        font-size: 2.25rem;
        line-height: 1;
    }

    .ui-datepicker.client-info-datepicker {
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
        overflow: hidden;
        padding: 8px;
        width: 286px;
        z-index: 2055 !important;
    }

    .ui-datepicker.client-info-datepicker .ui-datepicker-header {
        border: 0;
        border-radius: 4px 4px 0 0;
        padding: 6px 36px;
    }

    .ui-datepicker.client-info-datepicker .ui-datepicker-prev,
    .ui-datepicker.client-info-datepicker .ui-datepicker-next {
        top: 7px;
    }

    .ui-datepicker.client-info-datepicker .ui-datepicker-title {
        align-items: center;
        display: flex;
        gap: 8px;
        justify-content: center;
        line-height: 1;
        margin: 0;
    }

    .ui-datepicker.client-info-datepicker .ui-datepicker-title select {
        height: 32px;
        margin: 0;
        max-width: calc(50% - 4px);
        width: auto;
    }

    .ui-datepicker.client-info-datepicker table {
        margin: 8px 0 0;
        table-layout: fixed;
    }

    .ui-datepicker.client-info-datepicker th {
        font-size: 0.85rem;
        padding: 6px 0;
    }

    .ui-datepicker.client-info-datepicker td {
        padding: 2px;
    }

    .ui-datepicker.client-info-datepicker td span,
    .ui-datepicker.client-info-datepicker td a {
        border-radius: 4px;
        min-height: 36px;
        padding: 8px 0;
        text-align: center;
    }

    #clientInfoModal .btn-label .translation {
        color: #16a34a;
    }

</style>
<div class="modal fade" id="clientInfoModal" tabindex="-1" role="dialog" aria-labelledby="clientInfoModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="label-en modal-title-en">Edit Client</span>
                    <span class="translation modal-title-es">Editar cliente</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <input type="hidden" id="client_id" name="client_id">
                <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" id="mode" name="mode">
                <div class="modal-body">
                    <div class="alert alert-danger client-info-feedback" style="display: none;">
                        <strong>We couldn't complete the request.</strong>
                        <ul class="client-info-error-list"></ul>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">First Name</span>
                                <span class="translation">Nombre de pila</span>
                            </label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Last Name</span>
                                <span class="translation">Apellido</span>
                            </label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Email</span>
                                <span class="translation">Correo electrónico</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Address</span>
                                <span class="translation">Dirección</span>
                            </label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-4">
                            <label class="field-label">
                                <span class="label-en">Gender</span>
                                <span class="translation">Género</span>
                            </label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="female">Female / Femenino</option>
                                <option value="male">Male / Masculino</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Phone</span>
                                <span class="translation">Teléfono</span>
                            </label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no"
                                placeholder="Digits Only No Spaces or Special Characters" required>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Emergency Contact Person</span>
                                <span class="translation">Contacto de emergencia</span>
                            </label>
                            <input type="text" class="form-control" id="emergency_contact_person"
                                name="emergency_contact_person" placeholder="Contact person name" required>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Emergency Contact Person Phone Number</span>
                                <span class="translation">Teléfono del contacto de emergencia</span>
                            </label>
                            <input type="text" class="form-control" id="emergency_contact_number"
                                name="emergency_contact_number"
                                placeholder="Digits Only No Spaces or Special Characters" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Date of Birth</span>
                                <span class="translation">Fecha de nacimiento</span>
                            </label>
                            <input type="text" class="form-control js-datepicker" id="date_of_birth"
                                name="date_of_birth" placeholder="MM/DD/YYYY" autocomplete="bday" required>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Session and Cost</span>
                                <span class="translation">Sesión y costo</span>
                            </label>
                            <select class="form-control" id="session_cost_type" name="session_cost_type" required>
                                <option value="senior">Senior Rate / Tarifa para personas mayores</option>
                                <option value="children">Children Rate / Tarifa para niños</option>
                                <option value="group">Group Rate / Tarifa de grupo</option>
                                <option value="normal">Normal Rate / Tarifa normal</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="field-label">
                                <span class="label-en">Enter Price</span>
                                <span class="translation">Ingresar precio</span>
                            </label>
                            <input type="number" min="0" class="form-control" id="session_cost"
                                name="session_cost" placeholder="Please input price" required>
                        </div>
                        <div class="col paid-field" style="align-self: center;">
                            <div class="paid-toggle">
                                <input type="checkbox" class="form-check-input" id="session_paid"
                                    name="session_paid">
                                <label class="form-check-label checkbox-label" for="session_paid">
                                    <span class="label-en">Paid?</span>
                                    <span class="translation">Pagado</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-auto clear-btn">
                        <span class="btn-label">
                            <span class="label-en text-white">Clear</span>
                            <span class="translation">Borrar</span>
                        </span>
                    </button>
                    <button type="button" class="btn save-btn">
                        <span class="save-btn-spinner" aria-hidden="true"></span>
                        <span class="save-btn-label">
                            <span class="label-en save-btn-label-en text-white">Save</span>
                            <span class="translation save-btn-label-es">Guardar</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Client Info Modal -->
