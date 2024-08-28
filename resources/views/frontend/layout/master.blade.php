<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <!-- ======= Head Link ======= -->
  @include('frontend.includes.head_link')
  <!-- End Head -->

<body>

  <main>
    <div>
      <!-- ======= Content ======= -->
      @yield('content')
      <!-- End Content -->
    </div>

  </main>
  <!-- End #main -->

  <!-- ======= Foot Link ======= -->
  @include('frontend.includes.foot_link')
  <!-- End Foot Link -->

</body>

</html>
