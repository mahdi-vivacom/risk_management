@extends( 'backend.layout.master' )
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route ( 'skills.update', [ 'skill' => $skill->id ] ) }}" method="post" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    @method( 'patch' )
                    <div class="card-body pt-3">
                        <input name="id" value="{{ $skill->id }}" type="hidden" />
                        <div class="row">

                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang('admin_fields.name')</label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error( 'name' ) is-invalid @enderror"
                                    value="{{ old ( 'name', $skill->name ) }}" placeholder="Name" autocomplete="name">
                                @error( 'name' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="image" class="form-label">
                                    @lang('admin_fields.Image') <small class="text-danger">* (Dimensions : width=512,height=512 )</small>
                                </label>
                                <input id="image" type="file" name="image"
                                    class="form-control @error( 'image' ) is-invalid @enderror" placeholder="Image"
                                    autocomplete="Image">
                                @error( 'image' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label">@lang('admin_fields.description')</label>
                                <textarea id="description" name="description"
                                    class="form-control @error( 'description' ) is-invalid @enderror"
                                    placeholder="Description"
                                    rows="3">{{ old ( 'description', $skill->description ) }}</textarea>
                                @error( 'description' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang('admin_fields.status')</label>
                                <select name="status" id="status"
                                    class="form-select @error( 'status' ) is-invalid @enderror">
                                    <option value="1" {{ $skill->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $skill->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error( 'status' )
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route ( 'skills.index' ) }}" type="button"
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
