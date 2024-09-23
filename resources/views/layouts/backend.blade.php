<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('devstarit.app_name') }} - {{ config('devstarit.app_desc') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    {{-- <link href="{{ asset('backend/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('backend/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/@chenfengyuan/datepicker/datepicker.min.css') }}">

    <!-- DataTables -->
    <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">

    <!-- Styles -->
    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    @yield('styles')

</head>
<body>

    <div id="layout-wrapper">

        @include('admin.includes.header')


        <!-- ========== Left Sidebar Start ========== -->
        <!-- sidebar -->
        @include('admin.includes.sidebar')

        <!-- Left Sidebar End -->

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <!-- content -->
                    @include('admin.includes.breadcrumb')
                    @include('admin.includes.flash')

                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- footer -->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/node-waves/waves.min.js')}}"></script>

<!-- JAVASCRIPT -->

{{-- <script src="{{ asset('backend/assets/libs/select2/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('backend/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/@chenfengyuan/datepicker/datepicker.min.js')}}"></script>

{{-- <script src="https://cdn.tailwindcss.com/"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script> --}}

<!-- Required datatable js -->
<script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

<!-- dashboard init -->
{{-- <script src="{{ asset('backend/assets/js/pages/dashboard.init.js')}}"></script>
<script src="{{ asset('backend/assets/js/pages/form-advanced.init.js')}}"></script> --}}

<!-- App js -->
<script src="{{ asset('backend/assets/js/app.js')}}"></script>

@yield('scripts')
</body>
</html>
