@extends('backend.layout.master')
@section('custom_css')
    <style>
        .btn-check:checked+.btn, .btn.active, .btn.show, .btn:first-child:active, :not(.btn-check)+.btn:active {
            color: var(--bs-btn-active-color);
            background-color: var(--bs-btn-active-bg);
            border-color: var(--bs-btn-active-border-color);
            background-color: #fdf4d3;
            border: 1px solid white;
            box-shadow: 0.1rem 0.1rem 0.5rem 0.1rem #d1c9c9;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="" method="post" class="needs-validation" novalidate>
                        @csrf
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio image-checkbox">
                                        <input type="radio" class="btn-check" name="options-base" id="theme1" autocomplete="off" checked>
                                        <label class="btn" for="theme1">
                                            <img src="{{ asset('backend/assets/img/news-4.jpg') }}" alt="#" class="img-fluid">
                                            <h5 class="mt-3">Theme 1</h5>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio image-checkbox">
                                        <input type="radio" class="btn-check" name="options-base" id="theme2" autocomplete="off">
                                        <label class="btn" for="theme2">
                                            <img src="{{ asset('backend/assets/img/news-5.jpg') }}" alt="#" class="img-fluid">
                                            <h5 class="mt-3">Theme 2</h5>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('dashboard') }}" type="button" class="btn btn-outline-secondary btn-sm">@lang('admin_fields.back')</a>
                            <button type="reset" class="btn btn-outline-danger btn-sm">@lang('admin_fields.clear')</button>
                            <button type="submit" class="btn btn-outline-success btn-sm" onClick="this.form.submit(); this.disabled=true; this.innerText='Submitting .....';">@lang('admin_fields.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
    <script type="text/javascript">

    </script>
@endsection
