@extends( 'backend.layout.master' )
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <form class="row g-3" enctype="multipart/form-data" action="{{ route ( $route . '.store' ) }}" method="post" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">@lang( 'admin_fields.name' )</label>
                            <input id="name" type="text" name="name"
                                class="form-control @error( 'name' ) is-invalid @enderror"
                                value="{{ old ( 'name' ) }}" placeholder="@lang( 'admin_fields.name' )"
                                autocomplete="name">
                            @error( 'name' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">@lang( 'admin_fields.phone_number' )</label>
                            <input id="phone_number" type="text" name="phone_number"
                                class="form-control @error( 'phone_number' ) is-invalid @enderror"
                                value="{{ old ( 'phone_number' ) }}" placeholder="@lang( 'admin_fields.phone_number' )"
                                autocomplete="phone_number">
                            @error( 'phone_number' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">@lang( 'admin_fields.password' )</label>
                            <input id="password" type="password" name="password"
                                class="form-control @error( 'password' ) is-invalid @enderror"
                                placeholder="@lang( 'admin_fields.password' )" autocomplete="password">
                            @error( 'password' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">@lang( 'admin_fields.email' )</label>
                            <input id="email" type="email" name="email"
                                class="form-control @error( 'email' ) is-invalid @enderror"
                                value="{{ old ( 'email' ) }}" placeholder="@lang( 'admin_fields.email' )"
                                autocomplete="email">
                            @error( 'email' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">@lang ( 'admin_fields.role' )</label>
                            <select name="role" id="role" class="form-select select2 @error( 'role' ) is-invalid @enderror">
                                <option value="">@lang ( 'admin_fields.select' )</option>
                                @foreach ( $roles as $role )
                                    <option value="{{ $role->id }}" {{ old ( 'role' ) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error( 'role' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="profile_image" class="form-label">@lang( 'admin_fields.profile_image' )</label>
                            <input id="profile_image" type="file" name="profile_image"
                                class="form-control @error( 'profile_image' ) is-invalid @enderror"
                                value="{{ old ( 'profile_image' ) }}"
                                placeholder="@lang( 'admin_fields.profile_image' )" autocomplete="password">
                            @error( 'profile_image' )
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save' )</button>
                            <button type="reset" class="btn btn-outline-warning btn-sm">@lang (
    'admin_fields.clear',
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