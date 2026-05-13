@extends('layouts.modern')

@section('page-title', 'My Subscriptions')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('My Subscriptions') }}</h1>
                    <p class="modern-page-subtitle">Mis suscripciones</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="subscriptions">
                                <thead>
                                    <tr>
                                        <th class="align-center">{{ __('Plan') }} / Plan</th>
                                        <th class="align-center">{{ __('Starts At') }} / Fecha de inicio</th>
                                        <th class="align-center">{{ __('Ends At') }} / Fecha de fin</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var dtLang = {
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
            };
            $('#subscriptions').DataTable({
                processing: true,
                serverSide: true,
                language: dtLang,
                ajax: { url : '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/users/me/subscriptions/datatables' },
                columns: [
                    { data: 'plan', orderable: false, searchable: false,
                        render: function ( data, type, row, meta ) {
                            return data.name + " ($" + parseFloat(Math.round(data.price * 100) / 100).toFixed(2) + ")";
                        }
                    },
                    { data: 'starts_at',
                        render: function ( data, type, row, meta ) { return new Date(data).toLocaleString(); }
                    },
                    { data: 'ends_at',
                        render: function ( data, type, row, meta ) { return new Date(data).toLocaleString(); }
                    }
                ]
            });
        });
    </script>
@endpush
