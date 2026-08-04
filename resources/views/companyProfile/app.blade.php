<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
        <title>@yield('header-title') - PT. Padasa Enam Utama</title>


        {{-- <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet" type="text/css"> --}}

        <link rel="stylesheet" href="{{ url('assets/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/company-profile/layout/layout.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/company-profile/company-profile-custom.css') }}">



        @yield('header-content')
    </head>

    <body>
        <header class="header navbar-fixed-top">
            <nav class="navbar" role="navigation">
                <div class="container">
                    <div class="menu-container js_nav-item">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="toggle-icon"></span>
                        </button>

                        <div class="logo">
                            <a class="logo-wrap" href="{{ url('/') }}">
                                <img class="logo-img" src="{{ url('/images/logo-padasa-459x50.png') }}" alt="Padasa Logo">
                            </a>
                        </div>
                    </div>

                    <div class="collapse navbar-collapse nav-collapse">
                        <div class="menu-container">
                            <ul class="nav navbar-nav navbar-nav-right">
                                <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="{{ url('/home') }}">Home</a></li>
                                <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="http://mail.padasa.co.id">Mail</a></li>
                                @if (Auth::check() == false)
                                    <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="{{ url('/login') }}">Login</a></li>
                                @else
                                    <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="{{ url('/dashboard') }}">Dashboard</a></li>
                                    <li class="js_nav-item nav-item dropdown">
                                        <a class="nav-item-child nav-item-hover dropdown-toggle" data-toggle="dropdown" href="#">{{ Auth::user()->nama }} <span class="caret"></span></a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{ url('/logout') }}">Logout</a></li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="main-content">
            @yield('main-content')
        </div>

        <footer class="footer">
            <div class="content container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <p class="margin-b-0 footer-copyright">Copyright © <a href="{{ url('/') }}">PT. Padasa Enam Utama</a></p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Back To Top -->
        <a href="javascript:void(0);" class="js-back-to-top back-to-top">Top</a>

        <script src="{{ url('assets/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script>

        <script src="{{ url('assets/company-profile/back-to-top/jquery.back-to-top.js') }}"></script>
        <script src="{{ url('assets/company-profile/layout/layout.min.js') }}"></script>
        <script src="{{ url('assets/company-profile/company-profile-custom.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="{{ url('assets/OwlCarousel2-2.3.4/dist/owl.carousel.min.js') }}"></script>

        @yield('script-content')
        
    </body>
</html>