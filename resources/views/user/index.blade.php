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

                                </div> <!-- end media-body-->
                            </div>
                        </div> <!-- end col-->

                        <div class="col-sm-4">
                            <div class="text-center mt-sm-0 mt-3 text-sm-right">

                                <a href="{{ route('user.edit') }}" class="btn btn-light">
                                    <i class="mdi mdi-account-edit mr-1"></i> Edit Profile
                                </a>

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
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('file-photo');
            const profileImg = document.getElementById('profile-img');

            profileImg.parentElement.addEventListener('click', function () {
                fileInput.click();
            });

            fileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.size > 512 * 1024) {
                        alert('Kích thước ảnh tối đa 512KB');
                        return;
                    }

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
