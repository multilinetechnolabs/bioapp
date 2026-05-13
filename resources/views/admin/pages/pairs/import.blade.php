@extends('layouts.admin')
@section('page-title')Import Pairs@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Import Pairs') }}</h2>
        </div>
        <p class="text-muted" style="font-size:0.85rem;">{{ __('Only files of format .xls or .xlsx are accepted. Please note that first row should always be the column headers.') }}</p>
        <form id="import-form" action="{{ url('/admin/pairs/parse') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row form-group col-md-12">
                <div class="col-md-2">
                    <select class="form-control" id="scan_type" name="scan_type" data-scan_type="{{$request->scan_type}}" required>
                        <option value="body_scan">Bio</option>
                        <option value="chakra_scan">Chakra</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="file" name="pair_file" id="fileToUpload" class="form-control" accept=".xls,.xlsx" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="admin-btn admin-btn--primary">{{ __('Upload and Parse') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('javascripts')
    @parent
    <script>
        $(document).ready(function() {
            document.getElementById("scan_type").value = $("#scan_type").data('scan_type');
        })
    </script>
@stop
