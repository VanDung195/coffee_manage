@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">    <!-- chia thành 12 cột -->
        <div class="card">
            <div class="card-header">
                <form id="form-inline" action="">
                    <div class="form-group">
                        <label>Loại món</label>
                        <div class="col-4">
                            <select name="category" id="category" class="form-control select-filter">
                                <option value="">ALL</option>
                                @foreach ($menu_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> <!--vì sao lại dùng tHead các thứ thì sau này dùng mấy cái thư viện gì đó thì hai cái T này quan trọng vãi-->
                        <tr>
                            <th>Mã món</th>
                            <th>Tên món</th>
                            <th>Giá bán</th>
                            <th>Sửa món</th>
                            <th>Xoá món</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menu_items as $item)
                            <tr>
                                <td>
                                    <a>
                                        {{ $item->id }}
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
                                    <button class="btn btn-success">
                                        Sửa
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-danger">
                                        Xoá
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('.select-filter').change(function(){
                $('#form-inline').submit();
            });
        });
    </script>
@endpush