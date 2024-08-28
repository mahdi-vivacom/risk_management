<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <title>{{ config('app.name') }} {{ !empty($title) ? '| ' . $title : '' }}</title>

    <!-- Favicons -->
    <link href="{{ asset('/backend/assets/img/favicon.ico') }}" rel="icon">
    <link href="/backend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/backend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/backend/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/backend/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/backend/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/backend/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/backend/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- DataTable CDN -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/backend/assets/css/style.css" rel="stylesheet">

    <style>
        #login_banner {
            background-image: url({{ '/backend/assets/img/banner.jpg' }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            opacity: 0.7;
        }
    </style>

</head>
