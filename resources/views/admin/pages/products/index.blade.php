@extends('layouts.admin')
@section('page-title')Products@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Products') }}</h2>
            <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#productModal" data-title="New Product">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Product') }}
            </button>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="products">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Weight') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Category') }}</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Unit Price') }}</label>
                            <input type="number" class="form-control" id="unit_price" name="unit_price" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Size') }}</label>
                            <input type="text" class="form-control" id="size" name="size" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Weight') }}</label>
                            <input type="text" class="form-control" id="weight" name="weight" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="admin-btn admin-btn--primary save-btn">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#productModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#productModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/products/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#product_id').val(r.id);
                            $('#user_id').val(r.user_id);
                            $('#name').val(r.name);
                            $('#description').val(r.description);
                            $('#category').val(r.category);
                            $('#unit_price').val(r.unit_price);
                            $('#size').val(r.size);
                            $('#weight').val(r.weight);
                        }
                    });
                } else {
                    $('#product_id, #user_id, #name, #description, #category, #unit_price, #size, #weight').val('');
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#product_id').val();
                var data = { user_id: $('#user_id').val(), name: $('#name').val(), description: $('#description').val(), category: $('#category').val(), unit_price: $('#unit_price').val(), size: $('#size').val(), weight: $('#weight').val() };
                var url = '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/products' + (id ? '/' + id : '');
                $.ajax({ url: url, type: id ? 'PUT' : 'POST', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#products').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this product?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/products/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#products').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/products/datatables' },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'category', name: 'category' },
                    { data: 'unit_price', name: 'unit_price' },
                    { data: 'size', name: 'size' },
                    { data: 'weight', name: 'weight' },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#productModal" data-title="Edit Product" data-id="' + data + '">' + icEdit + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
