<header id="header" class="header fixed-top d-flex align-items-center">

    <style>
        .logo img {
            max-height: 155px;
            margin-right: 6px;
        }

        .sidebar {
            background-color: #18E365;
        }

        .sidebar-nav .nav-link:hover {
            color: #FFFFFF;
            background: #11a248;
        }

        .sidebar-nav .nav-link i {
            color: #FFFFFF;
        }

        .sidebar-nav .nav-link {
            background: #18e365;
            color: #FFFFFF;
        }

        .sidebar-nav .nav-content a {
            color: #FFFFFF;
        }

        .sidebar-nav .nav-content a:hover {
            color: #FFFFFF;
        }

        .sidebar-nav .nav-link:hover i {
            color: #FFFFFF;
        }

        .sidebar-nav .nav-link.collapsed {
            color: #FFFFFF;
            background: #18e365;
        }

        .sidebar-nav .nav-link.collapsed i {
            color: #ffffff;
        }

        .active-menu {
            background: red;
        }
    </style>
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            {{-- <img style="height: 45px;" class="d-block d-lg-none" src="{{ asset('/backend') . '/assets/img/favicon.ico' }}"
                alt="{{ config('app.name') }}"> --}}
            <img class="d-none d-lg-block full-logo" src="{{ asset('/backend') . '/assets/img/hrm_logo.png' }}"
                alt="{{ config('app.name') }}">
            {{-- <span class="d-none d-lg-block">{{ config('app.name') }}</span> --}}
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ !empty(auth()->user()->profile_image) ? asset(auth()->user()->profile_image) : asset('/backend/assets/img/profile_image.jpg') }}"
                        alt="Profile-Image" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ auth()->user()->name }}</h6>
                        <span>{{ auth()->user()->email }}</span><br>
                        <span><strong>{{ auth()->user()->roles->pluck('display_name')[0] }}</strong></span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>



                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
