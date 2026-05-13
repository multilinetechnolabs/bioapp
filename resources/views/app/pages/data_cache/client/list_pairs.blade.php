@extends('layouts.modern')

@section('page-title', $client->name . ' Pairs')

@php
    $activeNav = 'data';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div id="content-container" class="modern-data-cache-wrap"
             ng-cloak ng-controller="DataCacheClientAddPairsCtrl as ctrl">
            <div style="display: none;" id="clientId" data-value="{{ $client->id }}"></div>
            <div style="display: none;" id="scanSessionId" data-value="{{ $request->ssid }}"></div>
            <div style="display: none;" id="scanType" data-value="{{ $request->scan_type }}"></div>

            <div class="loader" style="margin: 100px auto;" ng-if="!ctrl.loaded"></div>

            <header class="modern-page-header"
                    ng-if="(ctrl.scan_session | valPresent) && ctrl.loaded">
                <div>
                    <h1 class="modern-page-title">
                        {{ $client->name }} &mdash; <% ctrl.scan_type == 'body_scan' ? 'Bio' : 'Chakra' %>
                    </h1>
                    <p class="modern-page-subtitle">All available pairs to add / Todos los pares disponibles para agregar</p>
                </div>
                <div class="modern-page-header__actions">
                    <a href="/data_cache/clients/{{ $client->id }}/bio?ssid=<% ctrl.scan_session.id %>"
                       ng-if="ctrl.scan_type == 'body_scan'" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back / Volver
                    </a>
                    <a href="/data_cache/clients/{{ $client->id }}/chakra?ssid=<% ctrl.scan_session.id %>"
                       ng-if="ctrl.scan_type != 'body_scan'" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back / Volver
                    </a>
                    <button class="modern-btn modern-btn--ghost" ng-click="ctrl.refresh()">Refresh / Actualizar</button>
                </div>
            </header>

            <section class="modern-info-card"
                     ng-if="(ctrl.scan_session | valPresent) && ctrl.loaded">
                <div class="modern-data-cache-toolbar">
                    <div class="modern-data-cache-toolbar__group">
                        <input type="text" ng-model="ctrl.searchText" placeholder="Search / Buscar"
                               class="modern-data-cache-input modern-data-cache-toolbar__input">
                    </div>
                </div>

                <div class="table-responsive modern-data-cache-table-shell"
                     ng-if="(ctrl.pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText).length > 0">
                    <table border="1" class="modern-data-cache-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="max-width: 150px;" ng-click="ctrl.toggleSortBy('name')">Point/Name / Punto/Nombre</th>
                                <th ng-click="ctrl.toggleSortBy('radical')">Radical</th>
                                <th ng-click="ctrl.toggleSortBy('origins')">Start/Origin / Inicio/Origen</th>
                                <th ng-click="ctrl.toggleSortBy('symptoms')">Leads/Symptoms / Señales/Síntomas</th>
                                <th ng-click="ctrl.toggleSortBy('paths')">Path/Route / Ruta/Causa y efecto</th>
                                <th ng-click="ctrl.toggleSortBy('alternative_routes')">Alt. Routes / Rutas alternativas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="pair in ctrl.pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText | orderBy: ctrl.sortBy.column track by pair.id">
                                <td class="modern-data-cache-cell-actions">
                                    <i class="fa fa-plus-circle fa-2x modern-data-cache-add-icon"
                                       aria-hidden="true"
                                       ng-click="ctrl.addPair(pair)"
                                       ng-if="!pair._delete"></i>
                                    <i class="fa fa-minus-circle fa-2x modern-data-cache-remove-icon"
                                       aria-hidden="true"
                                       ng-click="ctrl.removePair(pair)"
                                       ng-if="pair._delete"></i>
                                </td>
                                <td style="max-width: 180px; word-wrap: break-word;"><% pair.name %></td>
                                <td><% pair.radical %></td>
                                <td><% pair.origins || '-' %></td>
                                <td><% pair.symptoms || '-' %></td>
                                <td><% pair.paths || '-' %></td>
                                <td><% pair.alternative_routes || '-' %></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h5 class="text-center modern-data-cache-empty"
                    ng-if="ctrl.loaded && (ctrl.scan_session | valPresent) && (ctrl.pairs | where: { scan_type: ctrl.scan_type } | filter: ctrl.searchText).length == 0">
                    No results found.
                </h5>
            </section>
        </div>
    </main>
@endsection
