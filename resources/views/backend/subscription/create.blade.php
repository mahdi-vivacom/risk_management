@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route ( 'subscriptions.store' ) }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="title" class="form-label">@lang('admin_fields.title')</label>
                                    <input id="title" type="text" name="title" class="form-control @error( 'title' ) is-invalid @enderror" value="{{ old ( 'title' ) }}" placeholder="@lang( 'admin_fields.title' )" autocomplete="title">
                                    @error( 'title' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="label" class="form-label">@lang('admin_fields.label')</label>
                                    <input id="label" type="text" name="label" class="form-control @error( 'label' ) is-invalid @enderror" value="{{ old ( 'label' ) }}" placeholder="@lang( 'admin_fields.label' )" autocomplete="label">
                                    @error( 'label' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="amount" class="form-label">@lang('admin_fields.amount')</label>
                                    <input id="amount" type="text" name="amount" class="form-control @error( 'amount' ) is-invalid @enderror" value="{{ old ( 'amount' ) }}" placeholder="@lang( 'admin_fields.amount' )" autocomplete="amount">
                                    @error( 'amount' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="renewal" class="form-label">@lang ( 'admin_fields.renewal' )</label>
                                <select name="renewal" id="renewal" class="form-select select2 @error( 'renewal' ) is-invalid @enderror">
                                    <option value="Monthly" selected>Monthly</option>
                                    <option value="3 Months">3 Months</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                                @error( 'renewal' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="area_id" class="form-label">@lang ( 'admin_fields.area' )</label>
                                <select name="area_id" id="area_id" class="form-select select2 @error( 'area_id' ) is-invalid @enderror">
                                    <option value="">@lang ( 'admin_fields.select' )</option>
                                    @foreach ( $areas as $area )
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error( 'area_id' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="details" class="form-label">@lang('admin_fields.details')</label>
                                <textarea id="textarea" type="text" name="details" class="form-control @error( 'details' ) is-invalid @enderror" value="{{ old ( 'details' ) }}" placeholder="@lang( 'admin_fields.details' )" autocomplete="details"></textarea>
                                @error( 'details' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label">@lang('admin_fields.description')</label>
                                <textarea id="textarea" type="text" name="description" class="form-control @error( 'description' ) is-invalid @enderror" value="{{ old ( 'description' ) }}" placeholder="@lang( 'admin_fields.description' )" autocomplete="description"></textarea>
                                @error( 'description' )
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
                                <a href="{{ route ( 'subscriptions.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

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
