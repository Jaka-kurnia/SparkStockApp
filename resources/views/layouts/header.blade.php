<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <!-- Left Side: Title -->
            <div class="col">
                <div class="page-pretitle">
                    SparkStock!
                </div>
                <h2 class="page-title text-dark">
                    @yield('page_title')
                </h2>
            </div>
            
            <!-- Right Side: User Status & Switcher -->
            @auth
            <div class="col-auto ms-auto d-flex align-items-center gap-3">
                <!-- User Info Badge -->
                <div class="d-none d-md-flex flex-column text-end">
                    <span class="font-weight-bold text-dark">{{ Auth::user()->name }}</span>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" title="Logout" data-bs-toggle="tooltip">
                        <i class="ti ti-logout" style="font-size: 22px; stroke-width: 1.5;"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>
