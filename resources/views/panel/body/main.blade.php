<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>JugueteriaTripleJJJ</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/logoo.png') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.0') }}">
        
        <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
        
        @yield('specificpagestyles')
    </head>
<body>
    
    <!-- loader Start -->
    {{-- <div id="loading">
        <div id="loading-center"></div>
    </div> --}}
    <!-- loader END -->

    <!-- Wrapper Start -->
    <div class="wrapper">
        @include('panel.body.sidebar')

        @include('panel.body.navbar')
        
        <div class="content-page">
            @yield('container')
        </div>
    </div>
    <!-- Wrapper End-->
    

    @include('panel.body.footer')

    <!-- Backend Bundle JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>
    <script src="https://kit.fontawesome.com/4c897dc313.js" crossorigin="anonymous"></script>

    @yield('specificpagescripts')
    <!-- App JavaScript -->
    <script src="{{ asset('assets/js/app.js') }}"></script>



    <!-- LINKS TOASTIFY -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- Meta para mensajes -->
    @if(session('success'))
    <meta name="toast-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
    <meta name="toast-error" content="{{ session('error') }}">
    @endif
    <!-- Tu script personalizado -->
    <script src="{{ asset('js/toastify-mensajes.js') }}"></script>


</body>
</html>
