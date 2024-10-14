@extends('frontend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <style>
        .form-control {
            border: 2px solid #191919;
            background-color: #191919;
        }

        .form-control:focus {
            border: 2px solid #191919;
            background-color: #191919;
        }

        .page-login::before {
            background-color: #000000;
        }

        .dark {
            background-color: #000000;
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

        /* Hide the default checkbox */
        .form-check-input {
            appearance: none;
            -webkit-appearance: none;
            background-color: #DCDCDC;
            border: 2px solid #ccc;
            width: 20px;
            height: 20px;
            cursor: pointer;
            border-radius: 4px;
            position: relative;
        }

        /* Style the checkbox when it's checked */
        .form-check-input:checked {
            background-color: #4f4f4f;
            /* Change to green when checked */
            border: 2px solid #4f4f4f;
        }

        /* Add a checkmark when it's checked */
        .form-check-input:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>
    <section
        class="page-login page-dark layout-full section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="card dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-center pt-5">
                                <a href="{{ url('/') }}" class="d-flex align-items-center w-auto">
                                    <img src="{{ asset('/backend') }}/assets/img/hrm-mini.png" alt="{{ config('app.name') }}"
                                        height="96px;">
                                </a>
                            </div>

                            <form class="row g-3 needs-validation py-5" novalidate method="POST"
                                action="{{ route('login') }}">
                                @csrf

                                <div class="col-12">
                                    <label for="youremail" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        {{-- <span class="input-group-text" id="inputGroupPrepend">@</span> --}}
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

                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" value="true"
                                            id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">{{ __('Remember me') }}</label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <button class="btn btn-white float-end" type="submit"
                                        style="background-color: #DCDCDC;">Log In</button>
                                </div>

                                <div class="col-12">
                                    {{-- <p class="small mb-0"><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></p> --}}
                                </div>

                                {{-- <div class="col-12">
                                    <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create an
                                            account</a></p>
                                </div> --}}
                            </form>

                            <small class="d-flex justify-content-center"><a href="{{ url(env('POLICY_URL')) }}"><u>Privacy
                                        Policy</u></a></small>
                            {{-- <small class="copyright pt-3">&copy;
                                <strong><a href="{{ url(env('COPYRIGHT_URL')) }}">{{ env('COPYRIGHT_TITLE') }}</a></strong>
                                |
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> - All Rights Reserved.
                            </small> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
@endsection
