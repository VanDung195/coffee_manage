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
                                <th>Họ tên</th>
                                <th>Sửa tên loại món</th>
                                <th>Xoá loại món</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $item)
                                <tr>
                                    <td>
                                        <a>{{ $item->id }}</a>
                                    </td>
                                    <td>
                                        <a>
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{-- <form action="{{ route('admin.menu_categories.edit', $item->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <button class="btn btn-success">Sửa</button>
                                        </form> --}}
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
                        <h4 class="center">Có chắc xoá món này chứ?</h4>
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
        $('.btn-delete').on('click', function(){
            let menu_category_id = $(this).data('menu-category-id');
            $('#item-id').val(menu_category_id);
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