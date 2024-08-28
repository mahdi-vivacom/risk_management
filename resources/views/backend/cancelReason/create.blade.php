@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route ( 'cancel-reasons.store' ) }}" method="post" novalidate>
                            @csrf
                            
                            <div class="col-md-6">
                                <label for="reason" class="form-label">@lang('admin_fields.reason')</label>
                                    <input id="reason" type="text" name="reason" class="form-control @error( 'reason' ) is-invalid @enderror" value="{{ old ( 'reason' ) }}" placeholder="@lang( 'admin_fields.reason' )" autocomplete="reason">
                                    @error( 'reason' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="reason_for" class="form-label">@lang ( 'admin_fields.app' )</label>
                                <select name="reason_for" id="reason_for" class="form-select select2 @error( 'reason_for' ) is-invalid @enderror">
                                    <option value="1">@lang ( 'admin_fields.client' )</option>
                                    <option value="2" selected>@lang ( 'admin_fields.professional' )</option>
                                </select>
                                @error( 'reason_for' )
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
                                <a href="{{ route ( 'cancel-reasons.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

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
