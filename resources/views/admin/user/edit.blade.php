@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-3">
                            <label for="name">Tên nhân viên</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">
                        </div>
                        <div class="form-group col-1">
                            <label for="birthdate">Ngày sinh</label>
                            <input type="text" name="bithdate" id="birthdate" value="{{ $user->birthdate }}" class="form-control">
                        </div>
                        <div class="form-group col-1">
                            <label for="gender">Giới tính</label>
                            <input type="text" name="gender" id="gender" value="{{ $user->gender_name }}" class="form-control">
                        </div>
                        <div class="form-group col-2">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection







{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
            </div>
        </div>
    </div>
</div> --}}