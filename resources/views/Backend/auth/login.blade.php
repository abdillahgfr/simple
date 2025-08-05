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

        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-left img {
            max-height: 80%;
            width: auto;
        }

        .login-right {
            border-left: #e5e5e5 1px solid;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box h4 {
            font-weight: bold;
            color: #333;
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
            width: 100%;
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
            {{-- LEFT IMAGE SIDE --}}
            <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                <div class="text-center p-3 w-75 d-flex flex-column align-items-center">
                    {{-- Logo bagian atas --}}
                    <div class="d-flex justify-content-center" style="margin-bottom: -2.75rem !important">
                        <img src="{{ asset('assets/img/image-up-login.png') }}" style="width: 40px;" alt="Logo Pemprov">
                        <img src="{{ asset('assets/img/image-up.png') }}" style="width: 60px;" alt="Logo BPAD">
                    </div>

                    {{-- Gambar utama --}}
                    <img src="{{ asset('assets/img/image-front.png') }}" alt="Ilustrasi Aset Idle">
                </div>
            </div>

            {{-- RIGHT LOGIN FORM SIDE --}}
            <div class="col-md-6 col-sm-6">
                <div class="login-box mx-auto" style="max-width: 420px;">
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/logo-header.png') }}" width="250" class="mb-3 mt-2" />
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

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="bottom-links mt-4 text-center">
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
        $(document).ready(function() {
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
