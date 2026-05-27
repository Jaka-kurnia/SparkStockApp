<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title') | SparkStock</title>
    @include('layouts.style')
</head>

<body>
    <div class="page">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div class="page-wrapper">
            <!-- Page header -->
            @include('layouts.header')
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <!-- Libs JS -->
    @include('layouts.script')
</body>

</html>

