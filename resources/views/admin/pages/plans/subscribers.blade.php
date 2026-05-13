@extends('layouts.admin')
@section('page-title')
    {{ __('Anew Avenue Biomagnestim | Administrator - Plan Subscribers') }}
@stop
@section('styles')
    @parent
@stop
@section('content')
    @csrf
    <div id="content-container">
        <h2>
            {{ __('Subscribers') }} 
        </h2>
        <h6>Plan ({{$plan->name}} - ${{number_format($plan->price, 2)}})</h6><br/>
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="plan_subscribers" >
                <thead>
                    <tr>
                        <th class="align-left">{{ __('ID') }}</th>
                        <th class="align-left">{{ __('Name') }}</th>
                        <th class="align-left">{{ __('Email') }}</th>
                        <th class="align-left">{{ __('Type') }}</th>
                        <th class="align-left">{{ __('Business') }}</th>
                        <th class="align-left">{{ __('Location') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script type="text/javascript">     
        $(document).ready(function() {   
            $('#plan_subscribers').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url : '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/plans/{{$plan->id}}/subscribers/datatables' },
                columns: [
                    { data: 'id', name: 'id', className: 'dt-body-center' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'type', name: 'type', orderable: false, searchable: false },
                    { data: 'business', name: 'business' },
                    { data: 'location', name: 'location' }
                ]
            });
        });
    </script>
@stop
