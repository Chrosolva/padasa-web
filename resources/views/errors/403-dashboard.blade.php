@extends('dashboard.app')

@section('header-title')
    403
@endsection

@section('header-content')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/dashboard/dashboard-error.css') }}">
    <style type="text/css">
    	.content-wrapper {
    		background-color: white;
    	}
    </style>
@endsection

@section('main-content')
	<section class="content">
		<div class="page-container-responsive">
			<div class="row">
				<div class="col-md-5 col-middle">
					<h1 class="text-jumbo text-ginormous hide-sm">Sorry</h1>
					<h2>You are not authorized to access this page.</h2>
					<h6>Error code: 403</h6>
	                <div style="width: 100%; padding-top: 40px; padding-bottom: 15px; text-align: center;">
	                    <a href="{{ url('/') }}" style="font-size: 22px;"><u>Back to main page</u></a>
	                </div>
				</div>
				<div class="col-md-5 col-middle text-center">
					<img src="{{ url('/images/404.gif') }}" width="313" height="428" class="hide-sm" alt="Girl has dropped her ice cream.">
				</div>
			</div>
		</div>
	</section>
@endsection