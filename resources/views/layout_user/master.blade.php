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
                @include('layout_user.topbar')
                <!-- end Topbar -->
                
                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                    
                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            @include('layout_user.footer')
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