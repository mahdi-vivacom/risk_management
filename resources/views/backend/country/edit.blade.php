@extends( 'backend.layout.master' )
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route ( 'countries.update', [ 'country' => $country->id ] ) }}" method="post" novalidate>
                    @csrf
                    @method( 'patch' )
                    <div class="card-body pt-3">
                        <input name="id" value="{{ $country->id }}" type="hidden" />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang('admin_fields.name')</label>
                                <input id="name" type="text" name="name" class="form-control @error( 'name' ) is-invalid @enderror" placeholder="Name" autocomplete="name" value="{{ old ( 'name', $country->name ) }}">
                                @error( 'name' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="iso_code_2" class="form-label">@lang('admin_fields.iso_code_alpha_2')</label>
                                <input id="iso_code_2" type="text" name="iso_code_2" class="form-control @error( 'iso_code_2' ) is-invalid @enderror" placeholder="BD" value="{{ old ( 'iso_code_2', $country->iso_code_2 ) }}">
                                @error( 'iso_code_2' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="iso_code_3" class="form-label">@lang('admin_fields.iso_code_alpha_2')</label>
                                <input id="iso_code_3" type="text" name="iso_code_3"
                                    class="form-control @error( 'iso_code_2' ) is-invalid @enderror" placeholder="BGD" value="{{ old ( 'iso_code_3', $country->iso_code_3 ) }}">
                                @error( 'iso_code_3' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone_code" class="form-label">@lang('admin_fields.phone_code')</label>
                                <input id="phone_code" type="text" name="phone_code"
                                    class="form-control @error( 'phone_code' ) is-invalid @enderror" placeholder="+88" value="{{ old ( 'phone_code', $country->phone_code ) }}">
                                @error( 'phone_code' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="distance_unit" class="form-label">Distance Unit</label>
                                <select name="distance_unit" id="distance_unit"
                                    class="form-select @error( 'distance_unit' ) is-invalid @enderror">
                                    <option value="1" {{ old ( 'distance_unit', $country->distance_unit ) == 1 ? 'selected' : '' }}>Kilometer</option>
                                    <option value="0" {{ old ( 'distance_unit', $country->distance_unit ) == 0 ? 'selected' : '' }}>Mile</option>
                                </select>
                                @error( 'distance_unit' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang('admin_fields.status')</label>
                                <select name="status" id="status"
                                    class="form-select @error( 'status' ) is-invalid @enderror">
                                    <option value="1" {{ old ( 'status', $country->status ) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old ( 'status', $country->status ) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error( 'status' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">@lang('admin_fields.description')</label>
                                <textarea id="description" name="description"
                                    class="form-control @error( 'description' ) is-invalid @enderror" placeholder="Description" rows="3">{{ old ( 'description', $country->description ) }}</textarea>
                                @error( 'description' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route ( 'countries.index' ) }}" type="button"
                            class="btn btn-outline-secondary btn-sm">@lang('admin_fields.back')</a>
                        <button type="submit" class="btn btn-outline-success btn-sm"
                            onClick="this.form.submit(); this.disabled=true; this.innerText='Updating .....';">@lang('admin_fields.update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
