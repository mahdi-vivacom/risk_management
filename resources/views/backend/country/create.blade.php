@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
{{--                        <h5 class="card-title">Multi Columns Form</h5>--}}
                        <form class="row g-3" action="{{ route('countries.store') }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang('admin_fields.name')</label>
                                    <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" autocomplete="name">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="iso_code_2" class="form-label">@lang('admin_fields.iso_code_alpha_2')</label>
                                    <input id="iso_code_2" type="text" name="iso_code_2" class="form-control @error('iso_code_2') is-invalid @enderror" placeholder="BD">
                                    @error('iso_code_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="iso_code_3" class="form-label">@lang('admin_fields.iso_code_alpha_3')</label>
                                    <input id="iso_code_3" type="text" name="iso_code_3" class="form-control @error('iso_code_2') is-invalid @enderror" placeholder="BGD">
                                    @error('iso_code_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone_code" class="form-label">@lang('admin_fields.phone_code')</label>
                                <input id="phone_code" type="text" name="phone_code" class="form-control @error('phone_code') is-invalid @enderror" placeholder="+88">
                                @error('phone_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="distance_unit" class="form-label">Distance Unit</label>
                                <select name="distance_unit" id="distance_unit" class="form-select @error( 'distance_unit' ) is-invalid @enderror">
                                    <option value="1" selected>Kilometer</option>
                                    <option value="0">Mile</option>
                                </select>
                                @error('distance_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang('admin_fields.status')</label>
                                <select name="status" id="status" class="form-select @error( 'status' ) is-invalid @enderror">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">@lang('admin_fields.description')</label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" type="text" value="{{ old('description') }}" placeholder="description" rows="3"></textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">@lang('admin_fields.submit')</button>
                                <button type="reset" class="btn btn-secondary">@lang('admin_fields.reset')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script type="text/javascript">

    </script>
@endsection
