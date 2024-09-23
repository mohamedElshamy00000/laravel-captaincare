<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'captaincare') }}</title>

        <!-- owl.carousel css -->
        {{-- <link rel="stylesheet" href="{{ asset('backend/assets/libs/owl.carousel/assets/owl.carousel.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ asset('backend/assets/libs/owl.carousel/assets/owl.theme.default.min.css') }}"> --}}

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>

    <body class="auth-body-bg">
        
        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">
                    
                    <div class="col-xl-4 m-auto">
                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-2 m-auto">
                                        <a href="{{ url('/') }}" class="d-block auth-logo">
                                            <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="" height="40" class="auth-logo-dark">
                                            <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="" height="40" class="auth-logo-light">
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        
                                        @yield('content')
                                        
                                    </div>

                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© 2020 Captaincare Team</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- owl.carousel js -->
        {{-- <script src="{{ asset('backend/assets/libs/owl.carousel/owl.carousel.min.js') }}"></script> --}}

        <!-- auth-2-carousel init -->
        <script src="{{ asset('backend/assets/js/pages/auth-2-carousel.init.js') }}"></script>
        
        <!-- App js -->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    </body>
</html>
