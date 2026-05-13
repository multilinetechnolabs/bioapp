@php
header('location: /home');
exit;
@endphp
@extends('layouts.affiliate')
@section('page-title')
    {{ env('APP_TITLE').' Sophisticated Modern PWA App.'}}
@stop
@section('styles')
    @parent

    <style type="text/css">
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
										<p><img src="images/affiliate/logo.png" alt="{{ env('APP_TITLE') }}" /></p>
									</div> <!-- end logo -->
								</div> <!-- end logo-area -->
								<div class="header-content-left">
									<h1 class="text-xs-center">
										{{ env('APP_TITLE') }} ssssss Sophisticated Modern PWA App.
									</h1>
									<h3 class="text-xs-center">
										Ion Positive and Negative Changes
									</h3>
									<p>
										Engage to discover transforming energy to alignment, channeling cell activity with magnet fields to wellness.
									</p>		
									<p>
										A broad approach to positive and negative electron fields, working to eradicate diseases. (toxins, endotoxins, radicals, dysfunctions, emotional crisis, embedded negative codes, negative memory and PH imbalance.)
									</p>
									<h3 class="text-xs-center">
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
										<h2>Request a Demo</h2>
										<form method="POST" action="{{ route('affiliate.inquire') }}">
												@csrf
												@if ($message = Session::get('success'))
													<div class="alert alert-success alert-block">	
														<strong>{{ $message }}</strong>
													</div>
												@endif
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
													<label for="usr">Full Name <sup>* <sup></label>
													<input type="text" class="form-control" id="name" name="name" required="">
												</div>
												<div class="form-group">
													<label for="pwd">Email<sup>* <sup></label>
													<input type="email" class="form-control" id="email" name="email" required="">
												</div>    
												<div class="form-group">
													<label for="pwd">Phone Number</label>
													<input type="text" class="form-control" id="phone_no" name="phone_no">
												</div>
												@if(env('GOOGLE_RECAPTCHA_SITE_KEY'))
													<div class="form-group text-center">
														<div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_SITE_KEY')}}"></div>
													</div> 
												@endif
												<button type="submit" class="btn btn-danger">Submit</button>
										</form>
									</div> <!-- end header-content-right-area -->
								</div> <!-- end header-content-right -->
							</div>
						</div> <!-- end row -->
					</div> <!-- end header_content -->
				</div> <!-- end header_area -->
			</div> <!-- end container -->
		</div> <!-- end header-section -->

			 <div class="content-description-section">
				<div class="container">
				   	<p class="title text-center">Biomagnetic Therapy</p>
					<p class="title-border"></p>
				<div class="content-box" style="margin-top:20px">
				    <div class="row content-box-border">
						<div class="col-md-3">
						   	<div class="content-box-img">
								<div class="content-box-img-sub">
									<img src="images/affiliate/body_scan.png" alt="{{ env('APP_TITLE') }}" />
									<p>
										Biomagnetism<br/>Body Scan
									</p>
								</div>	
							</div> <!-- end content-box-img -->
						</div>
						<div class="col-md-9">
						   	<div class="content-box-text">
								<p class="text-xs-center">Biomagnetism Body Scan</p>
								<ul>
									<li> - 832 female pairs and 809 male pairs. </li>
									<li> - Male and Female 3D models. </li>
								    <li> - Guided body scan - simplified path to pairs. </li>
								    <li> - Search data & points on the body. </li>
								    <li> - Direct pairs to the patient database. </li>
								    <li> - Free Relaxing Music. </li>
								</ul>
						    </div> <!-- end content-box-text -->	
						</div>  
					</div>
				</div> <!-- end content-box -->
									 
				<div class="content-box">
				    <div class="row content-box-border">
					 	<div class="col-md-9">
							<div class="content-box-text">
								<p class="text-xs-center">Chakra Ways Body Scan</p>
							     <ul>
								    <li> - 291 pairs. </li>
								    <li> - Male and Female 3D models. </li>
								    <li> - Guided body scan - simplified path to pairs. </li>
								    <li> - Search data & points on the body. </li>
								    <li> - Direct pairs to the patient database. </li>
								    <li> - Free Relaxing Music. </li>
								  </ul>
							</div> <!-- end content-box-text -->
						</div> 
						<div class="col-md-3">
						   	<div class="content-box-img">
								<div class="content-box-img-sub">
								    <img src="images/affiliate/ckarka.png" alt="{{ env('APP_TITLE') }}" />
									<p>
										Chakra Ways<br/>Body Scan
									</p>
								</div>	
							</div> <!-- end content-box-img -->
						</div>
					</div>
				</div> <!-- end content-box -->	

				<div class="content-box">
				    <div class="row content-box-border">
						<div class="col-md-3">
						   	<div class="content-box-img">
								<div class="content-box-img-sub">
									<img src="images/affiliate/data_log.png" alt="{{ env('APP_TITLE') }}" />
									<p>
									  Data Cache
									</p>
								</div>	
							</div> <!-- end content-box-img -->
						</div>
						  <div class="col-md-9">
						   	<div class="content-box-text">
								<p class="text-xs-center">Data Cache</p>
								<ul>
									<li> - Packed full of pairs (832 Bio or 291 Chakra), radicals, origin, detailed descriptions of causes and effects. Plus, alternative routes and complementary pairs relation. </li>
									<li> - Interaction based to work with each of the body scan models. </li>
									<li> - Resources enabling you to research by case studies, past sessions, individual client’s log, patterns, symptoms, list by groups, client’s with familiarities. </li>
									<li> - Scan sessions by dates. </li>
									<li> - Data cache for clients medical notes. </li>
									<li> - Cache to upload clients consent form. </li>
								</ul>
						    </div> <!-- end content-box-text -->	
						</div>  
					</div>
				</div> <!-- end content-box -->	 

				<div class="content-box">
				    <div class="row content-box-border">
					 	<div class="col-md-9">
							<div class="content-box-text">
								<p class="text-xs-center">Bio Connect</p>
								<ul>
									<li> - Social Media features. </li>
									<li> - Group discussions, add friends, find friends,   and notifications all together   in one place. </li>
									<li> -  Activities and Chat functionalities for users. </li>
								</ul>
						   	</div> <!-- end content-box-text -->
						</div> 
						<div class="col-md-3">
						    <div class="content-box-img">
								<div class="content-box-img-sub">
									<img src="images/affiliate/bio_connect.png" alt="{{ env('APP_TITLE') }}" />
									<p >Bio Connect</p>
								</div>	
							</div> <!-- end content-box-img -->
						</div>
					</div>
				</div> <!-- end content-box -->
			</div> <!-- end container -->
		</div> <!-- end content-description-section -->
			 
			 
		<div class="preferences-section">
			<div class="container">
			    <div class="row">
				    <div class="col-md-12">
					  <p>
						  <!-- <span>Preferences:</span>
						  A place to set up ones detailed shop information, with logo header for invoicing and print/email finished scans. -->
						</p>
					</div>
				</div>
			</div>  <!-- end container -->
		</div>  <!-- end preferences-section -->
			 			 
		<div class="get-in-touch">
			 <div class="container">
			 	<h1>GET IN TOUCH</h1>
			    <div class="row">
			        <div class="col-md-12">
						  <form method="POST" action="{{ route('affiliate.inquire') }}">
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
								<input type="hidden" id="mode" name="mode" value="get-in-touch">
								<div class="form-inline-cus">
									<input type="text"  placeholder="Name" id="name" name="name" required="">
									<input type="email" placeholder="Email" id="email" name="email" required="">
								</div>
								@if(env('GOOGLE_RECAPTCHA_SITE_KEY'))
									<div class="text-center">
											<div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_SITE_KEY')}}"></div>
									</div> 
								@endif
								<p class="text-center"><button type="submit" class="btn btn-danger">Submit</button></p>
						</form>
					</div>
				</div>
			</div> <!-- end container -->
		</div> <!-- end get-in-touch -->
			 
		<div class="footer-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p class="text-center">
							<img src="images/affiliate/so.png" alt="{{ env('APP_TITLE') }}" /> <br/>
								All rights reserved Copyright © 2019
							<span>aNew</span>
						</p>
					</div>
	 			</div>
	 		</div> <!-- end container -->
 		</div> <!-- end footer-section --> 

	</div> <!-- end wrapper -->
@endsection
@section('javascripts')
    @parent

    <script src='https://www.google.com/recaptcha/api.js'></script>
@stop
