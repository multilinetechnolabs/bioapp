@extends('layouts.admin')
@section('page-title')Body Scan Model Labels@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Model Labels — Body Scan') }}</h2>
            <div class="admin-page-header__actions">
                <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#modelLabelModal" data-title="New Model Label">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Model Label') }}
                </button>
            </div>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="model_labels">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Target') }}</th>
                        <th>{{ __('Point') }}</th>
                        <th>{{ __('Point X') }}</th>
                        <th>{{ __('Point Y') }}</th>
                        <th>{{ __('Point Z') }}</th>
                        <th>{{ __('Label') }}</th>
                        <th>{{ __('Label X') }}</th>
                        <th>{{ __('Label Y') }}</th>
                        <th>{{ __('Label Z') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Model Label Modal -->
    <div class="modal fade" id="modelLabelModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelLabelModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="model_label_id" name="model_label_id">
                        <div class="form-group">
                            <label>{{ __('Scan Type') }}</label>
                            <select class="form-control" id="scan_type" name="scan_type" required disabled>
                                <option value="body_scan" selected>Body Scan</option>
                                <option value="chakra_scan">Chakra Scan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Target') }}</label>
                            <select class="form-control" id="target" name="target" required>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Point') }}</label>
                            <select class="form-control" id="pair_id" name="pair_id" required>
                                @foreach(\App\Models\Pair::orderBy('name', 'asc')->where('scan_type', '=', 'body_scan')->get() as $pair)
                                    <option value="{{$pair->id}}">{{$pair->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label>{{ __('Point Coordinates') }}</label>
                        <div class="form-group row">
                            <div class="col">
                                <label>X</label>
                                <input type="number" class="form-control" id="point_x" name="point_x" required>
                            </div>
                            <div class="col">
                                <label>Y</label>
                                <input type="number" class="form-control" id="point_y" name="point_y" required>
                            </div>
                            <div class="col">
                                <label>Z</label>
                                <input type="number" class="form-control" id="point_z" name="point_z" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Label') }}</label>
                            <input type="text" class="form-control" id="label" name="label" required>
                        </div>
                        <label>{{ __('Label Coordinates') }}</label>
                        <div class="form-group row">
                            <div class="col">
                                <label>X</label>
                                <input type="number" class="form-control" id="label_x" name="label_x" required>
                            </div>
                            <div class="col">
                                <label>Y</label>
                                <input type="number" class="form-control" id="label_y" name="label_y" required>
                            </div>
                            <div class="col">
                                <label>Z</label>
                                <input type="number" class="form-control" id="label_z" name="label_z" required>
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
            $('#modelLabelModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#modelLabelModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/model_labels/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#model_label_id').val(r.id);
                            $('#scan_type').val(r.scan_type);
                            $('#target').val(r.target);
                            $('#pair_id').val(r.pair_id);
                            $('#point_x').val(r.point_x);
                            $('#point_y').val(r.point_y);
                            $('#point_z').val(r.point_z);
                            $('#label').val(r.label);
                            $('#label_x').val(r.label_x);
                            $('#label_y').val(r.label_y);
                            $('#label_z').val(r.label_z);
                        }
                    });
                } else {
                    $('#model_label_id').val('');
                    $('#target').val('female');
                    $('#scan_type').val('body_scan');
                    $('#pair_id').val(null);
                    $('#point_x, #point_y, #point_z, #label_x, #label_y, #label_z').val(0);
                    $('#label').val('');
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#model_label_id').val();
                var data = {
                    target: $('#target').val(), scan_type: $('#scan_type').val(), pair_id: $('#pair_id').val(),
                    point_x: $('#point_x').val(), point_y: $('#point_y').val(), point_z: $('#point_z').val(),
                    label: $('#label').val(), label_x: $('#label_x').val(), label_y: $('#label_y').val(), label_z: $('#label_z').val()
                };
                if (!data.target) { alert('Target must be filled out.'); $('#target').focus(); return; }
                if (!data.pair_id) { alert('Point must be filled out.'); $('#pair_id').focus(); return; }
                var url = '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/model_labels' + (id ? '/' + id : '');
                $.ajax({ url: url, type: id ? 'PUT' : 'POST', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#model_labels').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this model label?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/model_labels/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#model_labels').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/model_labels/datatables?scan_type=body_scan' },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'target', name: 'target' },
                    { data: 'point.name', name: 'point_name', searchable: false, orderable: false },
                    { data: 'point_x', name: 'point_x' },
                    { data: 'point_y', name: 'point_y' },
                    { data: 'point_z', name: 'point_z' },
                    { data: 'label', name: 'label' },
                    { data: 'label_x', name: 'label_x' },
                    { data: 'label_y', name: 'label_y' },
                    { data: 'label_z', name: 'label_z' },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#modelLabelModal" data-title="Edit Model Label" data-id="' + data + '">' + icEdit + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
