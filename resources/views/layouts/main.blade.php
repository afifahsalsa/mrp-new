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
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('purple-free/src/assets/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/datatables.min.js') }}"></script>
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
    @stack('scriptPP')
    @stack('scriptOrderOriginal')
    <!-- End custom js for this page -->
    <script>
        function toggleArrow() {
            const button = document.getElementById("dropdownButton");
            const arrow = document.getElementById("dropdownArrow");

            button.addEventListener("click", function() {
                if (button.getAttribute("aria-expanded") === "true") {
                    arrow.innerHTML = "&#9662;"; // Downward arrow when open
                } else {
                    arrow.innerHTML = "&#9656;"; // Rightward arrow when closed
                }
            });
        }
    </script>
</body>

</html>
