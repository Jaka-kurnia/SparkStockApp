<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark my-1 my-lg-2">
            <a href="{{ url('/') }}">
                <img src="{{ asset('dist/logo/logo.png') }}" alt="SparkStock" class="navbar-brand-image"
                    style="width: auto; height: 52px;">
            </a>
        </h1>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav py-3 pt-lg-2">

                {{-- 1. Dashboard --}}
                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }} mb-2">
                    <a class="nav-link py-2.5" href="{{ url('/dashboard') }}">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-layout-dashboard" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Dashboard
                        </span>
                    </a>
                </li>

                {{-- 2. Master Data Dropdown --}}
                {{-- Ditambahkan 'customer.*' agar parent tetap terbuka saat menu customer aktif --}}
                @canany(['manage-suppliers', 'manage-spareparts', 'manage-customers', 'manage-vehicles'])
                <li class="nav-item dropdown {{ request()->routeIs(['supplier.*', 'sparepart.*', 'customer.*']) ? 'active show' : '' }} mb-2">
                    <a class="nav-link dropdown-toggle py-2.5 {{ request()->routeIs(['supplier.*', 'sparepart.*', 'customer.*']) ? 'show' : '' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="{{ request()->routeIs(['supplier.*', 'sparepart.*', 'customer.*']) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-database" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Master Data
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs(['supplier.*', 'sparepart.*', 'customer.*', 'mechanic.*','vehicle.*']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @can('manage-suppliers')
                                <a class="dropdown-item py-2 {{ request()->routeIs('supplier.*') ? 'active' : '' }}"
                                    href="{{ route('supplier.index') }}">
                                    <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Suppliers
                                </a>
                                @endcan

                                @can('manage-spareparts')
                                <a class="dropdown-item py-2 {{ request()->routeIs('sparepart.*') ? 'active' : '' }}"
                                    href="{{ route('sparepart.index') }}">
                                    <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Spareparts
                                </a>
                                @endcan

                                @can('manage-customers')
                                <a class="dropdown-item py-2 {{ request()->routeIs('customer.*') ? 'active' : '' }}"
                                    href="{{ route('customer.index') }}">
                                     <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Customers
                                </a>
                                @endcan

                                @can('manage-vehicles')
                                <a class="dropdown-item py-2 {{ request()->routeIs('vehicle.*') ? 'active' : '' }}"
                                    href="{{ route('vehicle.index') }}">
                                   <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Vehicles
                                </a>
                                @endcan

                                @can('manage-mechanic')
                                <a class="dropdown-item py-2 {{ request()->routeIs('mechanic.*') ? 'active' : '' }}"
                                    href="{{ route('mechanic.index') }}">
                                   <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Mechanic
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </li>
                @endcanany

                {{-- 3. Transaksi Dropdown --}}
                @can('manage-services')
                <li class="nav-item dropdown {{ request()->routeIs(['service.*']) ? 'active show' : '' }} mb-2">
                    <a class="nav-link dropdown-toggle py-2.5 {{ request()->routeIs(['service.*']) ? 'show' : '' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="{{ request()->routeIs(['service.*']) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-database" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Transaksi
                        </span>
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs(['service.*', 'stocktransaction.*']) ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item py-2 {{ request()->routeIs('service.*') ? 'active' : '' }}"
                                    href="{{ route('service.index') }}">
                                    <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Services
                                </a>
                                <a class="dropdown-item py-2 {{ request()->routeIs('stocktransaction.*') ? 'active' : '' }}"
                                    href="{{ route('stocktransaction.index') }}">
                                    <i class="ti ti-point" style="font-size: 1.1rem; line-height: 1;"></i>
                                    Stock Transaction
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endcan

                {{-- 4. Role Permissions (Owner Only) --}}
                @role('owner')
                <li class="nav-item {{ request()->routeIs('role-permissions.*') ? 'active' : '' }} mb-2">
                    <a class="nav-link py-2.5" href="{{ route('role-permissions.index') }}">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                           <i class="ti ti-adjustments-horizontal" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Role Permissions
                        </span>
                    </a>
                </li>
                @endrole

            </ul>
        </div>
    </div>
</aside>