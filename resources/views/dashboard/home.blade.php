@extends('dashboard.app')

@section('header-title')
    Home
@endsection

@section('main-content')
	<section class="content-header">
		@if (session()->has('message'))
		    <div class="alert alert-success alert-dismissable">
		        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		        {{ session('message') }}
		    </div>
		@endif

		@if (session()->has('error'))
		    <div class="alert alert-danger alert-dismissable">
		        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
		    </div>
		@endif
		<h1>
			Dashboard Highlight
			<small></small>
		</h1>
	</section>

	<section class="content">
	</section>
@endsection