<!-- Common Scripts -->
<script type="text/javascript">
    var toastTime = '{{ env('BACK_END_TOASTER_TIME', 5000) }}';

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        timerProgressBar: true,
        showConfirmButton: false,
        timer: toastTime
    });

    $(document).ready(function() {

        $('.select2').select2({
            placeholder: 'Select an option',
            width: 'resolve',
            allowClear: true,
        });

        $('.lang').on('click', function() {
            let url = $('#lang_url').val();
            let langCode = $(this)[0].dataset.lang;
            window.location.href = url + '?language=' + langCode;
        });

    });

    function confirmStatus(id, name, status) {
        var text = (status === 1) ? 'You want to activate this ' + name + ' status ??' :
            'You want to inactivate this ' + name + ' status ??';
        Swal.fire({
            title: 'Are you sure?',
            text: text,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.href + '-status',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status,
                    },
                    success: function(response) {
                        if (response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            $('#' + name + '-DataTable').DataTable().ajax.reload();
                        }
                    },
                    error: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                    }
                });
            }
        });
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this ' + name + ' ??',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes ! Delete',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.href + '/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            $('#' + name + '-DataTable').DataTable().ajax.reload();
                        }
                    },
                    error: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                    }
                });
            }
        });
    }

</script>

@if (\Session::has('success'))
    <script type="text/javascript">
        $(document).ready(function() {
            const message = "{!! \Session::get('success') !!}";
            if (message) {
                $(function() {
                    Toast.fire({
                        icon: 'success',
                        title: message
                    })
                });
            }
        });
    </script>
@endif
@if (\Session::has('error'))
    <script type="text/javascript">
        $(document).ready(function() {
            const message = "{!! \Session::get('error') !!}";
            if (message) {
                $(function() {
                    Toast.fire({
                        icon: 'error',
                        title: message
                    })
                });
            }
        });
    </script>
@endif
