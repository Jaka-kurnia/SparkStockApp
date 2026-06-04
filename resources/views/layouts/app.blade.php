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
        @include('layouts.sidebar')

        <div class="page-wrapper">
            @include('layouts.header')
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.script')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Menangkap Session Success (Misal: Berhasil Login, Berhasil Logout, Ubah Role)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Menangkap Session Error Umum / Kredensial Salah
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif

            // Menangkap Error Validasi Laravel ($errors)
            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif
        });
    </script>
</body>

</html>
