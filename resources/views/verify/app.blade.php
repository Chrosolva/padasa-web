<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <title>@yield('header-title') - PT. Padasa Enam Utama</title>

    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('assets/verify/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/verify/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ url('assets/verify/css/templatemo-space-dynamic.css') }}">
    <link rel="stylesheet" href="{{ url('assets/verify/css/animated.css') }}">
    <link rel="stylesheet" href="{{ url('assets/verify/css/owl.css') }}">

    @yield('header-content')

</head>

<body style="background: url({{ url('assets/verify/images/pattern_15.png') }}) repeat;">

    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s"
        style="background: none;">
        <div class="container">
            @yield('main-header')
            @yield('main-content')
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.25s">
                    <p>&copy; Copyright 2023 Padasa Enam Utama. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ url('assets/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ url('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/verify/js/owl-carousel.js') }}"></script>
    <script src="{{ url('assets/verify/js/animation.js') }}"></script>
    <script src="{{ url('assets/verify/js/templatemo-custom.js') }}"></script>

    @yield('script-content')

</body>

</html>
