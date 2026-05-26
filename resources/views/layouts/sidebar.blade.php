<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <h1 class="navbar-brand navbar-brand-autodark my-2 my-lg-3">
            <a href=".">
                <img src="{{ asset('static/logo.png') }}" alt="Tabler" class="navbar-brand-image" style="height: 2rem; width: auto;">
            </a>
        </h1>
        
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav py-3 pt-lg-2">
                
                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link py-2.5" href="./">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-layout-dashboard" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Dashboard
                        </span>
                    </a>
                </li>
                
                <li class="nav-item dropdown {{ request()->is(['supplier'] ) ? 'active open' : ' ' }}">
                    <a  class="nav-link dropdown-toggle py-2.5" data-bs-toggle="dropdown">
                        <span class="nav-link-icon d-flex align-items-center justify-content-center">
                            <i class="ti ti-database" style="font-size: 1.25rem; line-height: 1;"></i>
                        </span>
                        <span class="nav-link-title ms-2">
                            Master Data
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item py-2 {{ request()->routeIs('supplier.index') ? 'active' : '' }}" href="{{ route('supplier.index') }}">
                                    Supplier
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</aside>