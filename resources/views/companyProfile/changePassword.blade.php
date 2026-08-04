@extends('dashboard.app')

@section('header-title')
    Edit User
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
			Edit User Password
			<small></small>
		</h1>
	</section>

	<section class="content">

		<div class="box box-primary margin-top-30">
            <form role="form" method="POST" action="{{ url('/ChangePassword') }}/{{ $user->username }}/change-password">
            	{{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
            	<div class="box-header with-border">
            		<h3 class="box-title">Change Password</h3>
            	</div>
				<div class="box-body">
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
				</div>
            	<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url('/dashboard/home') }}'"><i class="fa fa-list"></i> Cancel</button>
            	</div>
			</form>
		</div>
	</section>
@endsection

@section('script-content')
	<script type="text/javascript">
		setValidationConfirmationPassword('password', 'konfirmasi');
	</script>
@endsection
