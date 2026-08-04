<!DOCTYPE html>
<html>
<head>

@include('admin.partials.htmlheader')

@yield('header-content')

</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
	
		@include('admin.partials.contentheader')

		@include('admin.partials.sidebar')

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

        	@yield('main-content')

		</div>

    </div>

    @include('admin.partials.scripts')

	@yield('script-content')

</body>
</html>