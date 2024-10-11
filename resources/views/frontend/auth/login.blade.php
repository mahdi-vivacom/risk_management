@extends('frontend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <style>
        .page-login::before {
            /* background-image: url("{{ asset('backend/assets/img/login-bg.png') }}"); */
            background-color: skyblue;
        }

        .page-dark.layout-full::before {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            content: "";
            background-position: center top;
            background-size: cover;
        }

        .layout-full {
            height: 100%;
            padding-top: 0 !important;
        }

        .logo img {
            max-height: 60px;
        }
    </style>
    <section
        class="page-login page-dark layout-full section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center pt-5">
                                <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('/backend') }}/assets/img/hrm_logo.png" alt="{{ config('app.name') }}">
                                    {{-- <span class="d-none d-lg-block">{{ config('app.name') }}</span> --}}
                                </a>
                            </div>

                            {{-- <h5 class="card-title text-center fs-4">Login to Your Account</h5> --}}

                            <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="col-12">
                                    <label for="youremail" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" id="youremail"
                                            value="{{ old('email') }}" autocomplete="on" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Please enter valid email!</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="yourPassword"
                                        autocomplete="off" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" value="t rue"
                                            id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">{{ __('Remember me') }}</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>

                                <div class="col-12">
                                    {{-- <p class="small mb-0"><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></p> --}}
                                </div>

                                {{-- <div class="col-12">
                                    <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create an
                                            account</a></p>
                                </div> --}}
                            </form>

                            <small class="copyright pt-3">&copy;
                                <strong><a href="{{ url(env('COPYRIGHT_URL')) }}">{{ env('COPYRIGHT_TITLE') }}</a></strong>
                                |
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> - All Rights Reserved.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
@endsection
