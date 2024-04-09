<div class="left-side-menu mm-show">
    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="" alt="" height="16">
        </span>
    </a>
    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="" alt="" height="16">
        </span>
    </a>
    <div class="h-100 mm-active" id="left-side-menu-container" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">

        <!--- Sidemenu -->
        <ul class="metismenu side-nav mm-show">
            <li class="side-nav-title side-nav-item">Manager</li>
            <li class="side-nav-item">
                <a href="{{route('table')}}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Table </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Điểm danh nhân viên </span>
                </a>
            </li>
            @if (user()->role === 1 || user()->role === 2 && user())
                <li class="side-nav-item">
                    <a href="{{ route('admin.user.index') }}" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> User </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="#" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Món </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="#" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Hoá đơn </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
                        <i class="uil-store"></i>
                        <span> Thống kê doanh thu </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse" aria-expanded="false" style="height: 0px;">
                        <li>
                            <a href="{{ route('admin.statistic.day') }}">Thống kê theo ngày</a>
                        </li>
                        <li>
                            <a href="#">Thống kê theo tháng</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 60px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div></div></div>
    <!-- Sidebar -left -->
</div>