@extends('layouts.admin')
@section('page-title')Media Files@stop
@section('styles')
    @parent
@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Media Files') }}</h2>
            <div class="admin-page-header__actions">
                <button type="button" class="admin-btn admin-btn--outline" data-toggle="modal" data-target="#playlistModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Playlist') }}
                </button>
                <button type="button" class="admin-btn admin-btn--primary" data-toggle="modal" data-target="#mediaModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    {{ __('Upload Media') }}
                </button>
            </div>
        </div>
        <div class="admin-dt-wrap table-responsive">
            <table class="table table-hover table-bordered" id="media">
                <thead>
                    <tr>
                        <th>{{ __('File Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Upload Media File') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="loader-part" style="display:none;">
                    <div class="modal-body text-center py-4">
                        <div class="loader mx-auto mb-3"></div>
                        <p class="text-muted">{{ __('Uploading, please wait…') }}</p>
                    </div>
                </div>
                <div id="form-part">
                    <form method="POST" enctype="multipart/form-data" id="newMediaForm" action="{{ url('/admin/media') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>{{ __('Audio File') }}</label>
                                <input type="file" name="media_file" id="fileToUpload" class="form-control" accept=".mp3,audio/*">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <button id="mediaSave" type="submit" class="admin-btn admin-btn--primary">{{ __('Upload') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Media') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="POST" id="updateMediaForm">
                    @csrf
                    <input type="hidden" id="media_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('File Name') }}</label>
                            <input type="text" class="form-control" id="media_file_name" name="file_name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <textarea class="form-control" id="media_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="updateMediaSave" type="button" class="admin-btn admin-btn--primary">{{ __('Save') }}</button>
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
                    <h5 class="modal-title">{{ __('Remove Media File') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="GET" id="deleteMediaForm">
                    @csrf
                    <input type="hidden" id="deleteUrl" value="">
                    <div class="modal-body">
                        <p>Are you sure you want to remove this media file?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="deleteMediaSave" type="button" class="admin-btn admin-btn--danger">{{ __('Remove') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add to Playlist Modal -->
    <div class="modal fade" id="comsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add to Playlist') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="POST" id="comsForm" action="{{ url('/admin/mediaplaylist') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="redirect_url" name="redirect_url" value="">
                        <div class="form-group">
                            <label>{{ __('Media File') }}</label>
                            <input type="hidden" id="media_file_id" name="media_id">
                            <input type="text" class="form-control" id="media_file" disabled>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Playlist') }}</label>
                            <select name="playlist_id" class="form-control">
                                <option value="0">Select playlist</option>
                                @foreach($playlists as $playlist)
                                    <option value="{{ $playlist['id'] }}">{{ $playlist['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn--outline" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="addMediaPlaylistSave" type="submit" class="admin-btn admin-btn--primary">{{ __('Add') }}</button>
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
                            <label>{{ __('Playlist Name') }}</label>
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
    <script src="{{ asset('js/jquery.jplayer.js') }}"></script>
    <script src="{{ asset('js/jplayer.playlist.js') }}"></script>
    <script>
        function playFile(filename, s3file) {
            if (!s3file) { alert('Audio file missing from storage.'); return; }
            $("#playerModalTitle").html(filename);
            new jPlayerPlaylist({ jPlayer: "#jquery_jplayer_2", cssSelectorAncestor: "#jp_container_2" },
                [{ title: filename, mp3: s3file }],
                { swfPath: "../../dist/jplayer", supplied: "mp3", wmode: "window", useStateClassSkin: true, autoBlur: true, smoothPlayBar: true, keyEnabled: true }
            );
        }
        function playMedia(id, filename) {
            $.ajax({ url: '/media/' + id, type: 'GET',
                success: function(r) { playFile(r.file_name || filename, r.file_url); },
                error: function() { alert('Unable to load audio file.'); }
            });
        }
        function editMedia(id) {
            $.ajax({ url: '/media/' + id, type: 'GET',
                success: function(r) {
                    $('#media_id').val(r.id);
                    $('#media_file_name').val(r.file_name);
                    $('#media_description').val(r.description || '');
                }
            });
        }
        function deleteMedia(id) { $("#deleteUrl").val('{{ url("/admin/media/delete") }}/' + id); }
        function addToPlaylist(id, filename, redirect) {
            $("#media_file_id").val(id);
            $("#media_file").val(filename);
            $("#redirect_url").val(redirect);
        }

        $(document).ready(function() {
            $("#mediaSave").click(function() { $('#form-part').hide(); $('#loader-part').show(); });
            $("#updateMediaSave").click(function() {
                var id = $("#media_id").val();
                $("#updateMediaForm").attr("action", '{{ url("/admin/media/update") }}/' + id).submit();
            });
            $("#deleteMediaSave").click(function() {
                $("#deleteMediaForm").attr("action", $("#deleteUrl").val()).submit();
            });

            $('#media').DataTable({
                processing: true, serverSide: true,
                ajax: { url: '{{ url("/admin/media/datatables") }}' },
                columns: [
                    { data: 'file_name', name: 'file_name' },
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
