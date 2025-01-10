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
    {{-- <link rel="stylesheet" href="{{ asset('purple-free/src/assets/css/dataTables.dataTables.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('datatables/dataTables.dataTables.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('datatables/dataTables.dateTime.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatables/searchBuilder.dataTables.css') }}"> --}}
</head>

<body>
    {{-- <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
          <span class='sr-only'>Loading...</span>
        </div>
      </div> --}}
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        @include('partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <!-- partial -->
            @include('partials.sidebar')
            <div class="main-panel">
                @yield('content')
                <div id="loading-overlay" class="loading-overlay">
                    {{-- <div class="spinner"></div> --}}
                    <span class="loader"></span>
                </div>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('purple-free/src/assets/js/sweetalert2@11.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="{{ asset('purple-free/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    {{-- <script src="{{ asset('purple-free/src/assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/datatables.min.js') }}"></script> --}}
    <script src="{{ asset('datatables/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.js') }}"></script>
    {{-- <script src="{{ asset('datatables/searchBuilder.dataTables.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.searchBuilder.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.dateTime.min.js') }}"></script> --}}
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
    @stack('scriptPr')
    @stack('scriptIM');
    @stack('scriptPP')
    @stack('scriptOrderOriginal')
    @stack('scriptMpp');
    @stack('scriptPrice');
    @stack('scriptINM');
    @stack('scriptSales');
    <!-- End custom js for this page -->
    <script>
        @if (session('swal'))
            Swal.fire({
                icon: '{{ session('swal.type') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.text') }}',
                html: '{!! session('swal.html') !!}'
            });
        @endif

        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }

        document.querySelector('form').addEventListener('submit', function() {
            showLoading();
        });

        window.addEventListener('load', function() {
            hideLoading();
        });
    </script>
</body>

</html>
