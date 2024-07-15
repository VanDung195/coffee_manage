@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">    <!-- chia thành 12 cột -->
        <div class="card">
            <div class="card-header">
                <form id="form-inline" class="form-inline">
                    <div class="form-group">
                        <label>Chức vụ</label>
                        <div class="col-4">
                            <select name="role" id="role" class="form-control select-filter">
                                <option value="" selected>ALL</option>
                                @foreach ($role as $name => $id)
                                    <option value="{{ $id }}" @if ((string)$id == $selected_role) selected @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ca làm việc</label>
                        <div class="col-4">
                            <select name="shift_id" id="shift_id" class="form-control select-filter">
                                <option value="" selected>ALL</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}" @if ((string)$shift->id == $selected_shift) selected @endif>
                                        {{ $shift->description }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                <a href="{{ route('register') }}" class="btn btn-success">Thêm nhân viên</a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> <!--vì sao lại dùng tHead các thứ thì sau này dùng mấy cái thư viện gì đó thì hai cái T này quan trọng vãi-->
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>CCCD</th>
                            <th>Số điện thoại</th>
                            <th>Chức vụ</th>
                            <th>Ca làm việc</th>
                            <th>Sửa</th>
                            <th>Xoá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{route("admin.user.show", $user)}}">
                                        {{$user->id}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->name}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->birthdate_name}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->cccd_name}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->phone}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{$user->role_name_second}}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{ $user->shift_id }}
                                    </a>
                                </td>
                                <td>
                                    {{-- <button class="btn btn-success">
                                        Sửa
                                    </button> --}}
                                    <form action="{{ route('admin.user.edit', $user->id) }}" method="POST" style="margin: 0px;">
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-success">Sửa</button>
                                    </form>
                                </td>
                                <td>
                                    <button data-user-id="{{ $user->id }}" class="btn-delete btn btn-danger">Xoá nhân viên</button>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    
<div id="modal-accept" class="modal fade" role="dialog" tabindex="-1"> 
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route("admin.user.destroy") }}" method="POST" id="form-accept">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="user_id" id="user-id">
                    <h4 class="center">Có chắc xoá nhân viên này chứ?</h4>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger">Huỷ</button>
                <button class="btn-submit-form btn btn-success">Có chứ!</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('.select-filter').change(function () {
                $('#form-inline').submit()
            })

            $('.btn-submit-form').click(function(){
                $('#modal-accept').modal('toggle');
                setTimeout(()=> {
                    $('#form-accept').submit();
                }, 400);
            })
        });

        $('.btn-delete').on('click', function(){
            let user_id = $(this).data('user-id');
            $('#user-id').val(user_id);
            $('#modal-accept').modal('show');
        })
    </script>
@endpush