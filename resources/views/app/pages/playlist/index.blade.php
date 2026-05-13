@extends('layouts.modern')

@section('page-title', 'Playlists')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Playlists') }}</h1>
                    <p class="modern-page-subtitle">Mis listas de reproducción</p>
                </div>
                <div>
                    <button type="button" class="modern-btn modern-btn--primary" data-toggle="modal" data-target="#playlistModal">
                        <span aria-hidden="true">+</span>
                        <span>{{ __('New Playlist') }}</span>
                    </button>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="playlists">
                                <thead>
                                    <tr>
                                        <th class="align-center">{{ __('Name') }} / Nombre</th>
                                        <th class="align-center">{{ __('Description') }} / Descripción</th>
                                        <th class="align-center">{{ __('Actions') }} / Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Edit Playlist Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle"> {{ __('Update Playlist') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="updatePlaylistForm">
                    @csrf
                    <input type="hidden" id="playlist_id" name="id" value="" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="modern-form-label" for="play_name">{{ __('Name') }}</label>
                            <input type="text" class="form-control modern-data-cache-input w-100" id="play_name" name="name" value="" required>
                        </div>
                        <div class="form-group">
                            <label class="modern-form-label" for="play_description">{{ __('Description') }}</label>
                            <textarea class="form-control modern-data-cache-input w-100" id="play_description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="updatePlaylistSave" type="button" class="modern-btn modern-btn--primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Playlist Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalTitle"> {{ __('Remove Playlist') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="GET" id="deletePlaylistForm">
                    @csrf
                    <input type="hidden" id="deleteUrl" value="">
                    <div class="modal-body">
                        <h5>Are you sure you want to remove this playlist and all its contents? / ¿Estás seguro de que deseas eliminar esta lista y todo su contenido?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="deletePlaylistSave" type="button" class="modern-btn modern-btn--danger">{{ __('Remove') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Playlist Modal -->
    <div class="modal fade" id="playlistModal" tabindex="-1" role="dialog" aria-labelledby="playlistModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="playlistModalTitle"> {{ __('New Playlist') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="newPlaylistForm" action="{{ url('/playlist') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="modern-form-label" for="name">{{ __('Playlist Name') }}</label>
                            <input type="text" class="form-control modern-data-cache-input w-100" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="modern-form-label" for="description">{{ __('Description') }}</label>
                            <textarea class="form-control modern-data-cache-input w-100" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="playlistSave" type="submit" class="modern-btn modern-btn--primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Player Modal -->
    <div class="modal fade" id="playerModal" tabindex="-1" role="dialog" aria-labelledby="playerModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="playerModalTitle"> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                                <div class="jp-progress">
                                    <div class="jp-seek-bar">
                                        <div class="jp-play-bar"></div>
                                    </div>
                                </div>
                                <div class="jp-volume-controls">
                                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                                    <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                                <div class="jp-time-holder">
                                    <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                                    <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                                </div>
                                <div class="jp-toggles">
                                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                                    <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                                </div>
                            </div>
                            <div class="jp-playlist">
                                <ul>
                                    <li>&nbsp;</li>
                                </ul>
                            </div>
                            <div class="jp-no-solution">
                                <span>Update Required</span>
                                To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.jplayer.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jplayer.playlist.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function editPlaylist(id){
            $.ajax({
                url: '/playlist/'+id,
                type: 'GET',
                success: function(result) {
                    $('#playlist_id').val(result.id);
                    $('#play_name').val(result.name);
                    $('#play_description').val(result.description != null ? String(result.description) : '');
                }
            });
        }

        function deletePlaylist(id){
            var durl = '{{ url("/playlist/delete")}}'+'/'+id;
            $("#deleteUrl").val(durl);
        }

        function listenPlaylist(id, name){
            $("#playerModalTitle").html(name);
            var get_mediaurl = '{{ url("/playlist/media/")}}'+'/'+id;
            $.ajax({
                url: get_mediaurl,
                dataType: 'json'
            }).done(function(data){
                if (Array.isArray(data) && data.length > 0) {
                    new jPlayerPlaylist({
                        jPlayer: "#jquery_jplayer_2",
                        cssSelectorAncestor: "#jp_container_2"
                    },
                    data,
                    {
                        swfPath: "../../dist/jplayer",
                        supplied: "mp3",
                        wmode: "window",
                        useStateClassSkin: true,
                        autoBlur: true,
                        smoothPlayBar: true,
                        keyEnabled: true
                    });
                    $('#playerModal').modal('show');
                } else {
                    alert('No playable media files were found for this playlist.');
                }
            }).fail(function(xhr){
                console.error('Unable to load playlist media.', xhr);
                alert('Unable to load playlist media right now.');
            });
        }

        $(document).ready(function() {
            $("#updatePlaylistSave").click(function(e) {
                var id  = $("#playlist_id").val();
                var url = '{{ url("/playlist/update")}}'+'/'+id;
                $("#updatePlaylistForm").attr("action", url);
                $("#updatePlaylistForm").submit();
            });

            $("#deletePlaylistSave").click(function(e) {
                var url = $("#deleteUrl").val();
                $("#deletePlaylistForm").attr("action", url);
                $("#deletePlaylistForm").submit();
            });

            var dtLang = {
                search: "Search / Buscar:",
                searchPlaceholder: "Search... / Buscar...",
                processing: "Processing... / Procesando...",
                lengthMenu: "Show _MENU_ entries / Mostrar _MENU_ entradas",
                info: "Showing _START_ to _END_ of _TOTAL_ entries / Mostrando _START_ a _END_ de _TOTAL_ entradas",
                infoEmpty: "No entries found / Sin entradas",
                infoFiltered: "(filtered from _MAX_ total / filtrado de _MAX_ totales)",
                zeroRecords: "No matching records found / No se encontraron registros",
                emptyTable: "No data available / No hay datos disponibles",
                paginate: {
                    first: "First / Primero",
                    last: "Last / Último",
                    next: "Next / Siguiente",
                    previous: "Previous / Anterior"
                }
            };
            $('#playlists').DataTable({
                processing: true,
                serverSide: true,
                language: dtLang,
                ajax: { url : '{{ url("/playlist/datatables") }}' },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $("#playerModal").on('hidden.bs.modal', function(e){
                $(this).removeData('bs.modal');
                $("#jquery_jplayer_2").jPlayer("destroy");
            });
        });
    </script>
@endpush
