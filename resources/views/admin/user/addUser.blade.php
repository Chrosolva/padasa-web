@extends('admin.app')

@section('header-title')
    Create User
@endsection

@section('header-content')
    <style type="text/css">
    	#tab_2 .box-body {
    		padding-top: 20px;
    	}

    	#tab_2 .box-body div {
    		margin-top: -2px;
    	}

    	.material-switch {
    		display: inline-block;
    	}
    </style>
@endsection

@section('main-content')
	<section class="content-header">
		<h1>
			Create User
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{ url('/admin/user') }}">User</a></li>
			<li class="active">Create</li>
		</ol>
	</section>

	<section class="content">
        <form role="form" method="POST" class="nav-tabs-custom" action="{{ url('/admin/user/create') }}">
        	{{ csrf_field() }}
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Profile Information</a></li>
				<li><a href="#tab_2" data-toggle="tab">Privileges</a></li>
			</ul>
			<div class="tab-content">
              	<div class="tab-pane active" id="tab_1">
					<div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
						<label for="nama">Nama Lengkap *</label>
						<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" maxlength="100" value="{{ old('nama') }}" required autofocus>
                        @if ($errors->has('nama'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('nama') }}</span>
                        @endif
					</div>
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						<label for="email">Email *</label>
						<input type="email" name="email" id="email" class="form-control" placeholder="Alamat Email" maxlength="100" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</span>
                        @endif
					</div>
					<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
						<label for="username">Username *</label>
						<input type="text" name="username" id="username" class="form-control" placeholder="Username" maxlength="50" value="{{ old('username') }}" required>
                        @if ($errors->has('username'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('username') }}</span>
                        @endif
					</div>
					<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
						<label for="password">Password *</label>
						<input type="password" name="password" id="password" class="form-control" placeholder="Password" minlength="6" maxlength="20" required>
                        @if ($errors->has('password'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}</span>
                        @endif
					</div>
					<div class="form-group {{ $errors->has('konfirmasi') ? 'has-error' : '' }}">
						<label for="konfirmasi">Konfirmasi Password *</label>
						<input type="password" id="konfirmasi" class="form-control" placeholder="Konfirmasi Password" minlength="6" maxlength="20" required>
                        @if ($errors->has('konfirmasi'))
                            <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('konfirmasi') }}</span>
                        @endif
					</div>
					<div class="form-group margin-top-25">
						<label class="margin-right-15">Admin</label>
						<div class="material-switch">
                            <input id="admin" name="admin" type="checkbox" {{ old('admin', false) ? 'checked' : '' }}/>
                            <label for="admin" class="label-success"></label>
                        </div>
					</div>
         		</div>
              	<div class="tab-pane" id="tab_2">
				  <?php
                        $total_hak_akses = count($hak_akses);
                    ?>
					@for ($i = 0; $i < $total_hak_akses;)
						<div class="box box-primary">
							<div class="box-header with-border">
								<h4 class="box-title">{{ $hak_akses[$i]->nama_modul }}</h4>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 checkbox checkbox-primary margin-top-0">
		                            <input type="checkbox" id="hak_akses_{{ $i }}" name="hak_akses[]" value="{{ $hak_akses[$i]->id }}">
		                            <label for="hak_akses_{{ $i }}">{{$hak_akses[$i]->hak_akses}}</label>
			                    </div>

								@while ($i + 1 < $total_hak_akses && strtolower($hak_akses[$i]->nama_modul) == strtolower($hak_akses[$i + 1]->nama_modul))
									<?php $i++; ?>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 checkbox checkbox-primary margin-top-0">
										<input type="checkbox" id="hak_akses_{{ $i }}" name="hak_akses[]" value="{{ $hak_akses[$i]->id }}">
		                            	<label for="hak_akses_{{ $i }}">{{$hak_akses[$i]->hak_akses}}</label>
				                    </div>
								@endwhile
							</div>
						</div>
						<?php $i++; ?>
					@endfor
              	</div>
            </div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
		        <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/admin/user') }}'"><i class="fa fa-list"></i> Cancel</button>
			</div>
		</form>
	</section>
@endsection

@section('script-content')
	<script type="text/javascript">
		setValidationConfirmationPassword('password', 'konfirmasi');
	</script>
@endsection
