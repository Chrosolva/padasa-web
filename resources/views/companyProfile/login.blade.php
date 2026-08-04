@extends('companyProfile.app')

@section('header-title')
    Login
@endsection

@section('header-content')
    <style type="text/css">
    	.checkbox {
    		margin-top: 0px;
    	}
        .color-forget-password-success {
            color: #73b369;
        }
        .forget-password-catatan {
            font-size: 11px;
            color: #898989;
        }
    </style>
@endsection

@section('main-content')
	<div class="default-color-container">
	    <div class="container">
			<div class="login-box">
		        <div class="login-box-body">
		            <p class="login-box-msg">Login to start your session</p>

		            @if (session()->has('error'))
		                <div class="alert alert-danger alert-dismissable">
		                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
		                </div>
		            @endif

		            <form action="{{ url('/login') }}" method="post">
		                {{ csrf_field() }}
		                <div class="form-group has-feedback">
		                    <input name="username" type="text" class="form-control" placeholder="Username" maxlength="50" value="{{ old('username') }}" required autofocus>
		                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
		                </div>
		                <div class="form-group has-feedback">
		                    <input name="password" type="password" class="form-control" placeholder="Password" minlength="6" maxlength="20" required>
		                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		                </div>
		                <div class="row">
		                    <div class="col-xs-6">
		                        <div class="checkbox checkbox-primary margin-top-0">
		                            <input type="checkbox" id="remember_me" name="remember_me">
		                            <label for="remember_me">Remember Me</label>
		                        </div>
		                    </div>
		                    <div class="col-xs-6 text-right">
		                        <a class="default-link" href="#" data-toggle="modal" data-target="#forget-password-modal">Forget Password?</a>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-btn fa-sign-in"></i>&nbsp;&nbsp;&nbsp;Login</button>
		                </div>
		            </form>
		        </div>
		    </div>
	    </div>
   </div>

    <div class="modal fade" id="forget-password-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center">Reset Password</h4>
                </div>
                <form method="POST" id="form-forget-password">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>A password reset link will be sent to the following email address</p>
                        <input name="email_reset" id="email_reset" type="email" class="form-control" placeholder="Email" maxlength="50" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="sendResetPassword()">Send Reset Password Link</button>
                        <button type="submit" class="btn btn-warning hidden" id="submit_reset">Submit</button>
                    </div>
                </form>
                <div id="forget-password-success" class="hidden">
                    <div class="modal-body padding-20">
                        <div class="text-center">
                            <i class="fa fa-check-circle fa-5x color-forget-password-success"></i>
                            <div class="margin-top-10">A reset link has been sent to <b id="send-email-reset"></b></div>
                        </div>
                        <div class="margin-top-20 forget-password-catatan"Z>
                            <div>Catatan :</div>
                            <div>1. Mungkin membutuhkan waktu beberapa menit sampai email tersebut masuk ke inbox anda.</div>
                            <div>2. Jangan lupa mengecek folder spam anda jika anda tidak melihat email tersebut di inbox anda.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script>
        $('#forget-password-modal').on('show.bs.modal', function(e) {
            $('#forget-password-success').addClass('hidden');
            $('#form-forget-password').removeClass('hidden');
            $('#email_reset').val('');
            setTimeout(function (){
                $('#email_reset').focus();
            }, 500);
        });

        $('#form-forget-password').submit(function (e) {
            e.preventDefault();
        });

        function sendResetPassword() {
            if ($('#email_reset')[0].checkValidity()) {
                var email = $('#email_reset').val();

                $.ajax({
                    type: "POST",
                    url: "{{ url('/forget-password') }}",
                    datatype: "text",
                    data: $("#form-reset-password").serialize(),
                    success: function() {
                        $('#send-email-reset').html(email);
                        $('#form-forget-password').addClass('hidden');
                        $('#forget-password-success').removeClass('hidden');
                    },
                    error: function (request, status, error) {
                        alert("Network error. Please try again later.");
                    }
                });
            }
            else {
                $('#submit_reset').click();
            }
        };
    </script>
@endsection
