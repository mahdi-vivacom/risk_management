@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="{{ route('menus.store') }}" method="post" novalidate>
                    @csrf
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="row">
                                        <label for="label" class="col-sm-2 col-form-label">@lang('admin_fields.menu_label')</label>
                                        <div class="col-sm-8">
                                            <input name="label" class="form-control @error('label') is-invalid @enderror" type="text" value="{{ old('label') }}" placeholder="Menu Label" autocomplete="label">
                                            @error('label')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="row">
                                        <label for="serial" class="col-sm-2 col-form-label">@lang('admin_fields.menu_serial')</label>
                                        <div class="col-sm-8">
                                            <input name="serial" class="form-control @error('serial') is-invalid @enderror" type="number" value="{{ old('serial') }}" placeholder="Menu Serial" autocomplete="serial">
                                            @error('serial')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="row">
                                        <label for="icon" class="col-sm-2 col-form-label">@lang('admin_fields.menu_icon')</label>
                                        <div class="col-sm-8">
                                            <input name="icon" class="form-control @error('icon') is-invalid @enderror" type="text" value="{{ old('icon') }}" placeholder="Menu Icon" autocomplete="icon">
                                            @error('icon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="route" class="col-sm-2 col-form-label">@lang('admin_fields.route')</label>
                                        <div class="col-sm-8">
                                            <select name="route" class="form-control select2 @error('route') is-invalid @enderror" title="@lang('admin_fields.route')">
                                                <option value="" >Please Select</option>
                                                @foreach ($routeNames as $routeName)
                                                <option value="{{ $routeName }}" {{ old('route') == $routeName ? "selected" : "" }}>{{ $routeName }}</option>
                                                @endforeach
                                            </select>
                                            @error('route')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="parent_id" class="col-sm-2 col-form-label">@lang('admin_fields.parent_menu')</label>
                                        <div class="col-sm-8">
                                            <select name="parent_id" class="form-control select2 @error('parent_id') is-invalid @enderror" title="@lang('admin_fields.parent_menu')">
                                                <option value="">Please Select</option>
                                                @foreach ($menus as $menu)
                                                <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? "selected" : "" }}>{{ $menu->label }}</option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="permission_id" class="col-sm-2 col-form-label">@lang('admin_fields.permissions')</label>
                                        <div class="col-sm-8">
                                            <select name="permission_id" class="form-control select2 @error('permission_id') is-invalid @enderror" title="@lang('admin_fields.permissions')">
                                                <option value="">Please Select</option>
                                                @foreach ($permissions as $permission)
                                                <option value="{{ $permission->id }}" {{ old('permission_id') == $permission->id ? "selected" : "" }}>{{ $permission->display_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('permission_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('menus.index') }}" type="button" class="btn btn-outline-secondary btn-sm">@lang('admin_fields.back')</a>
                            <button type="submit" class="btn btn-outline-success btn-sm" onClick="this.form.submit(); this.disabled=true; this.innerText='Submitting .....';">@lang('admin_fields.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script type="text/javascript">

    </script>
@endsection
