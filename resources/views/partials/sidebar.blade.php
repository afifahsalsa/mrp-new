<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('purple-free/src/assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">David Grey. H</span>
                    <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Dashboard' ? 'active' : '' }}">
            <a class="nav-link {{ $title === 'Dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Buffer' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buffer.index') }}" aria-controls="icons">
                <span class="menu-title">Buffer</span>
                <i class="mdi mdi-contacts menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Choose Month Buffer' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buffer.choose-month') }}" aria-controls="icons">
                <span class="menu-title">Buffer Choose</span>
                <i class="mdi mdi-contacts menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Stok' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('stok.index') }}" aria-controls="forms">
                <span class="menu-title">Stock on Hand</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Open Po' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('open-po.index') }}" aria-controls="charts">
                <span class="menu-title">Outstanding PO</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ $title === 'Production Planning' || $title === 'Order Original' || $title === 'Order In Unit' ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">MPP</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lock menu-icon"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ $title === 'Order Original' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('order-original.index') }}">Order Original Customer</a>
                    </li>
                    <li class="nav-item {{ $title === 'Production Planning' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('planning-production.index') }}">Production Planning</a>
                    </li>
                    <li class="nav-item {{ $title === 'Order In Unit' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('order-unit.index') }}">Order in Unit</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/tables/basic-table.html') }}">Basic
                            table</a>
                    </li>
                </ul>
            </div>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lock menu-icon"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/samples/blank-page.html') }}"> Blank
                            Page </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/samples/login.html') }}"> Login </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/samples/register.html') }}"> Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/samples/error-404.html') }}"> 404
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('purple-free/src/pages/samples/error-500.html') }}"> 500
                        </a>
                    </li>
                </ul>
            </div>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ asset('purple-free/src/docs/documentation.html') }}" target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
