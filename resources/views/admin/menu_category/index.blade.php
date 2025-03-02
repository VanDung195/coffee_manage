@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.menu_categories.create') }}" class="btn btn-success">Thêm loại món</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên loại món</th>
                                <th>Sửa tên loại món</th>
                                <th>Xoá loại món</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $item)
                                <tr id="{{ $item->id }}">
                                    <td>
                                        <a>{{ $item->id }}</a>
                                    </td>
                                    <td>
                                        <a>
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.menu_categories.edit', $item->id) }}" method="POST" style="margin: 0px;">
                                            @csrf
                                            @method('put')
                                            <button class="btn btn-success">Sửa món</button>
                                        </form>
                                    </td>
                                    <td>
                                        <button data-menu-category-id="{{ $item->id }}" class="btn-delete btn btn-danger">Xoá</button>
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
                    <form action="{{ route("admin.menu_categories.destroy") }}" method="POST" id="form-accept">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="menu_category_id" id="item-id">
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
        function notify(type,message,icon,color)
        {
            $.toast({
                heading: type,
                text: message,
                icon: icon,
                loader: true,
                loaderBg: color
            })
        }
        // #FF0000 'info'
        $('.btn-delete').on('click', function(){
            let menu_category_id = $(this).data('menu-category-id');
            $('#item-id').val(menu_category_id);
            $('#modal-accept').modal('show');
        })
        $('.btn-cancel').click(function(){
            $('#modal-accept').modal('toggle');
        });
        $(document).ready(function () {
            $('.btn-submit-form').click(function(){
                let item_id = $('#item-id').val();
                $.ajax({
                    type: "delete",
                    url: '{{ route('admin.menu_categories.destroy') }}',
                    data: {
                        menu_category_id: item_id
                    },
                    dataType: "json",
                    success: function (response) {
                        document.getElementById(response.data.id).remove();
                        $('#modal-accept').modal('toggle');
                        notify('Thành công,',response.message,'success','#FF0000');
                    },
                    error: function(error)
                    {
                        notify('Lỗi,',error.responseJSON.message,'warning','#FF0000');
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
