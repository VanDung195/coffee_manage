@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="" class="btn btn-success">Thêm bàn</a>
                <a href="" class="btn btn-success">Thêm bàn (takeaway)</a>
                <a href="" class="btn btn-success">Thêm bàn (unknow)</a>
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
                            <tr>
                                <td>
                                    <a>{{ $table->stt }}</a>
                                </td>
                                <td>
                                    <a>{{ $table->name }}</a>
                                </td>
                                <td>
                                    <a>{{ $table->floor }}</a>
                                </td>
                                <td>
                                    <form action="" method="POST" style="margin: 0px;"> 
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-success">Sửa bàn</button>
                                    </form>
                                </td>
                                <td>
                                    <button data-pos-id="{{ $table->name }}" class="btn-delete btn btn-danger">Xoá bàn</button>
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
                    <input type="hidden" name="pos_id" id="pos-id">
                    <h4 class="center">Nếu bạn muốn xoá loại món này thì bạn phải xoá tất cả các món có liên quan đến loại món này. Tiếp tục xoá?</h4>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel btn btn-danger">Huỷ</button>
                <button class="btn-submit-form btn btn-success">Tôi đã xoá!</button>
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
        $(document).ready(function () {
            $('.btn-submit-form').click(function(){
                $('#modal-accept').modal('toggle');
                setTimeout(()=> {
                    $('#form-accept').submit();
                }, 400);
            })
        });
    </script>
@endpush