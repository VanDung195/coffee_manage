<html lang="en"><head>
    <meta charset="utf-8">
    <title>Log In | HAHAHAHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="assets/images/favicon.ico"> --}}

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
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="assets/images/logo.png" alt="" height="18"></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Đổi mật khẩu</h4>
                                <p class="text-muted mb-4">(Tối thiểu: 8, tối đa: 15 ký tự)</p>
                            </div>

                            <form action="{{ route('reset') }}" method="POST" id="reset-password">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="password">Nhập mật khẩu cũ</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="old_password" id="old-password" class="form-control" placeholder="Mật khẩu hiện tại" required>
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="old-password-error" class="text-danger">
                                        @if ($errors->has('old_password'))
                                            {{ $errors->first('old_password') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="password">Nhập mật khẩu mới</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="new_password" id="new-password" class="form-control" placeholder="Mật khẩu mới" required>
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="new-password-error" class="text-danger">
                                        @if ($errors->has('new_password'))
                                            {{ $errors->first('new_password') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="password">Nhập lại mật khẩu mới</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="confirm_password" id="confirm-password" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="confirm-password-error" class="text-danger">
                                        @if ($errors->has('confirm_password'))
                                            {{ $errors->first('confirm_password') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-submit btn-primary" type="submit" disabled> Đổi mật khẩu </button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Hello<a href="" class="text-muted ml-1"><b>World</b></a></p>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-alt">
        Reset password
    </footer>
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>    
    <script>
        $(document).ready(function () {
            function validatePasswords() {
                let old_password = $('#old-password').val();
                let new_password = $('#new-password').val();
                let confirm_password = $('#confirm-password').val();
                let hasError = false;
                if (new_password === old_password) {
                    $('#new-password-error').html('Mật khẩu mới không được trùng với mật khẩu cũ');
                    hasError = true;
                } else {
                    $('#new-password-error').html('');
                }

                if (confirm_password && confirm_password !== new_password) {
                    $('#confirm-password-error').html('Mật khẩu mới không khớp. Nhập lại!');
                    hasError = true;
                } else {
                    $('#confirm-password-error').html('');
                }
                $('.btn-submit').attr('disabled', hasError);
            }
            $('#new-password').on('keyup', validatePasswords);
            $('#confirm-password').on('keyup', validatePasswords);
        });
    </script>
</body></html>