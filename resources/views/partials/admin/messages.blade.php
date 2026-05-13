@if(Session::has('message.fail'))
	<div class="alert alert-danger alert-dismissible" style="margin-top:50px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-info"></i> {{ __('Alert!') }}</h4>
		{{Session::get('message.fail')}}
	</div>
@endif
@if(Session::has('message.success'))
	<div class="alert alert-success alert-dismissible" style="margin-top:50px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-info"></i> {{ __('Success!') }}</h4>
		{{Session::get('message.success')}}
	</div>
@endif