@extends('backend.layout.master')
@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }

        .iti--allow-dropdown input,
        .iti--allow-dropdown input[type=text],
        .iti--allow-dropdown input[type=tel],
        .iti--separate-dial-code input,
        .iti--separate-dial-code input[type=text],
        .iti--separate-dial-code input[type=tel] {}
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" enctype="multipart/form-data" action="{{ route('clients.store') }}"
                            method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">@lang('admin_fields.first_name')</label>
                                <input id="first_name" type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}" placeholder="@lang('admin_fields.first_name')"
                                    autocomplete="first_name">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">@lang('admin_fields.last_name')</label>
                                <input id="last_name" type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}" placeholder="@lang('admin_fields.last_name')" autocomplete="last_name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="country_id" class="form-label">@lang ( 'admin_fields.country' )</label>
                                <select name="country_id" id="country_id"
                                    class="form-select select2-country @error('country_id') is-invalid @enderror">
                                    <option value="">@lang ( 'admin_fields.select' )</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" alpha_2="{{ $country->iso_code_2 }}"
                                            {{ old('country_id') == $country->id ? 'selected' : '' }}
                                            data-code="{{ $country->phone_code }}">
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="alpha_2" id="alpha_2" value="">

                            <div class="col-md-6">
                                <label for="country_area_id" class="form-label">@lang ( 'admin_fields.area' )</label>
                                <select name="country_area_id" id="country_area_id"
                                    class="form-select select2 @error('country_area_id') is-invalid @enderror">
                                    <option value="">@lang ( 'admin_fields.select' )</option>
                                    {{-- @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('country_area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}</option>
                                    @endforeach --}}
                                </select>
                                @error('country_area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">@lang('admin_fields.phone_number')</label>
                                <input id="phone" type="text" name="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror intl_phone"
                                    value="{{ old('phone_number') }}" placeholder="@lang('admin_fields.phone_number')"
                                    autocomplete="phone_number">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">@lang('admin_fields.password')</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="@lang('admin_fields.password')" autocomplete="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">@lang('admin_fields.email')</label>
                                <input id="email" type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="@lang('admin_fields.email')" autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="profile_image" class="form-label">@lang('admin_fields.profile_image')</label>
                                <input id="profile_image" type="file" name="profile_image"
                                    class="form-control @error('profile_image') is-invalid @enderror"
                                    value="{{ old('profile_image') }}" placeholder="@lang('admin_fields.profile_image')"
                                    autocomplete="password">
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label">@lang ( 'admin_fields.gender' )</label>
                                <select name="gender" id="gender"
                                    class="form-select @error('gender') is-invalid @enderror">
                                    <option value="1">@lang ( 'admin_fields.male' )</option>
                                    <option value="2">@lang ( 'admin_fields.female' )</option>
                                    <option value="3">@lang ( 'admin_fields.others' )</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="1" selected>@lang ( 'admin_fields.active' )</option>
                                    <option value="0">@lang ( 'admin_fields.inactive' )</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save'
                                    )</button>
                                <button type="reset" class="btn btn-outline-warning btn-sm">@lang (
                                    'admin_fields.clear',
                                    )</button>
                                <a href="{{ route('clients.index') }}" type="button"
                                    class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let countryId = '{{ old('country_id') }}';
            getCountryArea(countryId);

            $('.select2-country').select2({
                placeholder: 'Select Country',
                allowClear: true
            });

            $('.select2-country').on('select2:select', function(e) {
                var selectedOption = e.params.data.element;
                var alpha2Value = selectedOption.getAttribute('alpha_2');
                let countryId = this.value;
                $('#alpha_2').val(alpha2Value);
                if (countryId !== "" && countryId !== null && countryId !== undefined) {
                    changePhoneCountry(countryId);
                    getCountryArea(countryId);
                    let phone = $('#phone').val();
                    if (phone !== "" && phone !== null) {
                        checkPhoneNumber(phone, countryId);
                    }
                }
            });

            $('#phone').on('input', function() {
                var countryId = $('.select2-country').val();
                if (countryId !== "" && countryId !== null && countryId !== undefined) {
                    checkPhoneNumber(this.value, countryId);
                }
            });

            function checkPhoneNumber(number, countryId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('phone.validation') }}",
                    data: {
                        phone_number: number,
                        country_id: countryId
                    },
                    success: function(data, textStatus, jqXHR) {
                        $("#phone").removeClass("is-invalid");
                        $("#phoneValidationMessage").removeClass("invalid-feedback");
                        $("#phoneValidationMessage").addClass("text-success");
                        $("#phoneValidationMessage").text("This is a valid number.");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#phone").addClass("is-invalid");
                        $("#phoneValidationMessage").removeClass("text-success");
                        $("#phoneValidationMessage").addClass("invalid-feedback");
                        $("#phoneValidationMessage").text("This is not a valid number!");
                    }
                });
            }

            function changePhoneCountry(countryId) {
                $.ajax({
                    method: 'GET',
                    url: "{{ url('get-country-info') }}/" + countryId,
                    success: function(data, textStatus, jqXHR) {
                        const input = document.querySelector(".intl_phone");
                        window.intlTelInput(input, {
                            initialCountry: data.iso_code_2.toLowerCase(),
                            onlyCountries: [data.iso_code_2.toLowerCase()],
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {}
                });
            }

            function getCountryArea(countryId) {
                $.ajax({
                    method: 'GET',
                    url: "{{ url('get-country-area') }}/" + countryId,
                    success: function(response) {
                        const areaId = "{{ old('country_area_id') }}";
                        $('#country_area_id').empty();
                        $('#country_area_id').append('<option value="">Select an area</option>');

                        $.each(response, function(index, area) {
                            let selected = '';
                            if (parseInt(areaId) === parseInt(area.id)) {
                                selected = 'selected';
                            }
                            $('#country_area_id').append('<option value="' + area.id + '" ' +
                                selected + '>' + area.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#country_area_id').empty();
                        $('#country_area_id').append('<option value="">Select an area</option>');
                        console.error('Error fetching areas:', error);
                    }
                });
            }

        });
    </script>
@endsection
