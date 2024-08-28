@extends( 'backend.layout.master' )

@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route ( $route . '.permission.update', [ 'id' => $user_id ] ) }}" method="post"
                    novalidate>
                    @csrf
                    <div class="card-body pt-3">
                        <div class="row">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th colspan=2 class="card-title">Modules</th>
                                        <th class="card-title">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse( $permissions as $key => $permission )
                                                                        @php
                                                                            $spacelesskey = str_replace ( " ", "", $key );
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $key }} : </td>
                                                                            <td>
                                                                            <td class="icheck-primary d-inline">
                                                                                @php
                                                                                    $checkAllPb = "";
                                                                                    if ( !empty ( old ( 'selectall_' . $spacelesskey ) ) ) {
                                                                                        $checkAllPb = "checked";
                                                                                    }
                                                                                @endphp
                                                                                <input type="checkbox" name="selectall_{{ $spacelesskey }}"
                                                                                    id="selectall_{{ $spacelesskey }}" class="checkAll"
                                                                                    module="{{ $spacelesskey }}" value="1" {{$checkAllPb}} />
                                                                                <label for="selectall_{{ $spacelesskey }}">All</label>
                                                                            </td>
                                                                            @foreach( $permission as $ikey => $value )
                                                                                                                @php
                                                                                                                    $checked = "";
                                                                                                                    if ( ( is_array ( old ( 'permission' ) ) && in_array ( $ikey, old ( 'permission' ) ) ) || ( in_array ( $ikey, $role_permissions ) ) ) {
                                                                                                                        $checked = "checked";
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                <td class="icheck-primary d-inline">
                                                                                                                    <input type="checkbox" class="permcheck {{ $spacelesskey }}"
                                                                                                                        id="{{ $value }}" name="permission[]" module="{{ $spacelesskey }}"
                                                                                                                        value="<?= $ikey ?>" {{ $checked }} />
                                                                                                                    <label for="{{ $value }}">{{ $value }}</label>
                                                                                                                </td>
                                                                            @endforeach
                                                                            </td>
                                                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan=2 class="text-center">{{ __ ( 'No module available yet.' ) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route ( $route . '.index' ) }}" type="button"
                            class="btn btn-outline-secondary btn-sm">@lang (
                            'admin_fields.back' )</a>
                        <button type="reset" class="btn btn-outline-danger btn-sm">@lang('admin_fields.clear')</button>
                        <button type="submit" class="btn btn-outline-success btn-sm"
                            onClick="this.form.submit(); this.disabled=true; this.innerText='Updating .....';">@lang('admin_fields.update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section( 'custom_js' )
<script type="text/javascript">
    $('.permcheck').each(function () {
        var allChecked = true;
        var modulename = $(this).attr('module');
        $('.' + modulename).each(function () {
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

    $('.checkAll').click(function () {
        var modulename = $(this).attr('module');
        if ($(this).prop("checked") == true) {
            $("." + modulename).prop("checked", true);
        }
        else if ($(this).prop("checked") == false) {
            $("." + modulename).prop("checked", false);
        }
    });

    $('.permcheck').click(function () {
        var allChecked = true;
        var modulename = $(this).attr('module');
        $('.' + modulename).each(function () {
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
