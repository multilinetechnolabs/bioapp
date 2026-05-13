@extends('layouts.admin')
@section('page-title')Protocol Pairs@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Protocol Pairs') }}</h2>
            <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#drGoizPairModal" data-title="New Protocol Pair">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Protocol Pair') }}
            </button>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="dr_goiz_pairs">
                <thead>
                    <tr>
                        <th>{{ __('Place') }}</th>
                        <th>{{ __('Resonance') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Characteristic') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Protocol Pair Modal -->
    <div class="modal fade" id="drGoizPairModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="drGoizPairModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="dr_goiz_pair_id" name="dr_goiz_pair_id">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Place (EN)</label>
                                <input type="text" class="form-control" id="place" name="place">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Place (ES)</label>
                                <input type="text" class="form-control" id="place_es" name="place_es">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Resonance (EN)</label>
                                <input type="text" class="form-control" id="resonance" name="resonance">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Resonance (ES)</label>
                                <input type="text" class="form-control" id="resonance_es" name="resonance_es">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name (EN)</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Name (ES)</label>
                                <input type="text" class="form-control" id="name_es" name="name_es">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Characteristic (EN)</label>
                                <textarea class="form-control" id="characteristic" name="characteristic" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Characteristic (ES)</label>
                                <textarea class="form-control" id="characteristic_es" name="characteristic_es" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Description (EN)</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Description (ES)</label>
                                <textarea class="form-control" id="description_es" name="description_es" rows="3"></textarea>
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
            $('#drGoizPairModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#drGoizPairModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/dr_goiz_pairs/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#dr_goiz_pair_id').val(r.id);
                            $('#place').val(r.place || '');
                            $('#place_es').val(r.place_es || '');
                            $('#resonance').val(r.resonance || '');
                            $('#resonance_es').val(r.resonance_es || '');
                            $('#name').val(r.name || '');
                            $('#name_es').val(r.name_es || '');
                            $('#characteristic').val(r.characteristic != null ? String(r.characteristic) : '');
                            $('#characteristic_es').val(r.characteristic_es != null ? String(r.characteristic_es) : '');
                            $('#description').val(r.description != null ? String(r.description) : '');
                            $('#description_es').val(r.description_es != null ? String(r.description_es) : '');
                        }
                    });
                } else {
                    $('#dr_goiz_pair_id, #place, #place_es, #resonance, #resonance_es, #name, #name_es, #characteristic, #characteristic_es, #description, #description_es').val('');
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#dr_goiz_pair_id').val();
                if (!$('#name').val()) { alert('Name (EN) must be filled out.'); $('#name').focus(); return; }
                var data = { place: $('#place').val(), place_es: $('#place_es').val(), resonance: $('#resonance').val(), resonance_es: $('#resonance_es').val(), name: $('#name').val(), name_es: $('#name_es').val(), characteristic: $('#characteristic').val(), characteristic_es: $('#characteristic_es').val(), description: $('#description').val(), description_es: $('#description_es').val() };
                var url = '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/dr_goiz_pairs' + (id ? '/' + id : '');
                $.ajax({ url: url, type: id ? 'PUT' : 'POST', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#dr_goiz_pairs').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this protocol pair?')) {
                    $.ajax({ url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/dr_goiz_pairs/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#dr_goiz_pairs').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env('APP_WEB_API_URL') }}/{{ env('APP_WEB_API_VERSION') }}/dr_goiz_pairs/datatables' },
                columns: [
                    { data: 'place', name: 'place', render: function(d, t, r) { var es = r.place_es || ''; return d + (es ? ' / <span style="color:#888;font-style:italic;">' + es + '</span>' : ''); } },
                    { data: 'resonance', name: 'resonance', render: function(d, t, r) { var es = r.resonance_es || ''; return d + (es ? ' / <span style="color:#888;font-style:italic;">' + es + '</span>' : ''); } },
                    { data: 'name', name: 'name', render: function(d, t, r) { var es = r.name_es || ''; return d + (es ? ' / <span style="color:#888;font-style:italic;">' + es + '</span>' : ''); } },
                    { data: 'characteristic', name: 'characteristic', render: function(d, t, r) { var es = r.characteristic_es || ''; return (d || '') + (es ? ' / <span style="color:#888;font-style:italic;">' + es + '</span>' : ''); } },
                    { data: 'description', name: 'description', render: function(d, t, r) { var es = r.description_es || ''; return (d || '') + (es ? ' / <span style="color:#888;font-style:italic;">' + es + '</span>' : ''); } },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#drGoizPairModal" data-title="Edit Protocol Pair" data-id="' + data + '">' + icEdit + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
