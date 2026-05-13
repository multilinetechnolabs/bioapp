@extends('layouts.admin')
@section('page-title')Playlists@stop
@section('styles')
    @parent
@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Playlists') }}</h2>
            <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#playlistModal">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Playlist') }}
            </button>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="playlists">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Playlist') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="POST" id="updatePlaylistForm">
                    @csrf
                    <input type="hidden" id="playlist_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="play_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <textarea class="form-control" id="play_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="updatePlaylistSave" type="button" class="admin-btn admin-btn--primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Remove Playlist') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="GET" id="deletePlaylistForm">
                    @csrf
                    <input type="hidden" id="deleteUrl" value="">
                    <div class="modal-body">
                        <p>Are you sure you want to remove this playlist and all its contents?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="deletePlaylistSave" type="button" class="admin-btn admin-btn--danger">{{ __('Remove') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Playlist Modal -->
    <div class="modal fade" id="playlistModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('New Playlist') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="POST" id="newPlaylistForm" action="{{ url('/admin/playlist') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="playlistSave" type="submit" class="admin-btn admin-btn--primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Player Modal -->
    <div class="modal fade" id="playerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="playerModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="jquery_jplayer_2" class="jp-jplayer"></div>
                    <div id="jp_container_2" class="jp-audio" role="application" aria-label="media player">
                        <div class="jp-type-playlist">
                            <div class="jp-gui jp-interface">
                                <div class="jp-controls">
                                    <button class="jp-previous" role="button" tabindex="0">previous</button>
                                    <button class="jp-play" role="button" tabindex="0">play</button>
                                    <button class="jp-next" role="button" tabindex="0">next</button>
                                    <button class="jp-stop" role="button" tabindex="0">stop</button>
                                </div>
                                <div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div>
                                <div class="jp-volume-controls">
                                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                                    <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                                    <div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div>
                                </div>
                                <div class="jp-time-holder">
                                    <div class="jp-current-time" role="timer">&nbsp;</div>
                                    <div class="jp-duration" role="timer">&nbsp;</div>
                                </div>
                                <div class="jp-toggles">
                                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                                    <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                                </div>
                            </div>
                            <div class="jp-playlist"><ul><li>&nbsp;</li></ul></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent
    <script src="{{ asset('/js/jquery.jplayer.js') }}"></script>
    <script src="{{ asset('/js/jplayer.playlist.js') }}"></script>
    <script>
        function editPlaylist(id) {
            $.ajax({ url: '/playlist/' + id, type: 'GET',
                success: function(r) {
                    $('#playlist_id').val(r.id);
                    $('#play_name').val(r.name);
                    $('#play_description').val(r.description || '');
                }
            });
        }
        function deletePlaylist(id) { $("#deleteUrl").val('{{ url("/admin/playlist/delete") }}/' + id); }
        function listenPlaylist(id, name) {
            $("#playerModalTitle").html(name);
            $.ajax({ url: '{{ url("/admin/playlist/media/") }}/' + id, dataType: 'json' })
                .done(function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        new jPlayerPlaylist({ jPlayer: "#jquery_jplayer_2", cssSelectorAncestor: "#jp_container_2" },
                            data, { swfPath: "../../dist/jplayer", supplied: "mp3", wmode: "window", useStateClassSkin: true, autoBlur: true, smoothPlayBar: true, keyEnabled: true });
                        $('#playerModal').modal('show');
                    } else { alert('No playable media found for this playlist.'); }
                })
                .fail(function() { alert('Unable to load playlist media.'); });
        }

        $(document).ready(function() {
            $("#updatePlaylistSave").click(function() {
                var id = $("#playlist_id").val();
                $("#updatePlaylistForm").attr("action", '{{ url("/admin/playlist/update") }}/' + id).submit();
            });
            $("#deletePlaylistSave").click(function() {
                $("#deletePlaylistForm").attr("action", $("#deleteUrl").val()).submit();
            });

            $('#playlists').DataTable({
                processing: true, serverSide: true,
                ajax: { url: '{{ url("/admin/playlist/datatables") }}' },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $("#playerModal").on('hidden.bs.modal', function() {
                $(this).removeData('bs.modal');
                $("#jquery_jplayer_2").jPlayer("destroy");
            });
        });
    </script>
@stop
