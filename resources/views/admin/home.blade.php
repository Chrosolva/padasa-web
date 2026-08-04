@extends('admin.app')

@section('header-title')
    Admin Console
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Admin Console
			<small>Control panel</small>
		</h1>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">User</span>
						<span class="info-box-number">{{ $user_count }}</span>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-yellow"><i class="ion ion-android-laptop"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Modul</span>
						<span class="info-box-number">{{ $modul_count }}</span>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
