@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.positions.create') }}" class="btn btn-success">Thêm chức vụ</a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>Tên chức vụ</th>
                            <th>Lương (1 giờ)</th>
                            <th>Sửa chức vụ</th>
                            <th>Xoá chức vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positions as $position)
                            <tr id="tr-position-id-{{ $position->id }}">
                                <td>
                                    <a>{{ $position->id }}</a>
                                </td>
                                <td>
                                    <a>{{ $position->name }}</a>
                                </td>
                                <td>
                                    <a>{{ $position->salary_formatted }}</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.positions.edit', $position->id) }}" method="POST" style="margin: 0px;"> 
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-success">Sửa chức vụ</button>
                                    </form>
                                </td>
                                <td>
                                    <button data-pos-id="{{ $position->id }}" class="btn-delete btn btn-danger">Xoá</button>
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
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xoá chức vụ</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{-- <form action="{{ route('admin.positions.destroy') }}" method="POST" id="form-accept">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="pos_id" id="pos-id">
                    <h4 class="center">Nếu bạn muốn xoá loại món này thì bạn phải xoá tất cả các món có liên quan đến loại món này. Tiếp tục xoá?</h4>
                </form> --}}
                <input type="hidden" name="pos_id" id="pos-id">
                <h4 class="center">Hãy đảm bảo không còn nhân viên nào thuộc chức vụ này! Xác nhận?</h4>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel btn btn-danger">Huỷ</button>
                <button class="btn-accept btn btn-success">Tôi đã xoá!</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $('.btn-delete').on('click', function(){
            let pos_id = $(this).data('pos-id');
            $('#pos-id').val(pos_id);
            $('#modal-accept').modal('show');
        })
        $('.btn-cancel').on('click', function() {
            $('#modal-accept').modal('toggle');
        })
        $(document).ready(function () {
            // $('.btn-submit-form').click(function(){
            //     $('#modal-accept').modal('toggle');
            //     setTimeout(()=> {
            //         $('#form-accept').submit();
            //     }, 400);
            // })
            $('.btn-accept').on('click', function(){
                let pos_id = $('#pos-id').val();
                $.ajax({
                    type: "delete",
                    url: '{{ route('admin.positions.destroy') }}',
                    data: {
                        pos_id: pos_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        let id = response.data.pos_id;
                        $.toast({
                            heading: 'Thành công',
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        $('#tr-position-id-'+id).remove();
                        $('#modal-accept').modal('toggle');
                    },
                    error: function(error) {
                        $.toast({
                            heading: 'Lỗi',
                            text: error.responseJSON.message,
                            showHideTransition: 'fade',
                            icon: 'error'
                        })
                    }
                });
            })
        });
        function notifyError(error)
        {
            $.NotificationApp.send("Error",error,"bottom-left","red","Icon")
        }
        function notifySuccess(success)
        {
            $.NotificationApp.send("Success",success,"bottom-left","green","Icon")
        }
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