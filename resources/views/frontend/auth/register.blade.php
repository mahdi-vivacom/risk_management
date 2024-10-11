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
                <img src="/backend/assets/img/hrm_logo.png" alt="{{ config('app.name') }}">
                {{-- <span class="d-none d-lg-block">{{ config('app.name') }}</span> --}}
              </a>
            </div>
            <!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                  {{-- <p class="text-center small">Enter your personal details to create account</p> --}}
                </div>

                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('register') }}">
                @csrf

                   <div class="col-12">
                    <label for="yourName" class="form-label @error('name') is-invalid @enderror">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" id="yourName" required value="{{ old('name') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @else
                    <div class="invalid-feedback">Please, enter your name!</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label for="yourEmail" class="form-label @error('email') is-invalid @enderror">{{ __('E-Mail Address') }}</label>
                    <input type="email" name="email" class="form-control" id="yourEmail" required value="{{ old('email') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @else
                    <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label @error('password') is-invalid @enderror">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control" id="yourPassword" required autocomplete="off">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @else
                    <div class="invalid-feedback">Please enter your password!</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off">
                    <div class="invalid-feedback">Please confirm your password!</div>
                  </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                      <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                      <div class="invalid-feedback">You must agree before submitting.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                  </div>

                  <div class="col-12">
                    <p class="small mb-0">Already have an account ? <a href="{{ route('login') }}">Log in</a></p>
                  </div>

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
