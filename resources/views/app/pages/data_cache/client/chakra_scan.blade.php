@extends('layouts.modern')

@section('page-title', $client->name . ' Chakra')

@php
    $activeNav = 'data';
    $useAppShell = true;
    $scan_session_id = request()->ssid ?? '';
    $scan_type = request()->scan_type ?? 'chakra_scan';
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div id="content-container" class="modern-data-cache-wrap"
             ng-cloak ng-controller="DataCacheClientShowChakraCtrl as ctrl">
            <div style="display: none;" id="scanSessionId" data-value="{{ $scan_session_id }}"></div>
            <div style="display: none;" id="clientId" data-value="{{ $client->id }}"></div>
            <div style="display: none;" id="scanType" data-value="{{ $scan_type }}"></div>

            <header class="modern-page-header" ng-if="ctrl.loaded">
                <div>
                    <h1 class="modern-page-title">{{ $client->name }} &mdash; Chakra</h1>
                    <p class="modern-page-subtitle">Scan session details / Detalles de sesión de escaneo</p>
                </div>
                <div class="modern-page-header__actions">
                    <a href="/data_cache/clients/<% ctrl.client.id %>" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back / Volver
                    </a>
                    <a href="{{ route('app.scanSessions.payment.request', ['id' => $scan_session_id]) }}"
                       ng-if="(ctrl.scan_session | valPresent) && !ctrl.scan_session.paid">
                        <button class="modern-btn modern-btn--primary">Request Payment / Solicitar pago</button>
                    </a>
                    <button class="modern-btn modern-btn--primary"
                            ng-click="ctrl.markDoneScanSession(ctrl.scan_session)"
                            ng-if="ctrl.scan_session.editable && !(ctrl.scan_session.date_ended | valPresent)">
                        Mark As Done
                    </button>
                    <button class="modern-btn modern-btn--ghost"
                            ng-click="ctrl.markUndoneScanSession(ctrl.scan_session)"
                            ng-if="ctrl.scan_session.editable && (ctrl.scan_session.date_ended | valPresent)">
                        Mark As Undone
                    </button>
                    <button class="modern-btn modern-btn--outline"
                            ng-click="ctrl.emailScanSession(ctrl.scan_session)"
                            ng-if="(ctrl.scan_session | valPresent)">
                        Email
                    </button>
                    <button class="modern-btn modern-btn--outline"
                            ng-click="ctrl.printScanSession(ctrl.scan_session)"
                            ng-if="(ctrl.scan_session | valPresent)">
                        Print
                    </button>
                </div>
            </header>

            <div class="loader" style="margin: 100px auto;" ng-if="!ctrl.loaded"></div>

            <section class="modern-data-cache-meta row g-3" ng-if="ctrl.loaded">
                <div class="col-12 col-md-4">
                    <div class="modern-info-card modern-data-cache-meta__card">
                        Anew Beginning<br>
                        Biomagnetism
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="modern-info-card modern-data-cache-meta__card">
                        5 Bon Air Rd.<br>
                        Suite 127<br>
                        Larkspur CA. 94938
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="modern-info-card modern-data-cache-meta__card">
                        anew-beginning.com<br>
                        415-555-1212
                    </div>
                </div>
            </section>

            <section class="modern-data-cache-summary row g-3" ng-if="ctrl.loaded">
                <div class="col-12 col-lg-4">
                    <div class="modern-info-card modern-data-cache-summary__card">
                        <div><strong>Name:</strong> <% ctrl.client.name %></div>
                        <div><strong>Age:</strong> <% ctrl.client.age %> (<% ctrl.client.date_of_birth | date: 'MMMM dd, yyyy' %>)</div>
                        <div><strong>Gender:</strong> <% ctrl.client.gender | capitalize %></div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="modern-info-card modern-data-cache-summary__card">
                        <div><strong>Address:</strong> <% ctrl.client.address %></div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="modern-info-card modern-data-cache-summary__card">
                        <span><% (ctrl.scan_session.date_started | date: 'MMMM dd, yyyy') || '-' %></span><span ng-if="ctrl.scan_session.date_ended | valPresent"> &mdash; <% (ctrl.scan_session.date_ended | date: 'MMMM dd, yyyy') || '-' %></span><br>
                        Invoice #<% ctrl.scan_session.id || '-' %><br>
                        <strong>Price:</strong> $<% ctrl.scan_session.cost || '-' %> <% (ctrl.scan_session.paid ? '(paid)' : '(not paid yet)') || '-' %>
                    </div>
                </div>
            </section>

            <section class="modern-info-card" ng-if="ctrl.loaded">
                <div class="modern-data-cache-toolbar">
                    <div class="modern-data-cache-toolbar__group">
                        <button class="modern-btn modern-btn--outline" ng-click="ctrl.refreshData()">Refresh</button>
                        <input type="text" placeholder="Search for pairs or points below"
                               class="modern-data-cache-input modern-data-cache-toolbar__input"
                               ng-model="ctrl.searchText">
                    </div>
                    <div class="modern-data-cache-toolbar__group modern-data-cache-toolbar__group--right"
                         ng-if="!(ctrl.scan_session.date_ended | valPresent)">
                        <input type="text" placeholder="Filter selection"
                               class="modern-data-cache-input"
                               ng-model="searchTextPair">
                        <select ng-model="pair" ng-disabled="!(ctrl.pairs | valPresent)"
                                class="modern-data-cache-select">
                            <option ng-repeat="pair in ctrl.pairs | orderBy: 'name' | filter: { name: searchTextPair } track by pair.id"
                                    ng-value="<% pair %>" ng-if="pair._selectable"><% pair.name %></option>
                        </select>
                        <button class="modern-btn modern-btn--primary"
                                ng-click="ctrl.addPair(pair)"
                                ng-disabled="!(pair | valPresent)">Add</button>
                        <a href="/data_cache/clients/<% ctrl.client.id %>/add_pairs?ssid=<% ctrl.scan_session.id %>" target="_blank">
                            <button class="modern-btn modern-btn--outline">Show List</button>
                        </a>
                    </div>
                </div>

                <div class="table-responsive modern-data-cache-table-shell"
                     ng-if="(ctrl.displayed_pairs | valPresent) && ctrl.loaded">
                    <table border="1" class="modern-data-cache-table">
                        <thead>
                            <tr>
                                <th ng-click="ctrl.toggleSortBy('name')">Point/Name / Punto/Nombre</th>
                                <th ng-click="ctrl.toggleSortBy('origins')">Start/Origin / Inicio/Origen</th>
                                <th ng-click="ctrl.toggleSortBy('symptoms')">Leads/Symptoms / Señales/Síntomas</th>
                                <th ng-click="ctrl.toggleSortBy('paths')">Path/Route / Ruta/Causa y efecto</th>
                                <th ng-click="ctrl.toggleSortBy('alternative_routes')">Alt. Routes / Rutas alternativas</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-repeat="(radical, pairs) in ctrl.displayed_pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText | orderBy: 'radical' | groupBy: 'radical'">
                            <tr>
                                <td class="header-radical header-radical-<% $index %>" colspan="6"><% radical %></td>
                            </tr>
                            <tr class="plain" ng-repeat="pair in pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText | orderBy: ctrl.sortBy.column track by pair.id">
                                <td><% pair.name %></td>
                                <td><% pair.origins || '-' %></td>
                                <td><% pair.symptoms || '-' %></td>
                                <td><% pair.paths || '-' %></td>
                                <td><% pair.alternative_routes || '-' %></td>
                                <td>
                                    <button class="fa fa-close fa-2x modern-data-cache-remove-btn"
                                            ng-click="ctrl.removePair(pair)"></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h5 class="text-center modern-data-cache-empty"
                    ng-if="ctrl.loaded && (ctrl.displayed_pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText).length == 0">
                    No records.
                </h5>
            </section>
        </div>
    </main>
@endsection
