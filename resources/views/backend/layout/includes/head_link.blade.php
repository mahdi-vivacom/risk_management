<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <meta content="{{ config('app.name') }}" name="description">
    <meta content="{{ config('app.name') }}" name="keywords">
    <meta content="{{ config('app.name') }}" name="apple-mobile-web-app-title" />
    <title>{{ config('app.name') }} {{ !empty($title) ? '| ' . $title : '' }}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('/backend') . '/assets/img/hrm-favicon.png' }}"
        sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('/backend') . '/assets/img/hrm-favicon.png' }}" />
    <link rel="shortcut icon" href="{{ asset('/backend') . '/assets/img/favicon/hrm-favicon.png' }}" />
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('/backend') . '/assets/img/favicon/hrm-favicon.png' }}" />
    <link rel="manifest" href="{{ config('app.url') }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/backend/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/backend/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/backend/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/backend/assets/vendor/remixicon/remixicon.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/backend/assets/css/style.css" rel="stylesheet">

    {{-- font-awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- vite --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Common CSS -->
    @include('backend.layout.includes.common_css')

    <!-- Custom CSS -->
    @yield('custom_css')

</head>
