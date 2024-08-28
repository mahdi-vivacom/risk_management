@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route ( 'navigations.store' ) }}" method="post" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang('admin_fields.name')</label>
                                    <input id="name" type="text" name="name" class="form-control @error( 'name' ) is-invalid @enderror" value="{{ old ( 'name' ) }}" placeholder="@lang( 'admin_fields.name' )" autocomplete="name">
                                    @error( 'name' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="slug" class="form-label">@lang('admin_fields.slug')</label>
                                    <input id="slug" type="text" name="slug" class="form-control @error( 'slug' ) is-invalid @enderror" value="{{ old ( 'slug' ) }}" placeholder="@lang( 'admin_fields.slug' )" autocomplete="slug">
                                    @error( 'slug' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sequence" class="form-label">@lang('admin_fields.sequence')</label>
                                    <input id="sequence" type="number" name="sequence" class="form-control @error( 'sequence' ) is-invalid @enderror" value="{{ old ( 'sequence' ) }}" placeholder="@lang( 'admin_fields.sequence' )" autocomplete="sequence">
                                    @error( 'sequence' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="image" class="form-label">@lang('admin_fields.image')</label>
                                    <input id="image" type="file" name="image" class="form-control @error( 'image' ) is-invalid @enderror" value="{{ old ( 'image' ) }}" placeholder="@lang( 'admin_fields.image' )" autocomplete="password">
                                    @error( 'image' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="type" class="form-label">@lang ( 'admin_fields.type' )</label>
                                <select name="type" id="type" class="form-select select2 @error( 'type' ) is-invalid @enderror">
                                    <option value="1">@lang ( 'admin_fields.client' )</option>
                                    <option value="2" selected>@lang ( 'admin_fields.professional' )</option>
                                </select>
                                @error( 'type' )
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
                                <a href="{{ route ( 'navigations.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

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
