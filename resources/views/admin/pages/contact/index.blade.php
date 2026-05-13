@extends('layouts.admin')
@section('page-title', 'Contact Messages')
@section('styles')
    @parent
@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">
                Contact Messages
                @if($unread > 0)
                    <span style="display:inline-flex;align-items:center;justify-content:center;background:#dc2626;color:#fff;font-size:0.7rem;font-weight:700;border-radius:999px;min-width:20px;height:20px;padding:0 5px;margin-left:0.4rem;vertical-align:middle;">{{ $unread }}</span>
                @endif
            </h2>
            <div class="admin-page-header__actions">
                <a href="{{ route('admin.contact.mark-all-read') }}" class="admin-btn admin-btn--outline">Mark All Read</a>
            </div>
        </div>

        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="contactTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th class="text-center">Email Sent</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Message modal --}}
    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="msgModalTitle">Message</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1" style="font-size:0.8rem;color:#64748b;" id="msgModalMeta"></p>
                    <hr>
                    <p style="white-space:pre-wrap;font-size:0.875rem;" id="msgModalBody"></p>
                </div>
                <div class="modal-footer">
                    <a id="msgReplyBtn" href="#" class="admin-btn admin-btn--primary">Reply via Email</a>
                    <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script>
    $(document).ready(function () {
        var icEye   = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
        var icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

        var table = $('#contactTable').DataTable({
            ajax: { url: '{{ route("admin.contact.datatables") }}', dataSrc: 'data' },
            order: [[0, 'desc']],
            columns: [
                { data: 'created_at' },
                { data: 'name' },
                { data: 'email' },
                { data: 'subject' },
                {
                    data: 'email_sent', orderable: false,
                    render: function (v) {
                        return v
                            ? '<span style="color:#16a34a;font-weight:600;">✓ Sent</span>'
                            : '<span style="color:#dc2626;font-weight:600;">✗ Failed</span>';
                    }
                },
                {
                    data: 'is_read', orderable: false,
                    render: function (v, type, row) {
                        return v
                            ? '<span style="color:#64748b;font-size:0.8rem;">Read</span>'
                            : '<span style="color:#f97316;font-weight:700;font-size:0.8rem;">● Unread</span>';
                    }
                },
                {
                    data: null, orderable: false,
                    render: function (d) {
                        return '<div class="admin-action-group">'
                            + '<button class="admin-action-btn admin-action-btn--view btn-view" data-id="' + d.id + '" data-name="' + d.name + '" data-email="' + d.email + '" data-subject="' + d.subject + '" data-msg="' + encodeURIComponent(d.message) + '" data-date="' + d.created_at + '">' + icEye + '</button>'
                            + '<button class="admin-action-btn admin-action-btn--delete btn-delete" data-id="' + d.id + '">' + icTrash + '</button>'
                            + '</div>';
                    }
                }
            ],
            createdRow: function (row, data) {
                if (!data.is_read) $(row).css('font-weight', '600');
            }
        });

        // View message
        $('#contactTable').on('click', '.btn-view', function () {
            var d = $(this);
            var id = d.data('id');
            $('#msgModalTitle').text(d.data('subject') !== '—' ? d.data('subject') : 'Message from ' + d.data('name'));
            $('#msgModalMeta').text('From: ' + d.data('name') + ' <' + d.data('email') + '>  ·  ' + d.data('date'));
            $('#msgModalBody').text(decodeURIComponent(d.data('msg')));
            $('#msgReplyBtn').attr('href', 'mailto:' + d.data('email') + '?subject=Re: ' + encodeURIComponent(d.data('subject') !== '—' ? d.data('subject') : 'Your message'));
            $('#msgModal').modal('show');

            // Mark as read
            $.post('{{ url("admin/contact") }}/' + id + '/read', { _token: '{{ csrf_token() }}' }, function () {
                table.ajax.reload(null, false);
            });
        });

        // Delete message
        $('#contactTable').on('click', '.btn-delete', function () {
            var id = $(this).data('id');
            if (!confirm('Delete this message?')) return;
            $.ajax({
                url: '{{ url("admin/contact") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () { table.ajax.reload(null, false); }
            });
        });
    });
    </script>
@stop
