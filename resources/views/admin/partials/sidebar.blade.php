<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header" style="color: white;">ADMIN NAVIGATION</li>
            <li class="treeview">
                <a href="{{ url('/admin/home') }}"><i class="fa fa-home"></i> <span>Admin Home</span></a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>User</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/admin/user') }}"><i class="fa fa-circle-o"></i> User</a></li>
                    <li><a href="{{ url('/admin/hak-akses') }}"><i class="fa fa-circle-o"></i> Hak Akses</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-desktop"></i>
                    <span>Program</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/admin/kebun') }}"><i class="fa fa-circle-o"></i> Kebun</a></li>
                    <li><a href="{{ url('/admin/modul') }}"><i class="fa fa-circle-o"></i> Modul</a></li>
                    <li><a href="{{ url('/admin/modul-per-kebun') }}"><i class="fa fa-circle-o"></i> Modul Per Kebun</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>