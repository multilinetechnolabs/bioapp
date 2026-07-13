@extends('layouts.modern')

@section('page-title', 'My Course Subscriptions')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('My Course Subscriptions') }}</h1>
                    <p class="modern-page-subtitle">Mis suscripciones a cursos</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="course_subscriptions">
                                <thead>
                                    <tr>
                                        <th class="align-center">{{ __('Course') }} / Curso</th>
                                        <th class="align-center">{{ __('Starts At') }} / Fecha de inicio</th>
                                        <th class="align-center">{{ __('Ends At') }} / Fecha de fin</th>
                                        <th class="align-center">{{ __('Status') }} / Estado</th>
                                        <th class="align-center">{{ __('Actions') }}</th>
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
            $('#course_subscriptions').DataTable({
                processing: true,
                serverSide: true,
                language: dtLang,
                ajax: { url : '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/users/me/course-subscriptions/datatables' },
                columns: [
                    { data: 'course', orderable: false, searchable: false,
                        render: function ( data, type, row, meta ) {
                            return data.title + " ($" + parseFloat(Math.round(data.price * 100) / 100).toFixed(2) + ")";
                        }
                    },
                    { data: 'starts_at',
                        render: function ( data, type, row, meta ) { return new Date(data).toLocaleString(); }
                    },
                    { data: 'ends_at',
                        render: function ( data, type, row, meta ) { return new Date(data).toLocaleString(); }
                    },
                    { data: 'status',
                        render: function (data, type, row, meta)
                        {
                            if (data === 'active') {
                                return '<span class="badge badge-success">Active</span>';
                            }
                            if (data === 'cancelled') {
                                return '<span class="badge badge-danger">Cancelled</span>';
                            }

                            return data;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row) {

                            if (row.status === 'cancelled' || row.cancelled_at) {
                                return '';
                            }

                            var icCancel = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"/></svg>';

                            var btn = '<button class="admin-action-btn admin-action-btn--warn editor-cancel" data-id="' + data + '" title="Cancel Course Subscription">' + icCancel + '</button>';

                            return '<div class="admin-action-group">' + btn + '</div>';
                        }
                    }
                ]
            });

            // Handle cancel click
            $('#course_subscriptions').on('click', 'button.editor-cancel', function(e) {

                e.preventDefault();

                var id = $(this).data('id');

                if (!confirm('Are you sure you want to cancel this course subscription?')) {
                    return;
                }

                $.ajax({

                    url: '/course-subscriptions/' + id + '/cancel',

                    type: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(res) {

                        alert(res.message || 'Course subscription cancelled successfully');

                        location.reload();
                    },

                    error: function(xhr) {

                        var msg = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Error cancelling course subscription';

                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
