@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route ( 'cms-pages.store' ) }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="title" class="form-label">@lang('admin_fields.title')</label>
                                    <input id="title" type="text" name="title" class="form-control @error( 'title' ) is-invalid @enderror" value="{{ old ( 'title' ) }}" placeholder="@lang( 'admin_fields.title' )" autocomplete="title">
                                    @error( 'title' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="slug" class="form-label">@lang('admin_fields.slug')</label>
                                    <select name="slug" id="slug" class="form-select select2 @error( 'slug' ) is-invalid @enderror">
                                    <option value="terms_&_conditions">@lang ( 'admin_fields.terms_&_conditions' )</option>
                                    <option value="about_us" selected>@lang ( 'admin_fields.about_us' )</option>
                                    <option value="privacy_policy">@lang ( 'admin_fields.privacy_policy' )</option>
                                </select>
                                    @error( 'slug' )
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
                                <label for="content" class="form-label">@lang('admin_fields.content')</label>
                                    <textarea id="content" type="text" name="content" class="form-control @error( 'content' ) is-invalid @enderror" style="height: 300px">{{ old ( 'content' ) }}</textarea>
                                    @error( 'content' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                                <select name="status" id="status" class="form-select select2 @error( 'status' ) is-invalid @enderror">
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
                                <a href="{{ route ( 'cms-pages.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

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
