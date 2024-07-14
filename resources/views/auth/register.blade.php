<html lang="en"><head>
    <meta charset="utf-8">
    <title>Register - Sign Up | HAHAHAHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css">

</head>

<body class="authentication-bg" data-layout-config="{&quot;darkMode&quot;:false}">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">
                        <!-- Logo-->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="assets/images/logo.png" alt="" height="18"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Đăng ký tài khoản nhân viên</h4>
                                <p class="text-muted mb-4">Chào admin (quản lý)</p>
                            </div>

                            <form action="{{ route('process_register') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input class="form-control" type="text" id="fullname" name="name" placeholder="Nhập họ tên" required="">
                                </div>

                                <div class="form-group">
                                    <label for="emailaddress">Tài khoản</label>
                                    <input class="form-control" name="account" id="emailaddress" required="" placeholder="Nhập tên tài khoản">
                                </div>

                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Nhập mật khẩu (recommend: Như tài khoản)">
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="emailaddress">Số điện thoại</label>
                                    <input class="form-control" name="phone" id="phone" required="" placeholder="Nhập số điện thoại">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-5">
                                        <label for="emailaddress">Chọn chức vụ:</label>
                                        {{-- <input class="form-control" name="account" id="emailaddress" required="" placeholder="Enter your account"> --}}
                                        <select name="role" id="" class="form-control">
                                            @foreach ($roles as $key => $value)
                                                <option value="{{ $value }}">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-7">
                                        <label for="shift">Chọn ca:</label>
                                        <select name="shift" class="form-control">
                                            @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}">{{ $shift->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Đăng ký </button>
                                </div>
                                <div class="form-group mb-0 text-center" style="margin-top: 20px;">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-danger">Huỷ</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Already have account? <a href="pages-login.html" class="text-muted ml-1"><b>Log In</b></a></p>
                        </div> <!-- end col-->
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
        2013 - 2024 © HoVanDung - hahah.com
    </footer>

    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>    
    


</body></html>