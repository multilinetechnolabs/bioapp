@extends('layouts.admin')
@section('page-title')Subscriptions@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Subscriptions') }}</h2>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="subscriptions">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Starts At') }}</th>
                        <th>{{ __('Ends At') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subscriptionModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="subscription_id" name="subscription_id">
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="form-group">
                            <label>{{ __('Plan') }}</label>
                            <select class="form-control" id="plan_id" name="plan_id" required>
                                <option value=""></option>
                                @foreach(\App\Models\Plan::orderBy('name', 'asc')->get() as $plan)
                                    <option value="{{ $plan->id }}">{{ ucfirst($plan->name) . ' ($' . number_format($plan->price, 2) . ')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Starts At') }}</label>
                            <input type="date" class="form-control" id="starts_at" name="starts_at">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Ends At') }}</label>
                            <input type="date" class="form-control" id="ends_at" name="ends_at">
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
            function formatDate(date) {
                var d = new Date(date), m = '' + (d.getMonth() + 1), day = '' + d.getDate(), y = d.getFullYear();
                if (m.length < 2) m = '0' + m;
                if (day.length < 2) day = '0' + day;
                return [y, m, day].join('-');
            }

            $('#subscriptionModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#subscriptionModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/subscriptions/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#subscription_id').val(id);
                            $('#user_id').val(r.user_id);
                            $('#starts_at').val(formatDate(r.starts_at));
                            $('#ends_at').val(formatDate(r.ends_at));
                            $('#plan_id').val(r.plan_id);
                        }
                    });
                } else {
                    $('#subscription_id, #user_id, #starts_at, #ends_at').val('');
                    $('#plan_id').val('');
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#subscription_id').val();
                var data = { user_id: $('#user_id').val(), plan_id: $('#plan_id').val(), starts_at: $('#starts_at').val(), ends_at: $('#ends_at').val() };
                var url = '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/subscriptions' + (id ? '/' + id : '');
                $.ajax({ url: url, type: id ? 'PUT' : 'POST', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#subscriptions').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this subscription?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/subscriptions/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

            $('#subscriptions').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/subscriptions/datatables' },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user', orderable: false, searchable: false, render: function(d) { return d.name; } },
                    { data: 'plan', orderable: false, searchable: false, render: function(d) { return d.name + ' ($' + parseFloat(d.price).toFixed(2) + ')'; } },
                    { data: 'starts_at', render: function(d) { return new Date(d).toLocaleString(); } },
                    { data: 'ends_at', render: function(d) { return new Date(d).toLocaleString(); } },
                    {
                        data: 'id', orderable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#subscriptionModal" data-title="Edit Subscription" data-id="' + data + '">' + icEdit + '</button>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
