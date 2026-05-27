<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark my-1 my-lg-2">
            <a href=".">
                <img src="{{ asset('dist/logo/logo.png') }}" alt="SparkStock" class="navbar-brand-image"
                    style="width: auto; height: 52px;">
            </a>
        </h1>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav py-3 pt-lg-2">

                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }} mb-2">
                    <a class="nav-link py-2.5" href="./">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-layout-dashboard" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Dashboard
                        </span>
                    </a>
                </li>

                <li
                    class="nav-item dropdown {{ request()->routeIs(['supplier.*', 'sparepart.*']) ? 'active show' : '' }}">
                    <a class="nav-link dropdown-toggle py-2.5 {{ request()->routeIs(['supplier.*', 'sparepart.*']) ? 'show' : '' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="{{ request()->routeIs(['supplier.*', 'sparepart.*']) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-database" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Master Data
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs(['supplier.*', 'sparepart.*']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item py-2 {{ request()->routeIs('supplier.*') ? 'active' : '' }}"
                                    href="{{ route('supplier.index') }}">
                                    <i class="ti ti-users me-2" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Suppliers
                                </a>

                                <a class="dropdown-item py-2 {{ request()->routeIs('sparepart.*') ? 'active' : '' }}"
                                    href="{{ route('sparepart.index') }}">
                                    <i class="ti ti-stack-2" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Spareparts
                                </a>

                                <a class="dropdown-item py-2 {{ request()->routeIs('customer.*') ? 'active' : '' }}"
                                    href="{{ route('customer.index') }}">
                                    <i class="ti ti-users me-2" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Customers
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</aside>
