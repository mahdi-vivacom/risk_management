@extends('backend.layout.master')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <form action="{{ route($route . '.store') }}" method="post" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="row">
                                        <label for="name" class="col-md-3 col-form-label">Name</label>
                                        <div class="col-md-9">
                                            <input name="name" class="form-control @error('name') is-invalid @enderror"
                                                type="text" value="{{ old('name') }}" placeholder="Name" required
                                                autocomplete="name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="row">
                                        <label for="display_name" class="col-md-3 col-form-label">Display Name</label>
                                        <div class="col-md-9">
                                            <input name="display_name"
                                                class="form-control @error('display_name') is-invalid @enderror"
                                                type="text" value="{{ old('display_name') }}" placeholder="Display Name"
                                                required autocomplete="display_name">
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="10%">Modules</th>
                                            <th width="10%">All</th>
                                            <th width="80%">Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($permissions as $key => $permission)
                                            @php
                                                $spacelesskey = str_replace(' ', '', $key);
                                            @endphp
                                            <tr>
                                                <td><b>{{ $key }}</b></td>
                                                <td>
                                                    @php
                                                        $checkAllPb = '';
                                                        if (!empty(old('selectall_' . $spacelesskey))) {
                                                            $checkAllPb = 'checked';
                                                        }
                                                    @endphp
                                                    <input type="checkbox" name="selectall_{{ $spacelesskey }}"
                                                        id="selectall_{{ $spacelesskey }}" class="checkAll"
                                                        module="{{ $spacelesskey }}" value="1" {{ $checkAllPb }} />
                                                    <label for="selectall_{{ $spacelesskey }}">All</label>
                                                </td>
                                                <td>
                                                    @foreach ($permission as $ikey => $value)
                                                        <p class="icheck-primary d-flex">
                                                            <input type="checkbox" class="permcheck {{ $spacelesskey }}"
                                                                id="{{ $value }}" name="permission[]"
                                                                style="margin-right: .6rem !important;"
                                                                module="{{ $spacelesskey }}" value="<?= $ikey ?>"
                                                                @if (is_array(old('permission')) && in_array($ikey, old('permission')))
                                                            checked
                                                    @endif />
                                                    <label for="{{ $value }}">{{ $value }}</label>
                                                    </p>
                                        @endforeach
                                        </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan=3 class="text-center">{{ __('No module available yet.') }}</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route($route . '.index') }}" type="button"
                                class="btn btn-outline-secondary btn-sm">Back</a>
                            <button type="submit" class="btn btn-outline-success btn-sm"
                                onClick="this.form.submit(); this.disabled=true; this.innerText='Submitting .....';">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
    <script type="text/javascript">
        $('.checkAll').click(function() {
            var modulename = $(this).attr('module');
            if ($(this).prop("checked") == true) {
                $("." + modulename).prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $("." + modulename).prop("checked", false);
            }
        });
        $('.permcheck').click(function() {
            var allChecked = true;
            var modulename = $(this).attr('module');
            $('.' + modulename).each(function() {
                if (!(this.checked)) {
                    allChecked = false;
                }
            });
            if (allChecked == true) {
                $("#selectall_" + modulename).prop("checked", true);
            } else {
                $("#selectall_" + modulename).prop("checked", false);
            }
        });
    </script>
@endsection
