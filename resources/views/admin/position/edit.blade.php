@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.positions.update') }}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="pos_id" value="{{ $position->id }}">
                     <div class="form-row">
                        <div class="form-group col-5">
                            <label for="name">Tên loại món</label>
                            <input type="text" name="pos_name" id="position-name" class="form-control" value="{{ $position->name }}" required>
                        </div>
                        <div class="form-group col-2">
                            <label for="name">Số tiền (VD: 1 = 1.000vnđ)</label>
                            <input type="number" name="salary" id="position-salary" class="form-control" value="{{ $position->salary_divided }}" required>
                        </div>
                        <div class="form-group col-4">
                            <button class="btn btn-success" style="float: left; margin-top:29px;">Cập nhật thông tin chức vụ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection