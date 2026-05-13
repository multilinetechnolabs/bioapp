@extends('layouts.modern')

@section('page-title', 'Media Files')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Media Files') }}</h1>
                    <p class="modern-page-subtitle">Archivos de medios</p>
                </div>
                <div class="modern-page-header__actions">
                    <button type="button" class="modern-btn modern-btn--primary" data-toggle="modal" data-target="#mediaModal">
                        <span aria-hidden="true">+</span>
                        <span>{{ __('Upload Media') }} / Subir medios</span>
                    </button>
                    @if(Auth::user()->isPractitioner())
                        <button type="button" class="modern-btn modern-btn--outline" data-toggle="modal" data-target="#playlistModal">
                            <span aria-hidden="true">+</span>
                            <span>{{ __('New Playlist') }} / Nueva lista</span>
                        </button>
                    @endif
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="media">
                                <thead>
                                    <tr>
                                        <th class="align-center" style="width:30%">{{ __('File Name') }} / Nombre de archivo</th>
                                        <th class="align-center" style="width:50%">{{ __('Description') }} / Descripción</th>
                                        <th class="align-center" style="width:20%">{{ __('Actions') }} / Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- New Media File Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaModalTitle"> {{ __('New Media File') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="loader-part" style="display:none;">
                    <div class="modal-body">
                        <br/><br/><div class="loader" style="margin-left:150px;"></div><br/>
                        <p style="text-align:center">{{ __('Please wait... file upload is still being processed...') }}</p><br/>
                    </div>
                </div>
                <div id="form-part">
                    <form method="POST" enctype="multipart/form-data" id="newMediaForm" action='{{ url("/media") }}'>
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="modern-form-label" for="fileToUpload">{{ __('File') }}</label>
                                <input type="file" name="media_file" id="fileToUpload" class="form-control" accept=".mp3,audio/*">
                            </div>
                            <div class="form-group">
                                <label class="modern-form-label" for="description">{{ __('Description') }}</label>
                                <textarea class="form-control modern-data-cache-input w-100" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Close') }}</button>
                            <button id="mediaSave" type="submit" class="modern-btn modern-btn--primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Media File Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle"> {{ __('Update Media Details') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="updateMediaForm">
                    @csrf
                    <input type="hidden" id="media_id" name="id" value="" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="modern-form-label" for="media_file_name">{{ __('File Name') }}</label>
                            <input type="text" class="form-control modern-data-cache-input w-100" id="media_file_name" name="file_name" value="" required>
                        </div>
                        <div class="form-group">
                            <label class="modern-form-label" for="media_description">{{ __('Description') }}</label>
                            <textarea class="form-control modern-data-cache-input w-100" id="media_description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="updateMediaSave" type="button" class="modern-btn modern-btn--primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Media File Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalTitle"> {{ __('Remove Media File') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="GET" id="deleteMediaForm">
                    @csrf
                    <input type="hidden" id="deleteUrl" value="">
                    <div class="modal-body">
                        <h5>Are you sure you want to remove this media file? / ¿Estás seguro de que deseas eliminar este archivo?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button id="deleteMediaSave" type="button" class="modern-btn modern-btn--danger">{{ __('Remove') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add media to playlist -->
    <div class="modal fade" id="comsModal" tabindex="-1" role="dialog" aria-labelledby="comsModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comsModalTitle"> {{ __('Add Media to Playlist') }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="comsForm" action="{{ url('/mediaplaylist') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="redirect_url" name="redirect_url" value="" />
                        <div class="form-group">
                            <label class="modern-form-label">{{ __('Media File') }}</label>
                            <input type="hidden" id="media_file_id" name="media_id" value="" />
                            <input type="text" class="form-control" id="media_file" value="" disabled>
                        </div>
                        <div class="form-group">
                            <label class="modern-form-label" for="playlist_id">{{ __('Playlist') }}</label>
                            <select name="playlist_id" class="form-control modern-data-cache-select w-100">
                                <option value="0">Select</option>
                                @foreach($playlists as $playlist)
                                    <option value="{{ $playlist['id'] }}">{{ $playlist['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-btn" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="addMediaPlaylistSave" type="submit" class="modern-btn modern-btn--primary">{{ __('Save') }}</button>
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
                    <h5 class="modal-title" id="playerModalTitle"></h5>
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
        function playFile(filename, s3file){
            if (!s3file) {
                alert('This audio file is missing from storage. Please re-upload it.');
                return;
            }
            $("#playerModalTitle").html(filename);
            var myPlaylist = new jPlayerPlaylist({
                    jPlayer: "#jquery_jplayer_2",
                    cssSelectorAncestor: "#jp_container_2"
                }, [
                {
                    title: filename,
                    mp3: s3file
                }
                ], {
                playlistOptions: {
                    enableRemoveControls: false
                },
                swfPath: "../../dist/jplayer",
                supplied: "mp3",
                wmode: "window",
                useStateClassSkin: true,
                autoBlur: true,
                smoothPlayBar: true,
                keyEnabled: true
            });
        }

        function playMedia(id, filename){
            $.ajax({
                url: '/media/' + id,
                type: 'GET',
                success: function(result) {
                    playFile(result.file_name || filename, result.file_url);
                },
                error: function() {
                    alert('Unable to load this audio file right now.');
                }
            });
        }

        function editMedia(id){
            $.ajax({
                url: '/media/'+id,
                type: 'GET',
                success: function(result) {
                    $('#media_id').val(result.id);
                    $('#media_file_name').val(result.file_name);
                    $('#media_description').val(result.description != null ? String(result.description) : '');
                }
            });
        }

        function deleteMedia(id){
            var durl = '{{ url("/media/delete")}}'+'/'+id;
            $("#deleteUrl").val(durl);
        }

        function addToPlaylist(id, filename, redirect){
            $("#media_file_id").val(id);
            $("#media_file").val(filename);
            $("#redirect_url").val(redirect);
        }

        $(document).ready(function() {
            $("#mediaSave").click(function(e) {
                $('#form-part').hide();
                $('#loader-part').show();
            });

            $("#updateMediaSave").click(function(e) {
                var id  = $("#media_id").val();
                var url = '{{ url("/media/update")}}'+'/'+id;
                $("#updateMediaForm").attr("action", url);
                $("#updateMediaForm").submit();
            });

            $("#deleteMediaSave").click(function(e) {
                var url = $("#deleteUrl").val();
                $("#deleteMediaForm").attr("action", url);
                $("#deleteMediaForm").submit();
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
            $('#media').DataTable({
                processing: true,
                serverSide: true,
                language: dtLang,
                ajax: { url : '{{ url("/media/datatables") }}' },
                columns: [
                    { data: 'file_name', name: 'file_name' },
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
