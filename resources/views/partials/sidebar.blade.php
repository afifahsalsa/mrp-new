<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('purple-free/src/assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">David Grey. H</span>
                    <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'staff' || auth()->user()->role == 'superuser')
            <li class="nav-item {{ $title === 'Dashboard' ? 'active' : '' }}">
                <a class="nav-link {{ $title === 'Dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li
                class="nav-item {{ $title === 'Edit Buffer' || $title === 'Index Buffer' || $title === 'View Buffer' || $title === 'Visualization Buffer & Stock' || $title === 'Index Stok' || $title === 'Edit Stok' || $title === 'View Stok' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('buffer.index') }}" aria-controls="icons">
                    <span class="menu-title">Buffer | Stock on Hand</span>
                    <i class="mdi mdi-database menu-icon"></i>
                </a>
            </li>
            <li
                class="nav-item {{ $title === 'Index Open PO' || $title === 'Edit Open PO' || $title === 'Index Open PR' || $title === 'Edit Open PR' || $title === 'Index Incoming Manual' || $title === 'Index Incoming Non Manual' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('open-po.index') }}" aria-controls="charts">
                    <span class="menu-title">Outstanding PO | PR | IM</span>
                    <i class="mdi mdi-cube-send menu-icon"></i>
                </a>
            </li>
            {{-- <li class="nav-item {{ $title === 'Index Incoming Non Manual' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('incoming-non-manual.index') }}" aria-controls="charts">
                    <span class="menu-title">Incoming Non Manual</span>
                    <i class="mdi mdi-cube-outline menu-icon"></i>
                </a>
            </li> --}}
            <li
                class="nav-item {{ $title === 'Index Order Customer' || $title === 'Index Production Planning' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('order-customer.index') }}" aria-controls="charts">
                    <span class="menu-title">MPP</span>
                    <i class="mdi mdi-package menu-icon"></i>
                </a>
            </li>
            <li
                class="nav-item {{ $title === 'Index BOM' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('bom.index') }}" aria-controls="charts">
                    <span class="menu-title">BOM</span>
                    <i class="mdi mdi-math-compass menu-icon"></i>
                </a>
            </li>
            <li
                class="nav-item {{ $title === 'MOQ & MPQ' || $title === 'Index MRP' || $title === 'Kebutuhan Material' || $title === 'Kebutuhan Produksi' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mrp.moq-mpq') }}" aria-controls="charts">
                    <span class="menu-title">MRP</span>
                    <i class="mdi mdi-math-compass menu-icon"></i>
                </a>
            </li>
        @endif
        @if (auth()->user()->role == 'staff' || auth()->user()->role == 'superuser')
            <li class="nav-item {{ $title === 'Index Sales' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}" aria-controls="charts">
                    <span class="menu-title">Sales</span>
                    <i class="mdi mdi mdi-sale menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ $title === 'Index Price' || $title === 'Input Currency' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('price.index') }}" aria-controls="charts">
                    <span class="menu-title">Price</span>
                    <i class="mdi mdi-cash-multiple menu-icon"></i>
                </a>
            </li>
        @endif
        @if (auth()->user()->role == 'superuser')
            <li class="nav-item {{ $title === 'Index User' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}" aria-controls="charts">
                    <span class="menu-title">Users</span>
                    <i class="mdi mdi-account menu-icon"></i>
                </a>
            </li>
        @endif
    </ul>
</nav>
