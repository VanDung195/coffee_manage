@extends('layout_user.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <!-- start row -->
    <div class="row">
        <div class="col-sm-12">
            <!-- Profile -->
            <div class="card">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="media">
                                {{-- <span class="float-left m-2 mr-4">
                                    <img src="assets/images/users/avatar-2.jpg" style="height: 100px;" alt="" class="rounded-circle img-thumbnail">
                                </span> --}}

                                <span class="float-left m-2 mr-4">
                                    <label for="filePhoto" style="cursor: pointer;" title="Click để thay đổi ảnh (kích thước ảnh tối đa 512KB)">
                                        <img src="assets/images/users/avatar-2.jpg" style="height: 100px;" alt="" class="rounded-circle img-thumbnail" id="profile-img">
                                    </label>
                                    <form id="formUpLoadPhoto" style="display:none">
                                        @csrf
                                        <input type="file" name="file_photo" id="file-photo" accept="image/x-png,image/gif,image/jpeg">
                                    </form>
                                </span>


                                <div class="media-body">

                                    <h4 class="my-1">{{ $name }}</h4>
                                    <p class="font-15 text-muted"> {{ $role_name }}</p>

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
                                {{-- <form action="{{ route('user.edit') }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button class="btn btn-light">
                                        <i class="mdi mdi-account-edit mr-1"></i> Edit Profile
                                    </button>
                                </form> --}}

                                <a href="{{ route('user.edit') }}" class="btn btn-light">
                                    <i class="mdi mdi-account-edit mr-1"></i> Edit Profile
                                </a>

                                {{-- <button type="button" class="btn btn-light">
                                    <i class="mdi mdi-account-edit mr-1"></i> Edit Profile
                                </button> --}}
                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end row -->

                </div> <!-- end card-body/ profile-user-box-->
            </div><!--end profile/ card -->
        </div> <!-- end col-->
    </div>
    <!-- end row -->

    <!-- start row-->
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

                        <p class="text-muted"><strong>Ngày sinh :</strong> <span class="ml-2">{{ $birthdate }}</span>
                        </p>

                        <p class="text-muted"><strong>Giới tính :</strong> <span class="ml-2">{{ $gender }}</span>
                        </p>

                        <p class="text-muted"><strong>Chức vụ :</strong> <span class="ml-2">{{ $role_name }}</span></p>

                        <p class="text-muted"><strong>Ca làm việc :</strong> <span class="ml-2">{{ $shift }}</span>
                        </p>

                        <p class="text-muted"><strong>Số điện thoại :</strong><span
                                class="ml-2">{{ $phone }}</span></p>

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

        <div class="col-lg-8">
            <!-- start card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Bảng lương</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Từ ngày</th>
                                    <th>Đến ngày</th>
                                    <th>Số giờ</th>
                                    <th>Số ngày làm</th>
                                    <th>Vắng</th>
                                    <th>Số tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salary_information as $item)
                                    <tr>
                                        <th>
                                            <a>{{ $item->id }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->created_at_formatted }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->payroll_date_formatted }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->work_hours }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->working_number }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->absent_number }}</a>
                                        </th>
                                        <th>
                                            <a>{{ $item->salary_formatted }}</a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end table responsive-->
                </div> <!-- end col-->
            </div>
            <!-- end card -->
        </div>
    </div>
    <!-- end row -->
@endsection
@push('js')
    <script>
        /*
        $(document).ready(function () {
            $('#profile-img').on('click', function() {
                $('#file-photo').click();
            })

            $('#file-photo').on('change', function () {
                var file = this.files[0];
                if (file) {
                    // Kiểm tra kích thước ảnh (tối đa 512KB)
                    if (file.size > 512 * 1024) {
                        alert('Kích thước ảnh tối đa 512KB');
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile-img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });*/

        document.addEventListener('DOMContentLoaded', function () {
            // Lấy các phần tử cần thiết
            const fileInput = document.getElementById('file-photo');
            const profileImg = document.getElementById('profile-img');

            // Thêm sự kiện click cho label để mở hộp thoại chọn tệp
            profileImg.parentElement.addEventListener('click', function () {
                fileInput.click();
            });

            // Thêm sự kiện change cho input file để cập nhật ảnh khi người dùng chọn ảnh mới
            fileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    // Kiểm tra kích thước ảnh (tối đa 512KB)
                    if (file.size > 512 * 1024) {
                        alert('Kích thước ảnh tối đa 512KB');
                        return;
                    }
                    
                    // Hiển thị ảnh đã chọn
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        @if(session('error'))
            $(document).ready(function() {
                notifyError("{{ session('error') }}");
            });
        @endif
        @if(session('success'))
            $(document).ready(function() {
                notifySuccess("{{ session('success') }}");
            });
        @endif
    </script>
@endpush

{{-- <span class="float-left m-2 mr-4">
    <label for="filePhoto" style="cursor: pointer;" title="Click để thay đổi ảnh (kích thước ảnh tối đa 512KB)">
        <img src="assets/images/users/avatar-2.jpg" style="height: 100px;" alt="" class="rounded-circle img-thumbnail" id="profile-img">
    </label>
    <form id="formUpLoadPhoto" style="display:none">
        @csrf
        <input type="file" name="file_photo" id="file-photo" accept="image/x-png,image/gif,image/jpeg">
    </form>
</span> --}}