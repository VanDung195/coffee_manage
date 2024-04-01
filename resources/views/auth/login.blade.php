<html lang="en"><head>
    <meta charset="utf-8">
    <title>Log In | HAHAHAHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css">

</head>

<body class="authentication-bg" data-layout-config="{&quot;darkMode&quot;:false}" data-leftbar-compact-mode="condensed">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="assets/images/logo.png" alt="" height="18"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Đăng nhập vào hệ thống</h4>
                                <p class="text-muted mb-4">Nhập tài khoản và mật khẩu</p>
                            </div>

                            <form action="{{ route('process_login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="account">Tài khoản</label>
                                    <input class="form-control" id="account" name="account" required="" placeholder="Nhập tài khoản của mày">
                                </div>

                                <div class="form-group">
                                    <a href="pages-recoverpw.html" class="text-muted float-right"><small>Quên mật khẩu? Hãy gọi cho quản lý</small></a>
                                    <label for="password">Mật khẩu</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu của mày">
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Thằng nào vào đây? <a href="" class="text-muted ml-1"><b>Méo cho signup đâu, thoát đê</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        Trang đăng nhập
    </footer>

    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>    
    


</body></html>