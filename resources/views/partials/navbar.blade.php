@if (!Auth::check() || Auth::user()->user_code === null)
    {{ route('login') }}
@endif
<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><img
                src="{{ asset('img/logo-mrp-new.png') }}" alt="logo" style="height: 100%; margin-top: 10px; margin-bottm: 10px;" /></a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img
                src="{{ asset('purple-free/src/assets/images/logo-mini.svg') }}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    @if (Auth::check() && Auth::user()->name)
                            <div class="nav-profile-img">
                                <img src="{{ asset('purple-free/src/assets/images/faces/face1.jpg') }}" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                            </div>
                        @else
                            <script>
                                window.location = "{{ route('login') }}";
                            </script>
                        @endif
                    {{-- <div class="nav-profile-text">
                        @if (Auth::check() && Auth::user()->user_code)
                            <div class="bg-primary rounded-circle font-weight-bold text-white"
                                style="margin-right:-1rem; font-size:1.7rem; text-align:center; width: 50px; height: 50px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                                {{ Auth::user()->user_code }}
                            </div>
                        @else
                            <script>
                                window.location = "{{ route('login') }}";
                            </script>
                        @endif
                    </div> --}}
                </a>
                <div class="dropdown-menu navbar-dropdown">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" type="submit"><i
                                class="mdi mdi-logout me-2 text-primary"></i>Logout</button>
                    </form>
                </div>
            </li>
            {{-- <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li> --}}
            {{-- <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-email-outline"></i>
                    <span class="count-symbol bg-warning"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                    aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">Messages</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('purple-free/src/assets/images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a
                                message</h6>
                            <p class="text-gray mb-0"> 1 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('purple-free/src/assets/images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a
                                message</h6>
                            <p class="text-gray mb-0"> 15 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('purple-free/src/assets/images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture
                                updated</h6>
                            <p class="text-gray mb-0"> 18 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">4 new messages</h6>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count-symbol bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="mdi mdi-calendar"></i>
                            </div>
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                            <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="mdi mdi-cog"></i>
                            </div>
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                            <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                                <i class="mdi mdi-link-variant"></i>
                            </div>
                        </div>
                        <div
                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                            <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                </div>
            </li>
            <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-power"></i>
                </a>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-format-line-spacing"></i>
                </a>
            </li> --}}
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
