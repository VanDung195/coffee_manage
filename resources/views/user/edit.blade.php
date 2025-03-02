@extends('layout_user.master')
@push('css')
    <style>
        /* Giao diện cho màn hình lớn (máy tính) */
        @media (min-width: 768px) {
            .form-row .form-group {
                margin-bottom: 1rem;
            }
        }

        /* Giao diện cho màn hình nhỏ (điện thoại di động) */
        @media (max-width: 767px) {
            .form-row {
                display: flex;
                flex-direction: column;
            }
            .form-row .form-group {
                width: 100%;
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Profile | Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body profile-user-box">
                <form action="{{ route('user.update') }}" method="POST" id="update-user-form">
                    @csrf
                    @method('put')
                    <div class="form-row">
                        <div class="form-group col-12 col-md-2">
                            <label for="">Tên :</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $name }}">
                            <span id="name-error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">Ngày sinh</label>
                            <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $birthdate }}">
                            <span id="birthdate-error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $phone }}">
                            <span id="phone-error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">CCCD</label>
                            <input type="text" name="cccd" id="cccd" class="form-control" value="{{ $cccd }}">
                            <span id="cccd-error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label for="">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ $address }}">
                            <span id="address-error" class="text-danger"></span>
                        </div>
                    </div>
                    <button class="btn btn-submit btn-light" style="width:100%;">Sửa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#update-user-form').on('submit', function(event) {
                event.preventDefault();
                let form = $(this);
                let url = form.attr('action');

                $.ajax({
                    type: "put",
                    url: url,
                    data: form.serialize(),
                    // dataType: "dataType",
                    success: function (response) {
                        if (response.success) {
                            $('.text-danger').html('');
                            window.location.href = '{{ route('user.index') }}';
                        }
                    },
                    error: function(xhr) {
                        // console.log(xhr);
                        var errors = xhr.responseJSON.errors;
                        $('.text-danger').html('');
                        for (var field in errors) {
                            var errorHtml = errors[field][0];
                            $('#'+field+'-error').html(errorHtml);
                        }
                    }
                });
            })
        });
    </script>
@endpush
