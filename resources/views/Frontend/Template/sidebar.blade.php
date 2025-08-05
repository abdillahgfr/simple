<!-- SIDEBAR -->
<aside class="site-sidebar scrollbar-enabled" data-suppress-scroll-x="true">
    <!-- User Details -->
    <div class="side-user d-none">
        <div class="col-sm-12 text-center p-0 clearfix">
            <div class="d-inline-block pos-relative mr-b-10">
                <figure class="thumb-sm mr-b-0 user--online">
                    <img src="/assets/demo/users/user1.jpg" class="rounded-circle" alt="">
                </figure><a href="page-profile.html" class="text-muted side-user-link"><i class="feather feather-settings list-icon"></i></a>
            </div>
            <!-- /.d-inline-block -->
            <div class="lh-14 mr-t-5"><a href="page-profile.html" class="hide-menu mt-3 mb-0 side-user-heading fw-500">Scott Adams</a>
                <br><small class="hide-menu">Developer</small>
            </div>
        </div>
        <!-- /.col-sm-12 -->
    </div>
    <!-- /.side-user -->
    <!-- Sidebar Menu -->
    <nav class="sidebar-nav">
        <ul class="nav in side-menu">
            <li class="current-page {{ request()->routeIs('frontend.dashboard') ? 'active' : '' }}">
                <a href="{{ route('frontend.dashboard') }}">
                    <i class="list-icon feather feather-command"></i> <span class="hide-menu">Dashboard</span>
                </a>
            </li>
             <li class="menu-item-has-children current-page {{ request()->routeIs('frontend.identifikasiaset') ? 'active' : '' }}"><a href="javascript:void(0);"><i class="list-icon feather feather-briefcase"></i> <span class="hide-menu">Identifikasi Aset Idle</a>
                <ul class="list-unstyled sub-menu">
                    <li><a href="{{ route('frontend.identifikasiaset') }}">KIB B</a>
                    </li>
                </ul>
            </li>
            <li class="menu-item-has-children current-page {{ request()->routeIs('frontend.validasiaset') ? 'active' : '' }}"><a href="javascript:void(0);"><i class="list-icon feather feather-briefcase"></i> <span class="hide-menu">Validasi Aset Idle</a>
                <ul class="list-unstyled sub-menu">
                    <li><a href="{{ route('frontend.validasiaset') }}">KIB B</a>
                    </li>
                </ul>
            </li>
            <li class="menu-item-has-children current-page {{ request()->routeIs('frontend.asetidle') ? 'active' : '' }}"><a href="javascript:void(0);"><i class="list-icon feather feather-briefcase"></i> <span class="hide-menu">Display Aset Idle</a>
                <ul class="list-unstyled sub-menu">
                    <li><a href="{{ route('frontend.asetidle') }}">KIB B</a>
                    </li>
                </ul>
            </li>
            <li class="menu-item-has-children"><a href="javascript:void(0);"><i class="list-icon feather feather-briefcase"></i> <span class="hide-menu">Permohonan Aset Idle</a>
                <ul class="list-unstyled sub-menu">
                    <li><a href="../default/app-calender.html">Daftar Permohonan</a>
                    </li>
                    <li><a href="../default/app-chat.html">BMD Dimohon</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- /.side-menu -->
    </nav>
    <!-- /.sidebar-nav -->
</aside>
<!-- /.site-sidebar -->