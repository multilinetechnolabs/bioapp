@extends('layouts.modern')

@section('page-title', 'Data Cache Chakra')

@php
    $activeNav = 'data';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap" ng-cloak ng-controller="DataCacheChakraCtrl as ctrl">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">Chakra</h1>
                    <p class="modern-page-subtitle">Chakra scan records across all clients / Registros de escaneo Chakra</p>
                </div>
                <div class="modern-page-header__actions">
                    <a href="/data_cache" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back / Volver
                    </a>
                </div>
            </header>

            <section class="modern-info-card">
                <div class="modern-data-cache-toolbar">
                    <div class="modern-data-cache-toolbar__group">
                        <label class="modern-data-cache-label" for="chakraClientSelect">Clients:</label>
                        <select id="chakraClientSelect" ng-model="client"
                                ng-disabled="!(ctrl.clients | valPresent)"
                                class="modern-data-cache-select">
                            <option ng-repeat="client in ctrl.clients track by client.id"
                                    ng-value="<% client %>"
                                    ng-if="client.user_id == {{ Auth::user()->id }}"><% client.name %></option>
                        </select>
                        <a ng-href="/data_cache/clients/<% client.id %>">
                            <button class="modern-btn modern-btn--primary"
                                    ng-disabled="!(client | valPresent)">Create New Scan</button>
                        </a>
                    </div>
                </div>

                <div class="loader" style="margin: 100px auto;" ng-if="!ctrl.loaded"></div>

                <h5 class="text-center modern-data-cache-error"
                    ng-if="ctrl.loaded && ctrl.errorMessage"><% ctrl.errorMessage %></h5>

                <div class="modern-data-cache-toolbar"
                     ng-if="(ctrl.displayed_pairs | valPresent) && ctrl.loaded">
                    <div class="modern-data-cache-toolbar__group">
                        <input type="text" placeholder="Search" ng-model="ctrl.searchText"
                               class="modern-data-cache-input">
                    </div>
                    <div class="modern-data-cache-toolbar__group modern-data-cache-toolbar__group--right">
                        <button class="modern-btn modern-btn--outline"
                                ng-click="ctrl.refreshScanSession()">Refresh</button>
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
                                <th style="width: 300px;">Clients / Clientes</th>
                            </tr>
                        </thead>
                        <tbody ng-repeat="(radical, scan_session_pairs) in ctrl.displayed_pairs | unique: 'pair.name' | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText | orderBy: 'pair.radical' | groupBy: 'pair.radical'">
                            <tr>
                                <td class="header-radical header-radical-<% $index %>" colspan="6"><% radical %></td>
                            </tr>
                            <tr class="plain" ng-repeat="scan_session_pair in scan_session_pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText | orderBy: 'pair.name'">
                                <td><% scan_session_pair.pair.name || '-' %></td>
                                <td><% scan_session_pair.pair.origins || '-' %></td>
                                <td><% scan_session_pair.pair.symptoms || '-' %></td>
                                <td><% scan_session_pair.pair.paths || '-' %></td>
                                <td><% scan_session_pair.pair.alternative_routes || '-' %></td>
                                <td>
                                    <ul class="modern-data-cache-client-list">
                                        <li ng-repeat="(client_name, scan_session_pairs) in ctrl.displayed_pairs | where: { pair_id: scan_session_pair.pair_id } | orderBy: 'scanSession.client.name' | groupBy: 'scanSession.client.name'">
                                            <span><% client_name || '-' %></span>
                                            <a ng-repeat="_scan_session_pair in scan_session_pairs | where: { pair_id: scan_session_pair.pair_id }"
                                               ng-href="/data_cache/clients/<% _scan_session_pair.scanSession.client.id %>/chakra?ssid=<% _scan_session_pair.scan_session_id %>"
                                               target="_blank">
                                                [#<% _scan_session_pair.scan_session_id || '-' %>]
                                            </a>
                                        </li>
                                    </ul>
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
