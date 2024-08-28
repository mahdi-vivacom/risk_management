@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route ( 'sos.store' ) }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="number" class="form-label">@lang('admin_fields.number')</label>
                                    <input id="number" type="text" name="number" class="form-control @error( 'number' ) is-invalid @enderror" value="{{ old ( 'number' ) }}" placeholder="@lang( 'admin_fields.number' )" autocomplete="number">
                                    @error( 'number' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="country_id" class="form-label">@lang ( 'admin_fields.country' )</label>
                                <select name="country_id" id="country_id" class="form-select select2 @error( 'country_id' ) is-invalid @enderror">
                                    <option value="">@lang ( 'admin_fields.select' )</option>
                                    @foreach ( $countries as $country )
                                    <option value="{{ $country->id }}" {{ old ( 'country_id' ) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error( 'country_id' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="application" class="form-label">@lang ( 'admin_fields.app' )</label>
                                <select name="application" id="application" class="form-select select2 @error( 'application' ) is-invalid @enderror">
                                    <option value="1">@lang ( 'admin_fields.client_app' )</option>
                                    <option value="2">@lang ( 'admin_fields.professional_app' )</option>
                                </select>
                                @error( 'application' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                                <select name="status" id="status" class="form-select @error( 'status' ) is-invalid @enderror">
                                    <option value="1" selected>@lang ( 'admin_fields.active' )</option>
                                    <option value="0">@lang ( 'admin_fields.inactive' )</option>
                                </select>
                                @error( 'status' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save' )</button>
                                <button type="reset" class="btn btn-outline-warning btn-sm">@lang ( 'admin_fields.clear' )</button>
                                <a href="{{ route ( 'sos.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section( 'custom_js' )
@endsection
