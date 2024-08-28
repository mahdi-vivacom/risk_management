@extends( 'backend.layout.master' )
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <form class="row g-3" action="{{ route ( $route . '.store' ) }}" method="post" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="amount" class="form-label">@lang('admin_fields.amount')</label>
                            <input id="amount" type="text" name="amount"
                                class="form-control @error( 'amount' ) is-invalid @enderror"
                                value="{{ old ( 'amount' ) }}" placeholder="@lang( 'admin_fields.amount' )"
                                autocomplete="amount">
                            @error( 'amount' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                            <select name="status" id="status"
                                class="form-select @error( 'status' ) is-invalid @enderror">
                                <option value="1" selected>@lang ( 'admin_fields.active' )</option>
                                <option value="0">@lang ( 'admin_fields.inactive' )</option>
                            </select>
                            @error( 'status' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save' )</button>
                            <button type="reset" class="btn btn-outline-warning btn-sm">@lang ( 'admin_fields.clear'
                                )</button>
                            <a href="{{ route ( $route . '.index' ) }}" type="button"
                                class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection