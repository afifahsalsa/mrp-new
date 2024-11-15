<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MRP-Register</title>
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple-free/src/assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('purple-free/src/assets/images/favicon.png') }}" />
</head>

<body>
    <div class="container-scroller">
        @yield('content')
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('purple-free/src/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/misc.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/settings.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('purple-free/src/assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->
</body>

</html>
