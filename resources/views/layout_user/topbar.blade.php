<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="" class="topnav-logo">
            <span class="topnav-logo-lg">
                <img src="assets/images/logo-light.png" alt="" height="16">
            </span>
            <span class="topnav-logo-sm">
                <img src="assets/images/logo_sm_dark.png" alt="" height="16">
            </span>
        </a>
        <ul class="list-unstyled topbar-right-menu float-right mb-0">
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="account-user-avatar"> 
                        <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                        <span class="account-user-name">{{ user()->name }}</span>
                        <span class="account-position">{{ getRoleByValue(user()->role) }}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop" style="">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>
                    @if (user()->role === 1 || user()->role === 2 || user()->role === 3)
                        <a href="{{ route('table') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-table-chair"></i>
                            <span>Bàn</span>
                        </a>
                    @endif
                    <!-- item-->
                    <a href="{{ route('reset_password') }}" class="dropdown-item notify-item">
                        <i class="mdi dripicons-clockwise mr-1"></i>
                        <span>Đổi mật khẩu</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout mr-1"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>