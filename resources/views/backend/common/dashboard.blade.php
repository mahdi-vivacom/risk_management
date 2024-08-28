@extends('backend.layout.master')
@section('custom_css')
    <style>
        .card-title {
            padding: 5px 0 15px 0 !important;
        }

        .top-card .card-title {
            color: #FFFFFF;
        }

        .dashboard .info-card {
            padding-bottom: 5px !important;
            border: none !important;
        }

        .card-title span {
            color: #fff;
            font-size: 14px;
            font-weight: 400;
        }

        .dashboard .filter .icon {
            color: #fff;
            font-size: 16px;
        }

        .bg-c-blue {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
            color: white;
        }

        .bg-c-red {
            background: linear-gradient(45deg, #FF5370, #ff869a);
            color: white;
        }

        .bg-c-green {
            background: linear-gradient(45deg, #2ed8b6, #59e0c5);
            color: white;
        }

        .bg-c-yellow {
            background: linear-gradient(45deg, #FFB64D, #ffcb80);
            color: white;
        }

        .dashboard .info-card h6 {
            color: white;
        }

        @media only screen and (min-width: 992px) and (max-width: 1399px) {
            .card-title {
                font-size: 1rem !important;
            }
        }
    </style>
@endsection
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">

                    {{-- <h4 style="padding: 1.5rem 0rem .5rem 1rem">@lang('admin_fields.site_stat')</h4> --}}

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card customers-card bg-c-yellow">

                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.current_clients')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $clients }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">

                        <div class="card info-card revenue-card bg-c-green">

                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.current_professionals')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $professionals }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card customers-card bg-c-red">
                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.service_countries')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-world"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $countries }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card sales-card bg-c-blue">
                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.service_areas')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-globe-central-south-asia"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $areas }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">

                    {{-- <h4 style="padding: 1.5rem 0rem .5rem 1rem">@lang('admin_fields.booking_stat')</h4> --}}

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card sales-card bg-c-blue">

                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.professional_skill')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-category-alt"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $skills }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card revenue-card bg-c-red">

                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.on_going')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa fa-road"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $booking }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">

                        <div class="card info-card revenue-card bg-c-green">

                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.completed')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-badge-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $completed }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-6 col-xl-6 col-md-6">
                        <div class="card info-card customers-card bg-c-yellow">
                            <div class="card-body">
                                <h5 class="card-title">@lang('admin_fields.cancelled')</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri ri-close-circle-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $cancelled }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
@endsection
