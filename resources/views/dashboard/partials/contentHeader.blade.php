<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>PEU</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-size: 16px;">Padasa Enam Utama</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user fa-fw"></i>
                    <span class="hidden-xs">{{ Auth::user()->nama }}</span>
                    <i class="fa fa-caret-down dropdown-menu-user-icon"></i>
                </a>
                <ul class="dropdown-menu">
                    @if (Auth::user()->admin)
                        <li>
                            <a href="{{ url('admin') }}"><i class="fa fa-wrench fa-fw"></i> Admin Console</a>
                        </li>
                        
                    @endif
                    {{-- <li>
                        <a href="{{ url('/profile') }}"><i class="fa fa-user-circle fa-fw"></i> Profile</a>
                    </li> --}}
                    <li>
                        <a href="{{ url('ChangePassword') }}/{{Auth::user()->username}}/edit "><i class="fa fa-key fa-fw"></i> Change Password</a>
                    </li>
                    <li>
                        <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
            </ul>
        </div>
    </nav>
</header>