@extends('layouts.modern')

@section('page-title', 'My Payments')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('My Payments') }}</h1>
                    <p class="modern-page-subtitle">Mis pagos</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="payments">
                                <thead>
                                    <tr>
                                        <th class="align-center">{{ __('Date') }} / Fecha</th>
                                        <th class="align-center">{{ __('Description') }} / Descripción</th>
                                        <th class="align-center">{{ __('Amount') }} / Monto</th>
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
            $('#payments').DataTable({
                processing: true,
                serverSide: true,
                language: dtLang,
                ajax: { url : '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/users/me/payments/datatables' },
                columns: [
                    { data: 'date_paid' },
                    { data: 'description' },
                    { data: 'amount',
                        render: function ( data, type, row, meta ) { return '$' + data; }
                    }
                ]
            });
        });
    </script>
@endpush
