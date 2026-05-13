@extends('layouts.affiliate')
@section('page-title')
    {{ env('APP_TITLE').' Sophisticated Modern PWA App.'}}
@stop
@section('styles')
    @parent

    <style type="text/css">
    	li {
    		text-align: justify!important;
    	}

    	p {
    		text-align: justify!important;
    	}

			.g-recaptcha {
					margin-top: 10px;
					display: inline-block!important;
			}
    </style>
@stop
@section('content')
    <div class="wrapper">
		<div class="header-section">
			<div class="container">
				<div class="header_area">  
					<div class="header_content">
						<div class="row">
							<div class="col-md-8 header-content-bg-left">
								<div class="logo-area">
									<div class="logo">
										<p><img src="{{ asset('images/affiliate/logo.png') }}" alt="{{ env('APP_TITLE') }}" /></p>
									</div> <!-- end logo -->
								</div> <!-- end logo-area -->
								<div class="header-content-left">
									<h1>
										{{ env('APP_TITLE') }} Sophisticated Modern PWA App.
									</h1>
									<h3>
										Ion Positive and Negative Changes
									</h3>
									<p>
										Engage to discover transforming energy to alignment, channeling cell activity with magnet fields to wellness.
									</p>		
									<p>
										A broad approach to positive and negative electron fields, working to eradicate diseases. (toxins, endotoxins, radicals, dysfunctions, emotional crisis, embedded negative codes, negative memory and PH imbalance.)
									</p>
									<h3>
										A journey to health - Biomagnetism Therapy
                                 	</h3>
								</div> <!-- end header-content-left-->
							</div>
							<div class="col-md-4 hedear-content-bg">
								<div class="header-content-right">	
									<div class="header-content-right-area">
										<div class="social-logo-area">
											<div class="social-icon">
												<p>
													<span>Stay connected with us</span>
													<a href="">
														<i class="fa fa-facebook-square" aria-hidden="true"></i>
													</a>										  
													<a href="">
														<i class="fa fa-twitter-square" aria-hidden="true"></i>
													</a>									  
													<a href="">
														<i class="fa fa-youtube-square" aria-hidden="true"></i>
													</a>  
												</p>
											</div> <!-- end social-icon -->
								        </div> <!-- end social-logo-area -->
										<h1>aNew</h1>
										<h2>Payment</h2>
                    @if ($message = Session::get('success'))
                      <h4 class="text-center" style="margin-bottom: 25px; color: #76FF03;"><u>{!! $message !!}</u></h4>
                      <button onclick="location.href = '{{ route('app.root') }}'" class="btn btn-danger">Back to Main Page</button>
                    @endif
                    @if ($message = Session::get('error'))
                      <h4 class="text-center" style="margin-bottom: 25px; color: #FF3D00"><u>{!! $message !!}</u></h4>
                      <button onclick="location.reload()" class="btn btn-danger" style="background-color: #dc3545;">Try Again</button>
                    @endif
                    @if (empty(Session::get('success')) && empty(Session::get('success')))
                      <form method="POST" action="{{ route('affiliate.payment') }}">
                          @csrf
                          @if (count($errors) > 0)
                              <div class="alert alert-danger">
                                  <ul style="padding-left: 15px;">
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif
                          <input type="hidden" id="mode" name="mode" value="inquiry/demo">
                          <div class="form-group">
                            <label for="usr">Amount <sup>* <sup></label>
                            <input type="number" class="form-control" id="amount" name="amount" required="">
                          </div>
                          @if(env('GOOGLE_RECAPTCHA_SITE_KEY'))
                            <div class="form-group">
                              <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_SITE_KEY')}}"></div>
                            </div> 
                          @endif
                          <button type="submit" class="btn btn-danger">Submit</button>
                      </form>
                    @else
                      <?php Session::forget('success');?>
                      <?php Session::forget('error');?>
                    @endif
									</div> <!-- end header-content-right-area -->
								</div> <!-- end header-content-right -->
							</div>
						</div> <!-- end row -->
					</div> <!-- end header_content -->
				</div> <!-- end header_area -->
			</div> <!-- end container -->
		</div> <!-- end header-section -->
	</div> <!-- end wrapper -->
@endsection
@section('javascripts')
    @parent

    <script src='https://www.google.com/recaptcha/api.js'></script>
@stop
