@extends('frontend.layout.master')
@section('custom_css')
@endsection
@section('content')

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                <img src="/backend/assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">{{ config('app.name') }}</span>
              </a>
            </div>
            <!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  {{-- <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5> --}}
                  <p class="text-center small">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                </div>

                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('password.email') }}">
                  @csrf

                  <div class="col-12">
                    <label for="youremail" class="form-label">Email</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="youremail" value="{{ old('email') }}" autocomplete="off" required autofocus>
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @else
                        <div class="invalid-feedback">Please enter valid email!</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">{{ __('Email Password Reset Link?') }}</button>
                  </div>

                  <div class="col-12">
                    <p class="small mb-0">Back to <a href="{{ route('login') }}" class="fw-bold">Log in</a> ?</p>
                  </div>

                  {{-- <div class="col-12">
                    <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create an account</a></p>
                  </div> --}}
                </form>

              </div>
            </div>

            <div class="copyright">Copyright &copy; 
                <strong><span><a href="{{ url('/') }}">{{ config('app.name', '') }}</a></span></strong>
                <script>document.write(new Date().getFullYear());</script> - All Rights Reserved.
            </div>

          </div>
        </div>
      </div>

    </section>

@endsection
@section('custom_js')
@endsection