@extends('layouts.modern')

@section('page-title', 'Free Protocol Pairs')

@php
    $activeNav = 'home';
    $useAppShell = false;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">FREE PROTOCOL PAIRS</h1>
                    <p class="modern-page-subtitle">Pares de Protocolo Gratis</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="row justify-content-center mb-2">
                        <div class="col-12 text-center"
                            style="background: #fef3c7; color: #92400e; font-weight: 600; padding: 8px 0; font-size: 14px; letter-spacing: 0.5px; border-radius: 6px 6px 0 0;">
                            FREE Tier &nbsp;|&nbsp; Nivel Gratis
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-12 text-center"
                            style="background: #ccfbf1; color: #0f766e; font-weight: 600; padding: 8px 0; font-size: 14px; letter-spacing: 0.5px; border-radius: 6px;">
                            267 Classic FREE PROTOCOL PAIRS &nbsp;|&nbsp; 267 Pares Clásicos de Protocolo Gratis
                        </div>
                    </div>
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table id="drGoizPairsTable" class="table table-hover table-bordered table-datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Place / Lugar</th>
                                        <th>Resonance / Resonancia</th>
                                        <th>Name / Nombre</th>
                                        <th>Characteristic / Característica</th>
                                        <th>Description / Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pairs as $pair)
                                        <tr>
                                            <td>
                                                {{ $pair->place }}
                                                @if ($pair->place_es)
                                                    <br><span class="text-muted" style="font-style: italic; font-size: 0.9em;">{{ $pair->place_es }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pair->resonance }}
                                                @if ($pair->resonance_es)
                                                    <br><span class="text-muted" style="font-style: italic; font-size: 0.9em;">{{ $pair->resonance_es }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pair->name }}
                                                @if ($pair->name_es)
                                                    <br><span class="text-muted" style="font-style: italic; font-size: 0.9em;">{{ $pair->name_es }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pair->characteristic }}
                                                @if ($pair->characteristic_es)
                                                    <br><span class="text-muted" style="font-style: italic; font-size: 0.9em;">{{ $pair->characteristic_es }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pair->description }}
                                                @if ($pair->description_es)
                                                    <br><span class="text-muted" style="font-style: italic; font-size: 0.9em;">{{ $pair->description_es }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    @guest
        <script src="{{ \App\Support\VersionedAsset::url('js/manifest.js') }}"></script>
        <script src="{{ \App\Support\VersionedAsset::url('js/vendor.js') }}"></script>
        <script src="{{ \App\Support\VersionedAsset::url('js/app.js') }}"></script>
    @endguest
    <script type="text/javascript">
        $(document).ready(function() {
            $('#drGoizPairsTable').DataTable({
                pageLength: 25,
                order: [[2, 'asc']],
                responsive: true,
                language: {
                    search: "Search / Buscar:",
                    searchPlaceholder: "Search... / Buscar...",
                    processing: "Processing... / Procesando...",
                    lengthMenu: "Show _MENU_ entries / Mostrar _MENU_ entradas",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries / Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "No entries found / Sin entradas",
                    infoFiltered: "(filtered from _MAX_ total / filtrado de _MAX_ totales)",
                    zeroRecords: "No matching records found / No se encontraron registros",
                    emptyTable: "No data available / No hay datos disponibles",
                    paginate: {
                        first: "First / Primero",
                        last: "Last / Último",
                        next: "Next / Siguiente",
                        previous: "Previous / Anterior"
                    }
                }
            });
        });
    </script>
@endpush
