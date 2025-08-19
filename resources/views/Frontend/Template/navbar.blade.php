<nav class="navbar" style="background: linear-gradient(45deg, #A7B7BA, #4B5354)">
    <!-- Logo Area -->
    <div class="navbar-header">
        <a href="{{ route('frontend.dashboard') }}" class="navbar-brand">
            <img class="logo-expand" alt="" src="/assets/img/logo-simple-new.png">
            <img class="logo-collapse" alt="" src="/assets/img/logo-bpad.png">
            <!-- <p>BonVue</p> -->
        </a>
    </div>
    <!-- /.navbar-header -->
    <!-- Left Menu & Sidebar Toggle -->
    <ul class="nav navbar-nav">
        <li class="sidebar-toggle dropdown"><a href="javascript:void(0)" class="ripple"><i
                    class="feather feather-menu list-icon fs-20"></i></a>
        </li>
    </ul>
    <!-- /.navbar-left -->
    <!-- /.navbar-search -->
    <div class="spacer"></div>
    <!-- /.navbar-right -->
    <!-- User Image with Dropdown -->
    <ul class="nav navbar-nav align-items-center">
        @php
            $user = session('user');
        @endphp

        {{-- Teks di sebelah gambar avatar --}}
        <li class="d-flex align-items-center mr-2">
            <div class="text-right pr-2">
                <strong class="d-block text-white">{{ $user->nalok ?? $user->skpd }}</strong>
                <small class="text-white-50">{{ $user->usname ?? $user->nrk_emp }}</small>
            </div>
        </li>

        {{-- Gambar avatar dan dropdown --}}
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle dropdown-toggle-user ripple" data-toggle="dropdown">
                <span class="avatar thumb-xs2">
                    <img src="/assets/img/default-avatar.jpg" class="rounded-circle" alt="">
                    <i class="feather feather-chevron-down list-icon"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
                <div class="card">
                    <ul class="list-unstyled card-body">
                        <li><a href="{{ route('frontend.profile') }}"><span class="align-middle">Profile</span></a></li>
                        <li><a href="{{ route('logout') }}"><span class="align-middle">Sign Out</span></a></li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    <!-- /.navbar-nav -->
</nav>
