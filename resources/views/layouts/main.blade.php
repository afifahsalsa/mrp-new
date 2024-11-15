<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MRP-PPIC</title>
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('purple-free/src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('purple-free/src/assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/css/dataTables.dataTables.css') }}">
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        @include('partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('partials.sidebar')
            <!-- partial -->
            <div class="main-panel">
                @yield('content')
                {{-- <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023 <a
                                href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights
                            reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                            with <i class="mdi mdi-heart text-danger"></i></span>
                    </div>
                </footer> --}}
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('purple-free/src/assets/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/dataTables.js') }}"></script>
    {{-- <script src="{{ asset('purple-free/src/assets/js/datatables.min.js') }}"></script> --}}
    <script src="{{ asset('purple-free/src/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/misc.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/settings.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/dashboard.js') }}"></script>
    @stack('scriptBuffer')
    @stack('scriptStok')
    @stack('scriptDashboard')
    @stack('scriptPo')
    <!-- End custom js for this page -->
</body>

</html>
