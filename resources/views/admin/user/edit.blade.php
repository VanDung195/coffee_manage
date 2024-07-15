@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.user.update') }}" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-row">
                            <div class="form-group col-2">
                                <label for="name">Tên nhân viên</label>
                                <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">
                            </div>
                            <div class="form-group col-2">
                                <label for="birthdate">Ngày sinh</label>
                                <input type="date" name="birthdate" id="birthdate" value="{{ $user->birthdate }}" class="form-control">
                            </div>
                            {{-- <div class="form-group col-1">
                                <label for="gender">Giới tính</label>
                                <input type="text" name="gender" id="gender" value="{{ $user->gender_name }}" class="form-control">
                            </div> --}}
                            <div class="form-group col-2">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label for="adsress">Địa chỉ</label>
                                <input type="text" name="address" id="address" value="{{ $user->address }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-2">
                                <label for="account">Tài khoản</label>
                                <input type="text" name="account" id="account" value="{{ $user->account }}" class="form-control" readonly>
                            </div>
                            <div class="form-group col-2">
                               <label for="role">Chức vụ</label>
                               {{-- <input type="text" name="role" id="role" value="{{ $user->role_second_name }}" class="form-control"> --}}
                               <select name="role" id="role" class="form-control">
                                    @foreach ($positions as $item)
                                        <option value="{{ $item->id }}" @if (($item->id) == $user->role) selected @endif>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                               </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="shift">Ca làm việc</label>
                                {{-- <input type="text" name="shift" id="shift" value="{{ $user->shift_id }}" class="form-control"> --}}
                                <select name="shift" id="shift" class="form-control">
                                    @foreach ($shifts as $item)
                                        <option value="{{ $item->id }}" @if ($item->id == $user->shift_id) selected @endif>
                                            {{ $item->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row col-2">
                                <button class="btn btn-success" style="width: 131px; height: 40px;margin-top:28px;margin-left: 15px;">Cập nhật</button>
                            </div>
                        </div>
                    </form>
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