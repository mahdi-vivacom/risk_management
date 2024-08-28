@extends( 'backend.layout.master' )
@section( 'custom_css' )
@endsection
@section( 'content' )
<section class="section">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body pt-3">
                    <table id="userDataTable" class="table table-striped">
                        <thead>
                            <tr>
                                <!-- <th><i class="ri-hashtag"></i></th> -->
                                <th>@lang('admin_fields.name')</th>
                                <th>@lang('admin_fields.email')</th>
                                <th>@lang('admin_fields.role')</th>
                                <th>
                                @can( 'user_create' )
                                    <button type="button" class="btn btn-outline-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#add-modal-sm" title="{{ __ ( 'Add New User' ) }}"><i class="ri-user-add-line"></i></button>
                                @endcan
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Add modal Start-->
    <div class="modal fade" id="add-modal-sm"  tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('admin_fields.add_new_user')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="useradd">@csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role">@lang('admin_fields.user_role')</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">--- Select Role ---</option>
                                @foreach ( $role as $data )
                                    <option value="{{ $data->name }}">{{ $data->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">@lang('admin_fields.name')</label>
                            <input id="name" name="name" class="form-control" type="text" placeholder="User Name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="email">@lang('admin_fields.email')</label>
                            <input id="email" name="email" class="form-control" type="email" placeholder="User Email" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('admin_fields.password')</label>
                            <input id="password" name="password" class="form-control" type="password" placeholder="Password" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">@lang('admin_fields.confirm_password')</label>
                            <input id="password-confirm" name="password_confirmation" class="form-control" type="password" placeholder="Confirm Password" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer justify -content-between">
                        <button type="reset" class="btn btn-warning btn-sm" data-bs-dismiss="modal">@lang('admin_fields.close')</button>
                        <button type="submit" class="btn btn-success btn-sm userSave">@lang('admin_fields.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Add modal Ends-->

    <!--Edit modal Start-->
    <div class="modal fade" id="role-modal-sm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __ ( 'Edit User Role' ) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updatE"> @csrf
                <div class="modal-body">
                    <input class="user_id" type="hidden"/>
                    <div class="form-group">
                        <label for="name">@lang('admin_fields.name')</label>
                        <input name="name" class="form-control name" placeholder="User Name"/>
                    </div>
                    <div class="form-group">
                        <label for="role">@lang('admin_fields.role')</label>
                        <select name="role" class="form-control role">
                            <option value="">--- Select Role ---</option>
                            @foreach ( $role as $data )
                                <option value="{{ $data->name }}">{{ $data->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning btn-sm" data-bs-dismiss="modal">@lang('admin_fields.cancel')</button>
                    <button type="submit" class="btn btn-success btn-sm userUpdate">@lang('admin_fields.update')</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!--Edit modal Ends-->
    <!--Password modal Start-->
    <div class="modal fade" id="password-modal-sm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __ ( 'Change Password' ) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="change_password"> @csrf
                    <div class="modal-body">
                        <input class="uid" name="id" type="hidden"/>
                        <div class="form-group">
                            <label for="password">@lang('admin_fields.new_password')</label>
                            <input name="password" type="password" class="form-control password" placeholder="New Password" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">@lang('admin_fields.confirm_password')</label>
                            <input name="password_confirmation" type="password" class="form-control password-confirm" placeholder="Confirm Password" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning btn-sm" data-bs-dismiss="modal">@lang('admin_fields.cancel')</button>
                        <button type="submit" class="btn btn-success btn-sm passconfirm">@lang('admin_fields.confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Password modal Ends-->
</section>
@endsection
@section( 'custom_js' )
    <script type="text/javascript">
        let userDataTable = function () {
            let initTable = function () {
                let table = $('#userDataTable');
                table.DataTable({
                    responsive: true,
                    searchDelay: 0,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route ( 'users.get.list' ) }}',
                        type: 'POST',
                        data: {_token: "{{csrf_token ()}}"},
                    },
                    columns: [
                        {"data": "name"},
                        {"data": "email"},
                        {"data": "role_name"},
                        {"data": "action", sortable: false},
                    ]
                });
            };
            return {
                init: function () {
                    initTable();
                },

            };
        }();
        $(document).ready(function () {
            userDataTable.init();
            var $j = jQuery.noConflict();
            $('#useradd').on('submit', function (e) {
                e.preventDefault();
                $('#useradd').validate({
                    rules: {
                        name: {
                            required: true,
                        },
                        role: {
                            required: true,
                        },
                        email: {
                            required: true,
                            email: true,
                        },
                        password: {
                            required: true,
                            minlength: 6,
                        },
                        password_confirmation: {
                            required: true,
                            minlength: 6,
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        name: {
                            required: "Please enter user name",
                        },
                        role: {
                            required: "Please select user role",
                        },
                        email: {
                            required: "Please enter user email",
                            email: "Please enter a valid email address",
                        },
                        password: {
                            required: "Please enter password",
                            minlength: "Password minimum 6 characters needed",
                        },
                        password_confirmation: {
                            required: "Please enter password confirmation",
                            minlength: "Password minimum 6 characters needed",
                            equalTo: "Password must be matched",
                        },
                    },
                    submitHandler: function(form) {
                        let formData = new FormData(document.getElementById("useradd"));
                        $(".userSave").attr('disabled', true);
                        $(".userSave").html('Submitting...');
                        $.ajax({
                            type: "POST",
                            url: "{{ route ( 'users.store' ) }}",
                            data: formData,
                            dataType: 'JSON',
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $(".userSave").attr('disabled', false);
                                $('.userSave').html('Save');
                                if (response.type === 'success') {
                                    $('#add-modal-sm').modal('hide');
                                    $('#useradd').trigger("reset");
                                    $('#userDataTable').DataTable().destroy();
                                    userDataTable.init();
                                }
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            },
                            error: function (response) {
                                $(".userSave").attr('disabled', false);
                                $('.userSave').html('Save');
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            }
                        });
                    }
                })
            });
            $('#updatE').on('submit', function (e) {
                e.preventDefault();
                $('#updatE').validate({
                    rules: {
                        name: {
                            required: true,
                        },
                        role: {
                            required: true,
                        },
                    },
                    messages: {
                        name: {
                            required: "Please enter user name",
                        },
                        role: {
                            required: "Please select user role",
                        },
                    },
                    submitHandler: function(form) {
                        $(".userUpdate").attr('disabled', true);
                        $(".userUpdate").html('Updating...');
                        $.ajax({
                            type: "POST",
                            url: "{{ route ( 'users.update' ) }}",
                            data: {
                                '_token': $('input[name=_token]').val(),
                                'id': $(".user_id").val(),
                                'name': $(".name").val(),
                                'role': $(".role").val(),
                            },
                            success: function (response) {
                                $(".userUpdate").attr('disabled', false);
                                $('.userUpdate').html('Update');
                                if (response.type === 'success') {
                                    $('#role-modal-sm').modal('hide');
                                    $('#updatE').trigger("reset");
                                    $('#userDataTable').DataTable().destroy();
                                    userDataTable.init();
                                }
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            },
                            error: function (error) {
                                $(".userUpdate").attr('disabled', false);
                                $('.userUpdate').html('Update');
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            }
                        });
                    }
                })
            });
            $('#change_password').on('submit', function (e) {
                e.preventDefault();
                $('#change_password').validate({
                    rules: {
                        password: {
                            required: true,
                            minlength: 6,
                        },
                        password_confirmation: {
                            required: true,
                            minlength: 6,
                            equalTo: ".password"
                        },
                    },
                    messages: {
                        password: {
                            required: "Please enter new password",
                            minlength: "New password minimum 6 characters needed",
                        },
                        password_confirmation: {
                            required: "Please enter new password confirmation",
                            minlength: "Confirm password minimum 6 characters needed",
                            equalTo: "Confirm Password must be matched",
                        },
                    },
                    submitHandler: function(form) {
                        $(".passconfirm").attr('disabled', true);
                        $('.passconfirm').html('Updating...');
                        $.ajax({
                            type: "POST",
                            url: "{{ route ( 'users.change.password' ) }}",
                            data: {
                                '_token': $('input[name=_token]').val(),
                                'id': $(".uid").val(),
                                'password': $(".password").val(),
                                'password_confirmation': $(".password-confirm").val(),
                            },
                            success: function (response) {
                                $(".passconfirm").attr('disabled', false);
                                $('.passconfirm').html('Confirm');
                                if (response.type === 'success') {
                                    $('#password-modal-sm').modal('hide');
                                    $('#change_password').trigger("reset");
                                    $('#userDataTable').DataTable().destroy();
                                    userDataTable.init();
                                }
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            },
                            error: function (response) {
                                $('.passconfirm').html('Confirm');
                                $(function () {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                });
                            }
                        });
                    }
                })
            });
        });
        function editUserRole(id) {
            $.ajax({
                type: "GET",
                url: "{{ route ( 'users.edit' ) }}",
                data: {id: id},
                success: function (data) {
                    $('.user_id').val(data.id);
                    $('.name').val(data.name);
                    $('.role').val(data.role);
                }
            });
        }
        function userPass(id) {
            $('.uid').val(id);
        }
        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route ( 'users.destroy' ) }}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token ()}}',
                            id: id
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );
                            $('#userDataTable').DataTable().destroy();
                            userDataTable.init();
                        },
                        error: function (response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection
