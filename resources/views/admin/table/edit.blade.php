@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.menu_items.update') }}" method="POST" id="form-create-menu-item">
                    @csrf
                    @method('put')
                    <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                     <div class="form-row">
                        <div class="form-group col-2">
                            <label for="category">Loại món</label>
                            <select name="category" id="category" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ((int)$category->id == $item->menu_category->id ) selected @endif>
                                        {{ $category->name }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-5">
                            <label for="name">Tên món</label>
                            <input type="text" name="name" id="menu-item-name" class="form-control" value="{{ $item->name }}">
                        </div>
                        <div class="form-group col-2">
                            <label for="price">Giá (Ví dụ: 10 = 10.000vnđ)</label>
                            <input type="number" name="price" id="price" placeholder="Ví dụ: 1 = 10.000đ, 19 = 19.000đ" class="form-control" inputmode="numeric" value="{{ $item->price_for_edit }}">
                        </div>
                    </div>
                    <button class="btn btn-success" style="float: left;">Thêm món</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection