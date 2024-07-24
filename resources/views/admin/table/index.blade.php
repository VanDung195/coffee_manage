@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.tables.create') }}" class="btn btn-success">Thêm bàn</a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>STT</th>
                            <th>Tên bàn</th>
                            <th>Tầng</th>
                            <th>Sửa bàn</th>
                            <th>Xoá xoá bàn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tables as $table)
                            <tr id="tr-table-id-{{ $table->id }}">
                                <td>
                                    <a>{{ $table->id }}</a>
                                </td>
                                <td>
                                    <a>{{ $table->name }}</a>
                                </td>
                                <td>
                                    <a>{{ $table->floor }}</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.tables.edit', $table->id) }}" method="POST" style="margin: 0px;"> 
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-success">Sửa bàn</button>
                                    </form>
                                </td>
                                <td>
                                    <button data-table-id="{{ $table->id }}" class="btn-delete btn btn-danger">Xoá bàn</button>
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
                <h4 class="modal-title">Xác nhận</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="form-accept">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="table_id" id="table-id">
                    <h4 class="center">Xác nhận?</h4>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel btn btn-danger">Huỷ</button>
                <button class="btn-submit-form btn btn-success">Chấp nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $('.btn-delete').on('click', function(){
            let table_id = $(this).data('table-id');
            $('#table-id').val(table_id);
            $('#modal-accept').modal('show');
        })
        $('.btn-cancel').on('click', function(){
            $('#modal-accept').modal('toggle');
        })
        $(document).ready(function () {
            $('.btn-submit-form').click(function(){
                let table_id = $('#table-id').val();
                $.ajax({
                    type: "delete",
                    url: '{{ route('admin.tables.destroy') }}',
                    data: {
                        table_id: table_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        let id = response.data.id;
                        $.toast({
                            heading: 'Thành công',
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        $('#tr-table-id-'+id).remove();
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