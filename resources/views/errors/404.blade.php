<!DOCTYPE html>
<html>
<head>
	<title>404 - PT. Padasa Enam Utama</title>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/dashboard/dashboard-error.css') }}">
</head>
<body>
	<div class="page-container-responsive absoluteCenter">
		<div class="row">
			<div class="col-md-5 col-middle">
				<h1 class="text-jumbo text-ginormous hide-sm">Oops!</h1>
				<h2>We can't seem to find the page you're looking for.</h2>
				<h6>Error code: 404</h6>
                <div style="width: 100%; padding-top: 40px; padding-bottom: 15px; text-align: center;">
                    <a href="{{ url('/') }}" style="font-size: 22px;"><u>Back to main page</u></a>
                </div>
			</div>
			<div class="col-md-5 col-middle text-center">
				<img src="{{ url('/images/404.gif') }}" width="313" height="428" class="hide-sm" alt="Girl has dropped her ice cream.">
			</div>
		</div>
	</div>
</body>
</html>
