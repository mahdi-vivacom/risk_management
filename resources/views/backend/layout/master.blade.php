<!DOCTYPE html>
<html lang="{{ str_replace ( '_', '-', app ()->getLocale () ) }}">

<!-- ======= Head Link ======= -->
@include( 'backend.layout.includes.head_link' )
<!-- End Head -->

<body>

<!-- ======= Header ======= -->
@include( 'backend.layout.includes.header' )
<!-- End Header -->

<!-- ======= Sidebar ======= -->
@include( 'backend.layout.includes.sidebar' )
<!-- End Sidebar -->

<main id="main" class="main">

    <!-- ======= Page Title ======= -->
    @include( 'backend.layout.includes.page_title' )
    <!-- End Page Title -->

    <!-- ======= Content ======= -->
    @yield( 'content' )
    <!-- End Content -->

</main>
<!-- End #main -->

<!-- ======= Footer ======= -->
@include( 'backend.layout.includes.footer' )
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- ======= Foot Link ======= -->
@include( 'backend.layout.includes.foot_link' )
<!-- End Foot Link -->

<!-- Common Scripts -->
@include( 'backend.layout.includes.common_scripts' )

<!-- Custom Scripts -->
@yield( 'custom_js' )

</body>

</html>
