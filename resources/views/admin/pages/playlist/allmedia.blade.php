@extends('layouts.admin')
@section('page-title')Playlist Media@stop
@section('styles')
    @parent
@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Assign Media to Playlist') }}</h2>
        </div>
        <form method="POST" id="mediaPlaylistForm" action="{{ url('/admin/mediaplaylist') }}">
            @csrf
            <input type="hidden" id="playlist_id" name="playlist_id" value="{{$playlist_id}}" />
            <input type="hidden" name="redirect_url" value="admin/playlist" />
            <div class="row form-group col-md-12">
                <div class="col-md-5">
                    <label>{{ __('All media files in the system:') }}</label>
                    <select multiple="multiple" id='listBox1' class="form-control" style="height:300px;">
                        @foreach($all_media as $key1 => $val2)
                            <option value="{{$key1}}">{{$val2}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-center" style="margin-top:130px;">
                    <button type="button" id="btnRight" class="admin-btn admin-btn--outline" style="width:42px;height:42px;padding:0;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <br/><br/>
                    <button type="button" id="btnLeft" class="admin-btn admin-btn--outline" style="width:42px;height:42px;padding:0;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                </div>
                <div class="col-md-5">
                    <label>{{ __('All media assigned to playlist:') }}</label>
                    <select multiple="multiple" id='listBox2' class="form-control" name="media_ids[]" style="height:300px;">
                        @foreach($assigned_media as $key2 => $val2)
                            <option value="{{$key2}}">{{$val2}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="text-center mt-3">
                <button id="mediaPlaylistSave" type="button" onClick="saveToPlaylist()" class="admin-btn admin-btn--primary">{{ __('Save to Playlist') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('javascripts')
    @parent
    <script src="{{ asset('js/jquery.selectlistactions.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function saveToPlaylist(){
            $('#listBox2 option').prop('selected', true);
            $("#mediaPlaylistForm").submit();
        }

        $(document).ready(function() {
            $('#btnRight').click(function (e) {
                var selectedOpts = $('#listBox1 option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }
                $('#listBox2').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });

            $('#btnLeft').click(function (e) {
                var selectedOpts = $('#listBox2 option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }
                $('#listBox1').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });
        });
    </script>
@stop
