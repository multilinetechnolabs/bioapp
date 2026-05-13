@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Biomagnetism Body Scan'}}
@stop
@section('content')
    @php ($target = request()->target ?? 'female')
    @php ($scan_type = request()->scan_type ?? 'body_scan')
    @php ($client = App\Models\Client::find(request()->id))
    @include('partials.header', ['title' => ($scan_type === 'body_scan' ? 'Body Scan' : 'Chakra Scan'), 'image_url' => '/images/iconimages/humanicon48.png'])
    <div class="container scan-container" ng-controller="ModelLabelsCtrl as ctrl">
        <div style="display: none;" id="clientId" data-value="{{$client->id}}"></div>
        <div style="display: none;" id="scanType" data-value="{{$scan_type}}"></div>
        <div style="display: none;" id="modelTarget" data-value="{{$client->gender ?? $target}}"></div>
        <div style="display: none;" id="userIsAdmin" data-value="{{Auth::user()->isAdmin() ? 'administrator' : ''}}"></div>
        <div class="row justify-content-center signup-form-row">
            <div class="row col-md-12" style="padding-left: 0;">
                <div class="col-md-6 col-xs-6" style="padding-left: 25px;">
                    <div id="loading"><h3 id="loading-text">Loading... Please wait... </h3></div>
                    <div id="modelcontainer" style="display: none;"></div>
                    <div id="toggleModel" class="scan-toggle-model" style="display: none; width: 100%;">
                        <button id="zoomIn" class="pull-left" ng-click="ctrl.zoomIn($event)" style="margin-right: 5px;">
                            Zoom In
                        </button>
                        <button id="zoomOut" class="pull-left" ng-click="ctrl.zoomOut($event)">
                            Zoom Out
                        </button>
                        <button id="nextPoint" class="pull-right" ng-click="ctrl.nextPoint($event)">
                            Next
                        </button>
                        <button id="prevPoint" class="pull-right" ng-click="ctrl.prevPoint($event)" style="margin-right: 5px;">
                            Previous
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 scan-point-container" ng-cloak="">
                    <div class="row scan-search-box">
                        <img src="{{asset('/images/scan-search-icon.png')}}" alt="{{ env('APP_TITLE') }}"></img>
                        <form>
                            <input type="text" placeholder="Search..." ng-model="ctrl.search.params.text" style="width: 92%"></input>
                            <select ng-model="ctrl.search.meta.perPage" style="width: 7%; height: 28px;">
                                <option ng-value="1">1</option>
                                <option ng-value="3">3</option>
                                <option ng-value="5">5</option>
                                <option ng-value="10">10</option>
                                <option ng-value="25">25</option>
                                <option ng-value="50">50</option>
                                <option ng-value="100">100</option>
                            </select>
                        </form>
                    </div>
                    <h5 ng-if="ctrl.model_labels.length > 0" style="margin: 8px 0;"><% ctrl.search.meta.pageStatus %></h5>
                    <a href="/data_cache/clients/{{$client->id}}" target="_blank" ng-if="ctrl.model_labels.length > 0"><button class="btn-data-cache" style="margin: 5px 15px 5px 0; cursor: pointer; position: absolute; right: 0;">View Client Information</button></a>
                    <div class="row second-layer" style="margin-top: 50px;">
                        <span style="margin-left: 50px;" ng-if="(ctrl.model_labels | filter: { point: { name: ctrl.search.params.text } }).length == 0">No results found.</span>
                        <ul id="accordion" ng-if="ctrl.model_labels.length > 0" style="width: 100%;">
                            <li ng-repeat="model_label in ctrl.model_labels | orderBy: 'point.name' | filter: { point: { name: ctrl.search.params.text } } | unique: 'point.name' | slice: ctrl.search.meta.startPos:ctrl.search.meta.startPos+ctrl.search.meta.perPage">
                                <button class="btn btn-link collapsed" ng-click="ctrl.showPoints($event, model_label.id)">
                                        <img src="{{asset('/images/scan-btn-add.png')}}" alt="{{ env('APP_TITLE') }}" ng-click="ctrl.addPairToClient(model_label.point)" ng-if="!model_label.point._delete"></img>
                                        <img src="{{asset('/images/scan-btn-minus.png')}}" alt="{{ env('APP_TITLE') }}" ng-click="ctrl.removePairToClient(model_label.point)" ng-if="model_label.point._delete"></img>
                                        <span data-toggle="collapse" data-target="#collapse-<% model_label.id %>" aria-expanded="true" aria-controls="collapse-<% model_label.id %>"><% model_label.point.name  || '-' %></span>
                                </button>
                                <div id="collapse-<% model_label.id %>" class="collapse" aria-labelledby="heading-<% model_label.id %>" data-parent="#accordion" style="font-size: 16px;">
                                    <table style="margin-left: 50px; margin-top: 10px; width: calc(100% - 50px);" border="1">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <td>Points/Name:</td>
                                                <td><% model_label.point.name || '-' %></td>
                                            </tr>
                                            <tr>
                                                <td>Radical:</td>
                                                <td><% model_label.point.radical || '-' %></td>
                                            </tr>
                                            <tr>
                                                <td>Start/Origin:</td>
                                                <td><% model_label.point.origins || '-' %></td>
                                            </tr>
                                            <tr>
                                                <td>Leads/Symptoms:</td>
                                                <td><% model_label.point.symptoms || '-' %></td>
                                            </tr>
                                            <tr>
                                                <td>Path/Route/Cause and Effect:</td>
                                                <td><% model_label.point.paths || '-' %></td>
                                            </tr>
                                            <tr>
                                                <td>Alternative Routes:</td>
                                                <td><% model_label.point.alternative_routes || '-' %></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        </ul>
                    </div>  
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
@stop
