@extends('backend.layout.master')
@section('custom_css')
<style>
    /* .profile-pic {
        max-width: 200px;
        max-height: 200px;
        display: block;
    } */

    .file-upload {
        display: none;
    }

    .circle {
        /* border-radius: 1000px !important; */
        overflow: hidden;
        /* width: 128px; */
        /* height: 128px; */
        border: 8px solid rgba(255, 255, 255, 0.7);
        /* position: absolute; */
        /* top: 72px; */
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .p-image {
        /* position: absolute; */
        /* top: 167px; */
        /* right: 30px; */
        float: right;
        color: #666666;
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
    }

    .p-image:hover {
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
    }

    .upload-button {
        font-size: 1.2em;
    }

    .upload-button:hover {
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
        color: #999;
    }
</style>
@endsection
@section('content')

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <div class="contanier">
                            <div class="circle">
                                <img class="profile-pic rounded-circle" src="{{ !empty(auth()->user()->profile_image) ? asset(auth()->user()->profile_image) : asset('/backend/assets/img/profile_image.jpg') }}" alt="Profile-Image">
                            </div>
                            <div class="p-image">
                                <i class="bi bi-camera upload-button"></i>
                            </div>
                        </div>
                        <h2>{{ auth()->user()->name }}</h2>
                        <small>{{ auth()->user()->email }}</small>
                        <small><strong>{{ auth()->user()->roles->pluck('display_name')[0] }}</strong></small>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link @if(isset(auth()->user()->password_set_by_user_id) && auth()->user()->password_set_by_user_id == auth()->id()) show active @endif" data-bs-toggle="tab" data-bs-target="#profile-edit">@lang('admin_fields.profile_overview')</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link @if(auth()->user()->password_set_by_user_id != auth()->id()) show active @endif" data-bs-toggle="tab" data-bs-target="#profile-change-password">@lang('admin_fields.change_password')</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade @if(isset(auth()->user()->password_set_by_user_id) && auth()->user()->password_set_by_user_id == auth()->id()) show active @endif profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form method="post" action="{{ route('profile.update') }}" novalidate enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <input class="file-upload" type="file" accept="image/*" name="profile_image"/>
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.name')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">@lang('admin_fields.enter_your_name')</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone_number" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.phone_number')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required autocomplete="phone_number">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">@lang('admin_fields.enter_your_phone_no')</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.email_address')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="Email" value="{{ old('email', $user->email) }}" readonly autocomplete="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">@lang('admin_fields.enter_your_email')</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">@lang('admin_fields.save_changes')</button>
                                </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade @if(auth()->user()->password_set_by_user_id != auth()->id()) show active @endif profile-edit pt-3" id="profile-change-password">
                                @if(isset(auth()->user()->password_set_by_user_id) && auth()->user()->password_set_by_user_id != auth()->id())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @lang('admin_fields.need_to_change_password')
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <!-- Change Password Form -->
                                <form method="post" action="{{ route('password.update') }}" novalidate>
                                    @csrf
                                    @method('put')

                                <div class="row mb-3">
                                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.current_password')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="current_password" type="password" class="form-control" id="current_password" autocomplete="current-password" required>
{{--                                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror--}}
                                        @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.new_password')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="password" autocomplete="new-password" required>
{{--                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror--}}
                                        @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">@lang('admin_fields.confirm_new_password')</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" autocomplete="new-password" required>
                                        @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">@lang('admin_fields.change_password')</button>
                                </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('custom_js')
<script>
    $(document).ready(function() {
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.profile-pic').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".file-upload").on('change', function(){
            readURL(this);
        });
        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });
    });
</script>
@endsection
