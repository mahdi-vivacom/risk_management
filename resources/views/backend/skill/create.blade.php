@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <form class="row g-3" action="{{ route ( 'skills.store' ) }}" method="post" novalidate
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">@lang('admin_fields.name')</label>
                            <input id="name" type="text" name="name"
                                class="form-control @error( 'name' ) is-invalid @enderror" value="{{ old ( 'name' ) }}"
                                placeholder="Name" autocomplete="name">
                            @error( 'name' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label">
                                @lang('admin_fields.image') <small class="text-danger">* (Dimensions : width=512,height=512 )</small>
                            </label>
                            <input id="image" type="file" name="image"
                                class="form-control @error( 'image' ) is-invalid @enderror"
                                value="{{ old ( 'image' ) }}" placeholder="Image" autocomplete="Image">
                            @error( 'image' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="description" class="form-label">@lang('admin_fields.description')</label>
                            <textarea id="description" name="description"
                                class="form-control @error( 'description' ) is-invalid @enderror" type="text"
                                value="{{ old ( 'description' ) }}" placeholder="description" rows="3"></textarea>
                            @error( 'description' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">@lang('admin_fields.status')</label>
                            <select name="status" id="status"
                                class="form-select @error( 'status' ) is-invalid @enderror">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error( 'status' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm">@lang('admin_fields.submit')</button>
                            <button type="reset" class="btn btn-outline-warning btn-sm">@lang('admin_fields.reset')</button>
                            <a href="{{ route ( 'skills.index' ) }}" type="button"
                                class="btn btn-outline-secondary btn-sm">@lang('admin_fields.back')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section( 'custom_js' )
<script type="text/javascript">

</script>
@endsection
