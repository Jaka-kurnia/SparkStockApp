<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login | SparkStock</title>
    <link href="{{ asset('dist/css/tabler.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css?1692870487') }}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class="d-flex flex-column">
    <script src="{{ asset('dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">

                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Login to your account</h2>

                                <form id="loginForm" action="{{ route('login.post') }}" method="POST"
                                    autocomplete="off" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="your@sparkstock.com" value="{{ old('email') }}" required
                                            autocomplete="off">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Your password" required autocomplete="off">
                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary" title="Show password"
                                                    data-bs-toggle="tooltip">
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-check">
                                            <input type="checkbox" name="remember" class="form-check-input" />
                                            <span class="form-check-label">Remember me on this device</span>
                                        </label>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">Login</button>
                                    </div>
                                </form>
                            </div>
                            <div class="hr-text">or</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="{{ asset('dist/logo/login.svg') }}" height="600" width="auto" class="d-block mx-auto"
                        alt="">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('dist/js/tabler.min.js?1692870487') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js?1692870487') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Validasi Form Login Sebelum Dikirim ke Server
            
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();

                let errorMessage = '';

                if (email === '' && password === '') {
                    errorMessage = 'Email dan Password wajib diisi!';
                } else if (email === '') {
                    errorMessage = 'Email wajib diisi!';
                } else if (password === '') {
                    errorMessage = 'Password wajib diisi!';
                }

                if (errorMessage !== '') {
                    e.preventDefault(); 

                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Belum Lengkap!',
                        text: errorMessage,
                        confirmButtonColor: '#3085d6'
                    });
                }
            });

        // Error handling dari server 
            @if ($errors->any())
                let serverError = "{{ $errors->first('email') }}";
                let displayMessage = "Email atau Password salah!";

                if (serverError.includes('required')) {
                    displayMessage = "Email dan Password wajib diisi!";
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: displayMessage,
                    confirmButtonColor: '#d33'
                });
            @endif

        //    Pesan Logout
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</body>

</html>
