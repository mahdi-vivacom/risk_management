@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="{{ route('permission.store') }}" method="post" novalidate>
                    @csrf
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="name" class="col-sm-2 col-form-label">@lang('admin_fields.name')</label>
                                        <div class="col-sm-8">
                                            <input name="name" class="form-control @error('name') is-invalid @enderror" type="text" value="{{ old('name') }}" placeholder="Name" autocomplete="name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="display_name" class="col-sm-2 col-form-label">@lang('admin_fields.display_name')</label>
                                        <div class="col-sm-8">
                                            <input name="display_name" class="form-control @error('display_name') is-invalid @enderror" type="text" value="{{ old('display_name') }}" placeholder="Display Name" autocomplete="display_name">
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="module_name" class="col-sm-2 col-form-label">@lang('admin_fields.module_name')</label>
                                        <div class="col-sm-8">
                                            <input name="module_name" class="form-control @error('module_name') is-invalid @enderror" type="text" value="{{ old('module_name') }}" placeholder="Module Name" autocomplete="module_name">
                                            @error('module_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('permission.index') }}" type="button" class="btn btn-outline-secondary btn-sm">@lang('admin_fields.back')</a>
                            <button type="reset" class="btn btn-outline-danger btn-sm">@lang('admin_fields.clear')</button>
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
        $('.checkAll').click(function(){
            var modulename = $(this).attr('module');
            if($(this).prop("checked") == true){
                $("."+modulename).prop("checked", true);
            }
            else if($(this).prop("checked") == false){
                $("."+modulename).prop("checked", false);
            }
        });
        $('.permcheck').click(function(){
            var allChecked  = true;
            var modulename = $(this).attr('module');
            $('.'+modulename).each(function () {
                if(!(this.checked)){
                    allChecked  = false;
                }
            });
            if (allChecked == true) {
                $("#selectall_"+modulename).prop("checked", true);
            } else {
                $("#selectall_"+modulename).prop("checked", false);
            }
        });
    </script>
@endsection
