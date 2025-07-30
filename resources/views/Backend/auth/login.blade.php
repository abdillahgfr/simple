@extends('Backend.Layouts.app')

@section('content')
    <style>
        body {
            background-color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
        }

        /* Remove flex: 1 from .login-left and .login-right */
        .login-left,
        .login-right {
            /* flex: 1; */
        }

        .login-left {
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-left img {
            height: auto;
        }

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            /* padding: 3rem; */
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            font-weight: bold;
            margin-bottom: 1rem;
            color: #222;
        }

        .login-box .form-group label {
            font-size: 14px;
            font-weight: 500;
        }

        .login-box .form-control {
            border-radius: 30px;
            padding: 10px 20px;
        }

        .login-box .btn {
            border-radius: 30px;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .bottom-links {
            margin-top: 2rem;
            text-align: center;
            font-size: 13px;
        }

        .bottom-links a {
            margin: 0 10px;
            color: #007bff;
        }
    </style>

    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center login-wrapper">
            <div class="col-12 col-md-6 login-left">
                <img src="{{ asset('assets/img/image-front.png') }}" alt="Illustration">
            </div>

            <div class="col-12 col-md-6 login-right">
                <div class="login-box">
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/logo-header.png') }}" width="300" class="mb-2 mt-2" />
                    </div>
                    <h4 class="text-center"><b>Sign in</b></h4>

                    <div class="mb-3 bg-light p-3 rounded" style="font-size: 14px">
                        <ul style="padding-left: 1.2rem; margin: 0; color: #000;">
                            Gunakan jenis akun berikut:
                            <li>Akun F (Pengurus Barang)</li>
                            <li>Akun D (P3B)</li>
                            <li>Akun A (Kepala SKPD/UKPD/UPB)</li>
                        </ul>
                    </div>

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="username">NRK/Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>

                    <div class="bottom-links mt-4">
                        <a href="#">Video Tutorial</a> |
                        <a href="#">Manual Book</a>
                        <br>
                        <p class="d-block mt-3">&copy; BPAD DKI Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($errors->has('login_error'))
    <script>
        $(document).ready(function () {
            $.toast({
                heading: 'Login Gagal',
                text: '{{ $errors->first('login_error') }}',
                position: 'top-right',
                icon: 'error',
                stack: false
            });
        });
    </script>
@endif
