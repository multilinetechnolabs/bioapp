@extends('layouts.modern')

@section('page-title', $client->name)

@php
    $activeNav = 'data';
    $useAppShell = true;
    $scan_type = request()->scan_type ?? 'body_scan';
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div id="content-container" class="modern-data-cache-wrap"
             ng-cloak ng-controller="DataCacheClientShowCtrl as ctrl">
            <div style="display: none;" id="clientId" data-value="{{ $client->id }}"></div>
            <div style="display: none;" id="scanType" data-value="{{ $scan_type }}"></div>

            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ $client->name }}</h1>
                    <p class="modern-page-subtitle">Client information &amp; sessions / Información del cliente y sesiones</p>
                </div>
                <div class="modern-page-header__actions">
                    <a href="/data_cache/client_info" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back / Volver
                    </a>
                    <button class="modern-btn modern-btn--outline"
                            data-toggle="modal" data-target="#clientInfoModal"
                            data-title="Edit Client" data-id="<% ctrl.client.id %>">Edit / Editar</button>
                    <button class="modern-btn modern-btn--ghost"
                            ng-click="ctrl.refreshClient()">Refresh / Actualizar</button>
                </div>
            </header>

            <div class="loader" style="margin: 100px auto;" ng-if="!ctrl.loaded"></div>

            <section class="modern-data-cache-summary row g-3" ng-if="ctrl.loaded">
                <div class="col-12 col-lg-6">
                    <div class="modern-info-card modern-data-cache-summary__card">
                        <div><strong>Name / Nombre:</strong> <% ctrl.client.name %></div>
                        <div><strong>Age / Edad:</strong> <% ctrl.client.age %> (<% ctrl.client.date_of_birth | date: 'MMMM dd, yyyy' %>)</div>
                        <div><strong>Gender / Género:</strong> <% ctrl.client.gender | capitalize %></div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="modern-info-card modern-data-cache-summary__card">
                        <div><strong>Address / Dirección:</strong> <% ctrl.client.address %></div>
                    </div>
                </div>
            </section>

            <section class="modern-info-card modern-data-cache-tabs-card" ng-hide="!ctrl.loaded">
                <div id="client_show_tabs" class="modern-data-cache-tabs">
                    <ul>
                        <li><a href="#tabs-sessions">Scan Sessions / Sesiones</a></li>
                        <li><a href="#tabs-medical-notes">Medical Notes / Notas médicas</a></li>
                        <li><a href="#tabs-consent-forms">Consent Forms / Formularios</a></li>
                    </ul>

                    <div id="tabs-sessions">
                        <div class="modern-data-cache-form-row">
                            <input type="date" id="scan_session_date_started"
                                   class="modern-data-cache-input"
                                   ng-model="ctrl.scan_session.date_started" date-picker-input>
                            <select ng-model="ctrl.scan_session.scan_type"
                                    class="modern-data-cache-select modern-data-cache-form-row__grow">
                                <option value="body_scan">Body Scan / Escaneo corporal</option>
                                <option value="chakra_scan">Chakra Scan / Escaneo Chakra</option>
                            </select>
                            <input type="number" id="scan_session_cost"
                                   class="modern-data-cache-input"
                                   ng-model="ctrl.scan_session.cost"
                                   placeholder="Cost / Costo ($)" min="0"
                                   ng-value="ctrl.client.session_cost">
                            <button class="modern-btn modern-btn--primary"
                                    ng-click="ctrl.addScanSession(ctrl.scan_session)"
                                    ng-disabled="!(ctrl.scan_session.date_started | valPresent) || !(ctrl.scan_session.scan_type | valPresent)">
                                Add / Agregar
                            </button>
                        </div>

                        <div class="table-responsive modern-data-cache-table-shell">
                            <table border="1" class="modern-data-cache-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th style="width: 171px;">Date Started / Fecha de inicio</th>
                                        <th style="width: 250px;">Scan Type / Tipo de escaneo</th>
                                        <th style="width: 171px;">Date Ended / Fecha de fin</th>
                                        <th style="width: 320px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="scan_session in ctrl.scan_sessions | orderBy: ['date_started', 'created_at'] track by scan_session.id">
                                        <td style="text-align: center;"><% scan_session.id %></td>
                                        <td style="text-align: center;"><% scan_session.date_started | date: 'MMM dd, yyyy' %></td>
                                        <td style="padding-left: 20px;"><% scan_session.scan_type == 'body_scan' ? 'Body Scan' : 'Chakra Scan' %></td>
                                        <td style="text-align: center;"><% (scan_session.date_ended | date: 'MMM dd, yyyy') || '-' %></td>
                                        <td class="modern-data-cache-actions">
                                            <a href="/data_cache/clients/<% scan_session.client_id %>/<% scan_session.scan_type == 'body_scan' ? 'bio' : 'chakra' %>?ssid=<% scan_session.id %>" target="_blank">
                                                <button class="modern-btn modern-btn--small modern-btn--outline">
                                                    <i class="fa fa-eye"></i> View Details / Ver detalles
                                                </button>
                                            </a>
                                            <a href="/<% scan_session.scan_type == 'body_scan' ? 'bodyscan' : 'chakrascan' %>?target=<% scan_session.client.gender %>&ssid=<% scan_session.id %>" target="_blank">
                                                <button class="modern-btn modern-btn--small modern-btn--outline">
                                                    <i class="fa fa-eye"></i> View Through Scan / Ver a través del escaneo
                                                </button>
                                            </a>
                                            <button class="modern-btn modern-btn--small modern-btn--primary"
                                                    ng-click="ctrl.markDoneScanSession(scan_session)"
                                                    ng-if="scan_session.editable && !(scan_session.date_ended | valPresent)">
                                                <i class="fa fa-check"></i> Mark As Done / Marcar como hecho
                                            </button>
                                            <button class="modern-btn modern-btn--small modern-btn--ghost"
                                                    ng-click="ctrl.markUndoneScanSession(scan_session)"
                                                    ng-if="scan_session.editable && (scan_session.date_ended | valPresent)">
                                                <i class="fa fa-close"></i> Mark As Undone / Marcar como no hecho
                                            </button>
                                            <button class="modern-btn modern-btn--small modern-btn--danger"
                                                    ng-click="ctrl.deleteScanSession(scan_session)"
                                                    ng-if="scan_session.deletable">
                                                <i class="fa fa-trash"></i> Delete / Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="text-center modern-data-cache-empty"
                            ng-if="!(ctrl.scan_sessions | valPresent)">No records.</h5>
                    </div>

                    <div id="tabs-medical-notes">
                        <div class="modern-data-cache-form-row" ng-if="!(ctrl.medical_note.id | valPresent)">
                            <input type="date" id="medical_note_date_noted"
                                   class="modern-data-cache-input"
                                   ng-model="ctrl.medical_note.date_noted">
                            <input type="text" placeholder="Description"
                                   class="modern-data-cache-input modern-data-cache-form-row__grow"
                                   ng-model="ctrl.medical_note.description">
                            <button class="modern-btn modern-btn--primary"
                                    ng-click="ctrl.addMedicalNote(ctrl.medical_note)"
                                    ng-disabled="!(ctrl.medical_note.date_noted | valPresent) || !(ctrl.medical_note.description | valPresent)">
                                Add
                            </button>
                        </div>
                        <div class="modern-data-cache-form-row" ng-if="ctrl.medical_note.id | valPresent">
                            <input type="date" id="medical_note_date_noted"
                                   class="modern-data-cache-input"
                                   ng-model="ctrl.medical_note.date_noted">
                            <input type="text" placeholder="Description"
                                   class="modern-data-cache-input modern-data-cache-form-row__grow"
                                   ng-model="ctrl.medical_note.description">
                            <button class="modern-btn modern-btn--primary"
                                    ng-click="ctrl.addMedicalNote(ctrl.medical_note)"
                                    ng-disabled="!(ctrl.medical_note.date_noted | valPresent) || !(ctrl.medical_note.description | valPresent)">
                                Save
                            </button>
                            <button class="modern-btn modern-btn--danger"
                                    ng-click="ctrl.cancelMedicalNote(ctrl.medical_note)"
                                    ng-disabled="!(ctrl.medical_note.date_noted | valPresent) || !(ctrl.medical_note.description | valPresent)">
                                Cancel
                            </button>
                        </div>

                        <div class="table-responsive modern-data-cache-table-shell">
                            <table border="1" class="modern-data-cache-table">
                                <thead>
                                    <tr>
                                        <th style="width: 171px;">Date / Fecha</th>
                                        <th>Description / Descripción</th>
                                        <th style="width: 152px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="medical_note in ctrl.medical_notes | orderBy: ['date_noted', 'created_at'] track by medical_note.id">
                                        <td style="text-align: center;"><% medical_note.date_noted | date: 'MMM dd, yyyy' %></td>
                                        <td style="padding-left: 20px;"><% medical_note.description %></td>
                                        <td class="modern-data-cache-actions">
                                            <button class="modern-btn modern-btn--small modern-btn--outline"
                                                    ng-click="ctrl.editMedicalNote(medical_note)"
                                                    ng-if="medical_note.editable && !(ctrl.medical_note.id | valPresent)">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button class="modern-btn modern-btn--small modern-btn--danger"
                                                    ng-click="ctrl.deleteMedicalNote(medical_note)"
                                                    ng-if="medical_note.deletable && !(ctrl.medical_note.id | valPresent)">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="text-center modern-data-cache-empty"
                            ng-if="!(ctrl.medical_notes | valPresent)">No records.</h5>
                    </div>

                    <div id="tabs-consent-forms">
                        <form method="POST" enctype="multipart/form-data" id="consentFormInputForm"
                              action="{{ url('/data_cache/upload_consent_form') }}"
                              class="modern-data-cache-consent-form">
                            @csrf
                            <input type="hidden" id="client_id" name="client_id" value="<% ctrl.client.id %>" />
                            <div id="form-part" class="modern-data-cache-form-row">
                                <input type="date" id="consent_form_date_entered" name="date_entered"
                                       class="modern-data-cache-input"
                                       value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                <input type="file" id="consent_form_file" name="consent_form_file"
                                       class="modern-data-cache-input"
                                       accept=".pdf, .doc, .docx" required>
                                <input type="text" id="description" name="description"
                                       placeholder="Description (Optional)"
                                       class="modern-data-cache-input modern-data-cache-form-row__grow">
                                <button type="submit" id="contentFormSubmit" class="modern-btn modern-btn--primary">
                                    Upload
                                </button>
                            </div>
                            <div id="loader-part" style="display: none;" class="text-center my-3">
                                <div class="loader" style="margin: 0 auto;"></div>
                                <p class="text-muted mt-2">{{ __('Please wait... file upload is still being processed...') }}</p>
                            </div>
                        </form>

                        <div class="table-responsive modern-data-cache-table-shell">
                            <table border="1" class="modern-data-cache-table">
                                <thead>
                                    <tr>
                                        <th style="width: 171px;">Date / Fecha</th>
                                        <th>File Name / Nombre de archivo</th>
                                        <th>Description / Descripción</th>
                                        <th style="width: 320px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="consent_form in ctrl.consent_forms | orderBy: ['date_entered', 'file_name'] track by consent_form.id">
                                        <td style="text-align: center;"><% consent_form.date_entered | date: 'MMM dd, yyyy' %></td>
                                        <td style="padding-left: 20px;"><% consent_form.file_name %></td>
                                        <td style="padding-left: 20px;"><% consent_form.description || '-' %></td>
                                        <td class="modern-data-cache-actions">
                                            <a ng-href="<% consent_form.file_url %>" target="_blank">
                                                <button class="modern-btn modern-btn--small modern-btn--outline"
                                                        ng-disabled="consent_form.file_ext != 'pdf'">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            </a>
                                            <a href="<% consent_form.file_url %>" download>
                                                <button class="modern-btn modern-btn--small modern-btn--outline">
                                                    <i class="fa fa-download"></i> Download
                                                </button>
                                            </a>
                                            <button class="modern-btn modern-btn--small modern-btn--danger"
                                                    ng-click="ctrl.deleteConsentForm(consent_form)"
                                                    ng-if="consent_form.deletable">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="text-center modern-data-cache-empty"
                            ng-if="!(ctrl.consent_forms | valPresent)">No records.</h5>
                    </div>
                </div>
            </section>
        </div>
    </main>

    @include('app.pages.data_cache.modals.client_info')
@endsection

@push('scripts')
    @include('app.pages.data_cache.modals.js.client_info')

    <script>
        $("#client_show_tabs").tabs();

        $('#contentFormSubmit').click(function(event){
            if ($("#consent_form_file").val() != '' || $("#consent_form_file").val() != null) {
                $('#form-part').hide();
                $('#loader-part').show();
            }
        });
    </script>
@endpush
