@extends('layouts.admin')
@section('page-title')Users@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Users') }}</h2>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="users">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Business') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Privacy') }}</th>
                        <th>{{ __('Email Verified') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Type') }}</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="null"></option>
                                @foreach(\Spatie\Permission\Models\Role::orderBy('name', 'asc')->get() as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Business') }}</label>
                            <input type="text" class="form-control" id="business" name="business">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Location') }}</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="privacy" name="privacy">
                            <label class="form-check-label" for="privacy">{{ __('Privacy') }}</label>
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
            $('#userModal').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                $('#userModalTitle').text(trigger.data('title'));
                var id = trigger.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/users/' + id,
                        type: 'GET',
                        success: function(r) {
                            $('#user_id').val(r.id);
                            $('#name').val(r.name);
                            $('#email').val(r.email);
                            $('#type').val(r.type ? r.type.toLowerCase() : null);
                            $('#business').val(r.business);
                            $('#location').val(r.location);
                            $('#privacy').prop('checked', r.privacy == 1);
                        }
                    });
                }
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#user_id').val();
                var data = { name: $('#name').val(), email: $('#email').val(), type: $('#type').val(), business: $('#business').val(), location: $('#location').val(), privacy: $('#privacy').is(':checked') ? 1 : 0 };
                $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/users/' + id, type: 'PUT', data: data, dataType: 'JSON', success: function() { location.reload(); } });
            });

            $('#users').on('click', 'button.editor-remove', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you wish to delete this user?')) {
                    $.ajax({ url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/users/' + id, type: 'DELETE', success: function() { location.reload(); } });
                }
            });

            var icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
            var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
            var icView  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';

            $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION") }}/users/datatables' },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'type', name: 'type', orderable: false, searchable: false },
                    { data: 'business', name: 'business' },
                    { data: 'location', name: 'location' },
                    { data: 'privacy', render: function(d) { return d == 1 ? 'Yes' : 'No'; } },
                    { data: 'email_verified_at', render: function(d) { return d ? 'Yes' : 'No'; } },
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: function(data) {
                            return '<div class="admin-action-group">'
                                + '<button class="admin-action-btn admin-action-btn--delete editor-remove" data-id="' + data + '">' + icTrash + '</button>'
                                + '<button class="admin-action-btn admin-action-btn--edit editor-edit" data-toggle="modal" data-target="#userModal" data-title="Edit User" data-id="' + data + '">' + icEdit + '</button>'
                                + '<a href="/admin/users/' + data + '/subscriptions" class="admin-action-btn admin-action-btn--view">' + icView + '</a>'
                                + '</div>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
