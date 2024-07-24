@extends('layout.master')
@push('css')
    <style>
        .center {
            text-align: center;
        }
    </style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">    <!-- chia thành 12 cột -->
        <div class="card">
            <div class="card-header">
                <form id="form-inline" class="form-inline" action="">
                    <div class="form-group">
                        <label>Loại món</label>
                        <div class="col-4">
                            <select name="category" id="category" class="form-control select-filter">
                                <option selected value="">ALL</option>
                                @foreach ($menu_categories as $category)
                                    <option value="{{ $category->id }}" @if ((string)$category->id == $selected_category) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sort">Sắp xếp theo giá</label>
                        <div class="col-4">
                            <select name="sort" id="sort" class="form-control select-filter">
                                <option value="none" selected @if ($selected_sort == 'none') selected @endif>
                                    Mặc định
                                </option>
                                <option value="asc" @if ($selected_sort == 'asc') selected @endif>
                                    Giá tăng dần
                                </option>
                                <option value="desc" @if ($selected_sort == 'desc') selected @endif>
                                    Giá giảm dần
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
                <a href="{{ route('admin.menu_items.create') }}" class="btn btn-success">Thêm món</a>
                {{-- <button class="btn btn-danger" onclick="openmodal()">test</button> --}}
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>Mã món</th>
                            <th>Loại món</th>
                            <th>Tên món</th>
                            <th>Giá bán</th>
                            <th>Sửa món</th>
                            <th>Xoá món</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr id="tr-menu-item-id-{{ $item->id }}">
                                <td>
                                    <a>
                                        {{ $item->id }}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{ $item->menu_category->name }}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td>
                                    <a>
                                        {{ $item->price_vnd }} VNĐ
                                    </a>
                                </td>
                                <td>
                                    {{-- <button class="btn btn-success">
                                        Sửa
                                    </button> --}}
                                    {{-- <a href="{{ route('admin.menu_items.edit', $item->id) }}" class="btn btn-success">Sửa món</a> --}}
                                    <form action="{{ route('admin.menu_items.edit', $item->id) }}" method="POST" style="margin: 0px;"> 
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-success">Sửa món</button>
                                    </form>
                                </td>
                                <td class="data-item">
                                    {{-- <form action="{{ route("admin.menu_items.destroy", $item->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger">Delete</button>
                                    </form> --}}
                                    <button data-menu-item-id="{{ $item->id }}" class="btn-delete btn btn-danger">Xoá món</button>
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
                {{-- <form action="{{ route("admin.menu_items.destroy") }}" method="POST" id="form-accept">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="menu_item_id" id="item-id">
                    <h4 class="center">Hạn chế xoá món! Xác nhận?</h4>
                </form> --}}
                <input type="hidden" name="menu_item_id" id="menu-item-id">
                <h4 class="center">Xác nhận?</h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger">Huỷ</button>
                <button class="btn-accept btn btn-success">Có chứ!</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        // function openmodal()
        // {
        //     $('#modal-accept').modal('show');
        //     // $('#myModal').modal('toggle');
        //     // $('#myModal').modal('show');
        //     // $('#myModal').modal('hide');
        // }
        $('.btn-delete').on('click', function(){
            let menu_item_id = $(this).data('menu-item-id');
            $('#menu-item-id').val(menu_item_id);
            $('#modal-accept').modal('show');
        })
        $(document).ready(function () {
            $('.select-filter').change(function(){
                $('#form-inline').submit();
            });
            $('.btn-accept').on('click', function() {
                let menu_item_id = $('#menu-item-id').val();
                $.ajax({
                    type: "delete",
                    url: '{{ route('admin.menu_items.destroy') }}',
                    data: {
                        menu_item_id: menu_item_id,
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        let id = response.data.menu_item_id;
                        $.toast({
                            heading: 'Thành công',
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        $('#tr-menu-item-id-'+id).remove();
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
            // $('.btn-submit-form').click(function(){
            //     $('#modal-accept').modal('toggle');
            //     setTimeout(()=> {
            //         $('#form-accept').submit();
            //     }, 400);
            // })

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