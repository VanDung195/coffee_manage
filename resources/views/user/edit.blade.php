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
                <form action="{{ route('user.update') }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-row">
                        <div class="form-group col-12 col-md-2">
                            <label for="">Tên :</label>
                            <input type="text" name="name" id="" class="form-control" value="{{ $name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">Ngày sinh</label>
                            <input type="date" name="birthdate" id="" class="form-control" value="{{ $birthdate }}">
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">Số điện thoại</label>
                            <input type="text" name="phone" id="" class="form-control" value="{{ $phone }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <label for="">CCCD</label>
                            <input type="text" name="cccd" id="" class="form-control" value="{{ $cccd }}">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label for="">Địa chỉ</label>
                            <input type="text" name="address" id="" class="form-control" value="{{ $address }}">
                        </div>
                    </div>
                    <button class="btn btn-submit btn-light" style="width:100%;">Sửa</button>
                </form>
            </div> 
        </div>
    </div> 
</div>
@endsection
