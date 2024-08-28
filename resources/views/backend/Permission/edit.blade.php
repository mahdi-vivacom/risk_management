@extends( 'backend.layout.master' )
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route ( $route . '.update', [ 'permission' => $permission->id ] ) }}" method="post"
                    novalidate>
                    @csrf
                    @method( 'patch' )
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <label for="display_name" class="col-sm-2 col-form-label">Display Name</label>
                                    <div class="col-sm-8">
                                        <input name="display_name"
                                            class="form-control @error( 'display_name' ) is-invalid @enderror"
                                            type="text" value="{{ old ( 'display_name', $permission->display_name ) }}"
                                            placeholder="Display Name" autocomplete="display_name">
                                        @error( 'display_name' )
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <label for="module_name" class="col-sm-2 col-form-label">Module Name</label>
                                    <div class="col-sm-8">
                                        <input name="module_name"
                                            class="form-control @error( 'module_name' ) is-invalid @enderror"
                                            type="text" value="{{ old ( 'module_name', $permission->module_name ) }}"
                                            placeholder="Module Name" autocomplete="module_name">
                                        @error( 'module_name' )
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route ( $route . '.index' ) }}" type="button"
                            class="btn btn-outline-secondary btn-sm">Back</a>
                        <!-- <button type="reset" class="btn btn-outline-danger btn-sm">Clear</button> -->
                        <button type="submit" class="btn btn-outline-success btn-sm"
                            onClick="this.form.submit(); this.disabled=true; this.innerText='Updating .....';">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection