<html lang="en"><head>
    <meta charset="utf-8">
    <title>Profile | User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="" data-layout="topnav" data-layout-config="{&quot;layoutBoxed&quot;:false,&quot;darkMode&quot;:false,&quot;showRightSidebarOnStart&quot;: true}" data-leftbar-theme="dark">
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
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
                                        <span class="account-user-name">Dominic Keller</span>
                                        <span class="account-position">Founder</span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop" style="">
                                    <!-- item-->
                                    <div class=" dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome !</h6>
                                    </div>
                                    <!-- item-->
                                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout mr-1"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                        
                    </div>
                </div>
                <!-- end Topbar -->
                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                
                                <h4 class="page-title">Profile</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Profile -->
                            <div class="card">
                                <div class="card-body profile-user-box">

                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="media">
                                                <span class="float-left m-2 mr-4"><img src="assets/images/users/avatar-2.jpg" style="height: 100px;" alt="" class="rounded-circle img-thumbnail"></span>
                                                <div class="media-body">

                                                    <h4 class="my-1">{{ $name }}</h4>
                                                    <p class="font-13 text-muted"> {{ $role_name }}</p>

                                                    {{-- <ul class="mb-0 list-inline">
                                                        <li class="list-inline-item mr-3">
                                                            <h5 class="mb-1">$ 25,184</h5>
                                                            <p class="mb-0 font-13">Total Revenue</p>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <h5 class="mb-1">5482</h5>
                                                            <p class="mb-0 font-13">Number of Orders</p>
                                                        </li>
                                                    </ul> --}}
                                                </div> <!-- end media-body-->
                                            </div>
                                        </div> <!-- end col-->

                                        <div class="col-sm-4">
                                            <div class="text-center mt-sm-0 mt-3 text-sm-right">
                                                <button type="button" class="btn btn-light">
                                                    <i class="mdi mdi-account-edit mr-1"></i> Edit Profile
                                                </button>
                                            </div>
                                        </div> <!-- end col-->
                                    </div> <!-- end row -->

                                </div> <!-- end card-body/ profile-user-box-->
                            </div><!--end profile/ card -->
                        </div> <!-- end col-->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-lg-4">
                            <!-- Personal-Information -->
                            <div class="card">
                                <div class="card-body">
                                    {{-- <h4 class="header-title mt-0 mb-3">Seller Information</h4>
                                    <p class="text-muted font-13">
                                        Hye, I’m Michael Franklin residing in this beautiful world. 
                                        I create websites and mobile apps with great UX and UI design. 
                                        I have done work with big companies like Nokia, Google and Yahoo. 
                                        Meet me or Contact me for any queries. 
                                        One Extra line for filling space. 
                                        Fill as many you want.
                                    </p> --}}
                                    <h4 class="header-title mt-0 mb-3">Thông tin cá nhân</h4>
                                    <hr>
                                    <div class="text-left">
                                        <p class="text-muted"><strong>Họ tên :</strong> <span class="ml-2">{{ $name }}</span></p>

                                        <p class="text-muted"><strong>Chức vụ :</strong> <span class="ml-2">{{ $role_name }}</span></p>

                                        <p class="text-muted"><strong>Số điện thoại :</strong><span class="ml-2">{{ $phone }}</span></p>

                                        {{-- <p class="text-muted"><strong>Email :</strong> <span class="ml-2">coderthemes@gmail.com</span></p> --}}

                                        <p class="text-muted"><strong>Địa chỉ :</strong> <span class="ml-2">{{ $address }}</span></p>

                                        {{-- <p class="text-muted"><strong>Languages :</strong>
                                            <span class="ml-2"> English, German, Spanish </span>
                                        </p> --}}
                                        {{-- <p class="text-muted mb-0"><strong>MXH :</strong>
                                            <a class="d-inline-block ml-2 text-muted" title="" data-placement="top" data-toggle="tooltip" href="" data-original-title="Facebook"><i class="mdi mdi-facebook"></i></a>
                                            <a class="d-inline-block ml-2 text-muted" title="" data-placement="top" data-toggle="tooltip" href="" data-original-title="Twitter"><i class="mdi mdi-twitter"></i></a>
                                            <a class="d-inline-block ml-2 text-muted" title="" data-placement="top" data-toggle="tooltip" href="" data-original-title="Skype"><i class="mdi mdi-skype"></i></a>
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <!-- end row -->

                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            2018 - 2020 © Hyper - Coderthemes.com
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>

    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>    
    <script src="{{ asset('js/helper.js') }}"></script> 

</body>
</html>