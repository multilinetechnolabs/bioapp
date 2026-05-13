@extends('layouts.admin')
@section('page-title')Bio Pairs@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Bio Pairs') }}</h2>
            <div class="admin-page-header__actions">
                <button type="button" class="admin-btn admin-btn--outline" onclick="window.location.href='/admin/pairs/import?scan_type=body_scan'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    {{ __('Import') }}
                </button>
                <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#pairModal" data-title="New Bio Pair">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Pair') }}
                </button>
            </div>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="pairs">
                <thead>
                    <tr>
                        <th>{{ __('Ref No.') }}</th>
                        <th>{{ __('Guided Ref No.') }}</th>
                        <th>{{ __('Scan Type') }}</th>
                        <th>{{ __('Points / Name') }}</th>
                        <th>{{ __('Radical') }}</th>
                        <th>{{ __('Start / Origin') }}</th>
                        <th>{{ __('Leads / Symptoms') }}</th>
                        <th>{{ __('Path / Route / Cause and Effect') }}</th>
                        <th>{{ __('Alternative Routes') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Pair Modal -->
    <div class="modal fade" id="pairModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pairModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="pair_id" name="pair_id">
                        <div class="form-group">
                            <label>{{ __('Scan Type') }}</label>
                            <select class="form-control" id="scan_type" name="scan_type" required>
                                <option value="body_scan">Bio</option>
                                <option value="chakra_scan">Chakra</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ __('Ref No.') }}</label>
                                <input type="text" class="form-control" id="ref_no" name="ref_no" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Guided Ref No.') }}</label>
                                <input type="text" class="form-control" id="guided_ref_no" name="guided_ref_no">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Radical') }}</label>
                                <input type="text" class="form-control" id="radical" name="radical">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ __('Start / Origin') }}</label>
                                <textarea class="form-control" id="origins" name="origins"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Leads / Symptoms') }}</label>
                                <textarea class="form-control" id="symptoms" name="symptoms"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ __('Path / Route / Cause and Effect') }}</label>
                                <textarea class="form-control" id="paths" name="paths"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Alternative Routes') }}</label>
                                <textarea class="form-control" id="alternative_routes" name="alternative_routes"></textarea>
                            </div>
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
            $('#pairModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#pairModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/pairs/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#pair_id').val(r.id);
                            $('#scan_type').val(r.scan_type).prop('disabled', false);
                            $('#ref_no').val(r.ref_no);
                            $('#guided_ref_no').val(r.guided_ref_no);
                            $('#name').val(r.name);
                            $('#radical').val(r.radical);
                            $('#origins').val(r.origins != null ? String(r.origins) : '');
                            $('#symptoms').val(r.symptoms != null ? String(r.symptoms) : '');
                            $('#paths').val(r.paths != null ? String(r.paths) : '');
                            $('#alternative_routes').val(r.alternative_routes != null ? String(r.alternative_routes) : '');
                        }
                    });
                } else {
                    $('#pair_id, #ref_no, #guided_ref_no, #name, #radical, #origins, #symptoms, #paths, #alternative_routes').val('');
                    $('#scan_type').val('body_scan').prop('disabled', true);
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#pair_id').val();
                if (!$('#scan_type').val()) { alert('Scan Type must be filled out.'); $('#scan_type').focus(); return; }
                var data = { scan_type: $('#scan_type').val(), ref_no: $('#ref_no').val(), guided_ref_no: $('#guided_ref_no').val(), name: $('#name').val(), radical: $('#radical').val(), origins: $('#origins').val(), symptoms: $('#symptoms').val(), paths: $('#paths').val(), alternative_routes: $('#alternative_routes').val() };
                var url = '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/pairs' + (id ? '/' + id : '');
                $.ajax({ url: url, type: id ? 'PUT' : 'POST', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#pairs').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this pair?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/pairs/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#pairs').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/pairs/datatables?scan_type=body_scan' },
                columns: [
                    { data: 'ref_no', render: function(d) { return d || '-'; } },
                    { data: 'guided_ref_no', render: function(d) { return d || '-'; } },
                    { data: 'scan_type', render: function(d) { return d === 'body_scan' ? 'Bio' : d === 'chakra_scan' ? 'Chakra' : d; } },
                    { data: 'name', name: 'name' },
                    { data: 'radical', name: 'radical' },
                    { data: 'origins', name: 'origins' },
                    { data: 'symptoms', name: 'symptoms' },
                    { data: 'paths', name: 'paths' },
                    { data: 'alternative_routes', name: 'alternative_routes' },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#pairModal" data-title="Edit Bio Pair" data-id="' + data + '">' + icEdit + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
