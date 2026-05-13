@extends('layouts.admin')
@section('page-title')Orders@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Orders') }}</h2>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="orders">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __("Buyer's Name") }}</th>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Cost') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#orders').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this order?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/orders/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#orders').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/orders/datatables' },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user', name: 'user', orderable: false, searchable: false, render: function(d) { return d.name; } },
                    { data: 'product', name: 'product', orderable: false, searchable: false, render: function(d) { return d.name; } },
                    { data: 'description', name: 'description' },
                    { data: 'product', name: 'product_price', orderable: false, searchable: false, render: function(d) { return '$' + d.unit_price; } },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'cost', name: 'cost', orderable: false, searchable: false, render: function(d) { return '$' + d; } },
                    { data: 'paid', name: 'paid', orderable: false, searchable: false, render: function(d) { return d ? 'Yes' : 'No'; } },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
