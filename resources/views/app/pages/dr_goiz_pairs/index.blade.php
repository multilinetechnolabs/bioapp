@extends('layouts.modern')

@section('page-title', 'Free Protocol Pairs')

@php
    $activeNav = 'home';
    $useAppShell = false;
    $l = $locale ?? 'en';
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">
                        @if($l === 'es')
                            PARES DE PROTOCOLO GRATUITOS
                        @elseif($l === 'fr')
                            PAIRES DE PROTOCOLES GRATUITES
                        @else
                            FREE PROTOCOL PAIRS
                        @endif
                    </h1>
                    <p class="modern-page-subtitle">
                        @if($l === 'es')
                            Pares Clásicos de Biomagnetismo Original
                        @elseif($l === 'fr')
                            Paires Classiques de Biomagnétisme Original
                        @else
                            Original Biomagnetism Protocol Pairs
                        @endif
                    </p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="row justify-content-center mb-2">
                        <div class="col-12 text-center"
                            style="background: #fef3c7; color: #92400e; font-weight: 600; padding: 8px 0; font-size: 14px; letter-spacing: 0.5px; border-radius: 6px 6px 0 0;">
                            @if($l === 'es')
                                Nivel Gratis
                            @elseif($l === 'fr')
                                Niveau Gratuit
                            @else
                                FREE Tier
                            @endif
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-12 text-center"
                            style="background: #ccfbf1; color: #0f766e; font-weight: 600; padding: 8px 0; font-size: 14px; letter-spacing: 0.5px; border-radius: 6px;">
                            @if($l === 'es')
                                267 Pares Clásicos de Protocolo Gratuitos
                            @elseif($l === 'fr')
                                267 Paires de Protocoles Classiques Gratuites
                            @else
                                267 Classic Free Protocol Pairs
                            @endif
                        </div>
                    </div>
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table id="drGoizPairsTable" class="table table-hover table-bordered table-datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>{{ $l === 'es' ? 'Lugar' : ($l === 'fr' ? 'Lieu' : 'Place') }}</th>
                                        <th>{{ $l === 'es' ? 'Resonancia' : ($l === 'fr' ? 'Résonance' : 'Resonance') }}</th>
                                        <th>{{ $l === 'es' ? 'Nombre' : ($l === 'fr' ? 'Nom' : 'Name') }}</th>
                                        <th>{{ $l === 'es' ? 'Característica' : ($l === 'fr' ? 'Caractéristique' : 'Characteristic') }}</th>
                                        <th>{{ $l === 'es' ? 'Descripción' : ($l === 'fr' ? 'Description' : 'Description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pairs as $pair)
                                        <tr>
                                            <td>{{ ($l === 'es' && $pair->place_es) ? $pair->place_es : $pair->place }}</td>
                                            <td>{{ ($l === 'es' && $pair->resonance_es) ? $pair->resonance_es : $pair->resonance }}</td>
                                            <td>{{ ($l === 'es' && $pair->name_es) ? $pair->name_es : $pair->name }}</td>
                                            <td>{{ ($l === 'es' && $pair->characteristic_es) ? $pair->characteristic_es : $pair->characteristic }}</td>
                                            <td>{{ ($l === 'es' && $pair->description_es) ? $pair->description_es : $pair->description }}</td>
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
            @if(($locale ?? 'en') === 'es')
            $('#drGoizPairsTable').DataTable({
                pageLength: 25,
                order: [[2, 'asc']],
                responsive: true,
                language: {
                    search: "Buscar:",
                    searchPlaceholder: "Buscar...",
                    processing: "Procesando...",
                    lengthMenu: "Mostrar _MENU_ entradas",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Sin entradas",
                    infoFiltered: "(filtrado de _MAX_ totales)",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
            @elseif(($locale ?? 'en') === 'fr')
            $('#drGoizPairsTable').DataTable({
                pageLength: 25,
                order: [[2, 'asc']],
                responsive: true,
                language: {
                    search: "Rechercher :",
                    searchPlaceholder: "Rechercher...",
                    processing: "Traitement en cours...",
                    lengthMenu: "Afficher _MENU_ entrées",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                    infoEmpty: "Aucune entrée",
                    infoFiltered: "(filtré sur _MAX_ entrées au total)",
                    zeroRecords: "Aucun résultat trouvé",
                    emptyTable: "Aucune donnée disponible",
                    paginate: {
                        first: "Premier",
                        last: "Dernier",
                        next: "Suivant",
                        previous: "Précédent"
                    }
                }
            });
            @else
            $('#drGoizPairsTable').DataTable({
                pageLength: 25,
                order: [[2, 'asc']],
                responsive: true
            });
            @endif
        });
    </script>
@endpush
