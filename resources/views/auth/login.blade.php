<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    <link href="{{ asset('theme/frontend/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('theme/frontend/css/all.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('theme/frontend/css/custom.css') }}" rel="stylesheet" media="screen">

    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <img src="{{ asset('theme') }}/frontend/images/logo-portal.webp" alt="Logo Politeknik Caltex Riau"
                    class="wow fadeInOut img-fluid mb-4 text-center d-flex mx-auto" data-wow-delay="0.7s"
                    style="height: 50px">

                <div class="card shadow border-0 mx-auto px-5 py-4">
                    <div class="card-body">
                        <a href="{{ route('login.google', ['provider' => 'google']) }}"
                            class="btn-default w-100 d-flex align-items-center justify-content-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                <path fill="#EA4335"
                                    d="M24 9.5c3.54 0 6 1.54 7.38 2.84l5.4-5.26C33.46 3.67 28.97 2 24 2 14.82 2 6.98 7.99 3.69 16.17l6.65 5.16C12.26 14.14 17.62 9.5 24 9.5z" />
                                <path fill="#4285F4"
                                    d="M46.5 24.5c0-1.64-.15-3.2-.43-4.71H24v9.02h12.7c-.55 2.83-2.23 5.22-4.74 6.82l7.66 5.93C43.9 37.33 46.5 31.36 46.5 24.5z" />
                                <path fill="#FBBC05"
                                    d="M10.34 28.31a14.5 14.5 0 0 1-.76-4.56c0-1.58.28-3.11.76-4.56l-6.65-5.16A23.86 23.86 0 0 0 2 23.75c0 3.86.92 7.51 2.55 10.72l6.66-5.16z" />
                                <path fill="#34A853"
                                    d="M24 47c6.48 0 11.9-2.13 15.87-5.81l-7.66-5.93C30.52 36.57 27.6 37.5 24 37.5c-6.38 0-11.74-4.64-13.66-10.97l-6.65 5.16C6.98 40.01 14.82 47 24 47z" />
                            </svg>
                            <span class="fw-semibold">Login dengan Google</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
