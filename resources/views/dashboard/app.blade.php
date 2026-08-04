<!DOCTYPE html>
<html>
<head>

@include('dashboard.partials.htmlheader')

@yield('header-content')

</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
	
		@include('dashboard.partials.contentheader')

		@include('dashboard.partials.sidebar')

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

        	@yield('main-content')

		</div>

    </div>

    @include('dashboard.partials.scripts')

	@yield('script-content')


<style>
    /* Sidebar Width */
    .main-sidebar,
    .left-side {
        width: 260px !important;
    }

    /* Content ikut bergeser */
    .content-wrapper,
    .main-footer {
        margin-left: 260px !important;
    }

    /* Wrap text submenu */
    .sidebar-menu .treeview-menu > li > a {
        white-space: normal !important;
        height: auto !important;
        line-height: 18px !important;
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

	.sidebar-menu .treeview-menu > li > a.sidebar-wrap-link {
        display: flex !important;
        align-items: flex-start;
        white-space: normal !important;
        height: auto !important;
        line-height: 18px !important;
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

    .sidebar-menu .treeview-menu > li > a.sidebar-wrap-link > i {
        flex: 0 0 16px;
        margin-top: 3px;
    }

    .sidebar-wrap-text {
        display: block;
        flex: 1;
        word-break: normal;
        overflow-wrap: break-word;
    }
</style>

<script>
$(function () {

    $('.sidebar-menu > li.treeview').each(function () {

        var $parent = $(this);

        // Remove unauthorized submenu items
        $parent.find('> ul.treeview-menu > li.disabled').remove();

        // Remove parent if no submenu remains
        if ($parent.find('> ul.treeview-menu > li').length === 0) {
            $parent.remove();
        }

    });

});
</script>

</body>
</html>