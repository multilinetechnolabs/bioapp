@extends('layouts.modern')

@section('page-title', 'Add Media to Playlist')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Add Media to Playlist') }}</h1>
                    <p class="modern-page-subtitle">Agregar medios a la lista</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel" style="padding: 24px;">
                    <form method="POST" id="mediaPlaylistForm" action="{{ url('/mediaplaylist') }}">
                        @csrf
                        <input type="hidden" id="playlist_id" name="playlist_id" value="{{ $playlist_id }}" />
                        <input type="hidden" name="redirect_url" value="playlist" />
                        <div class="row form-group">
                            <div class="col-md-5">
                                <label class="modern-form-label">{{ __('All media files in the system:') }}</label>
                                <select multiple="multiple" id='listBox1' class="form-control" style="height:300px;">
                                    @foreach($all_media as $key1 => $val2)
                                        <option value="{{ $key1 }}">{{ $val2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 text-center d-flex flex-column justify-content-center align-items-center" style="gap: 12px;">
                                <button type="button" id="btnRight" class="modern-btn modern-btn--primary modern-btn--small" title="Add to playlist">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                <button type="button" id="btnLeft" class="modern-btn modern-btn--small" title="Remove from playlist">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                            </div>
                            <div class="col-md-5">
                                <label class="modern-form-label">{{ __('Assigned media to playlist:') }}</label>
                                <select multiple="multiple" id='listBox2' class="form-control" name="media_ids[]" style="height:300px;">
                                    @foreach($assigned_media as $key2 => $val2)
                                        <option value="{{ $key2 }}">{{ $val2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button id="mediaPlaylistSave" type="button" onclick="saveToPlaylist()" class="modern-btn modern-btn--primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Save to Playlist') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.selectlistactions.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function saveToPlaylist(){
            $('#listBox2 option').prop('selected', true);
            $("#mediaPlaylistForm").submit();
        }

        $(document).ready(function() {
            $('#btnRight').click(function(e) {
                var selectedOpts = $('#listBox1 option:selected');
                if (selectedOpts.length == 0) { alert("Nothing to move."); e.preventDefault(); return; }
                $('#listBox2').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });

            $('#btnLeft').click(function(e) {
                var selectedOpts = $('#listBox2 option:selected');
                if (selectedOpts.length == 0) { alert("Nothing to move."); e.preventDefault(); return; }
                $('#listBox1').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });
        });
    </script>
@endpush
