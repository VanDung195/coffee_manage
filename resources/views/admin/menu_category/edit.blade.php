@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.menu_categories.update') }}" method="POST" id="form-create-menu-category">
                    @csrf
                    @method('put')
                    <input type="hidden" name="menu_category_id" value="{{ $category->id }}">
                     <div class="form-row">
                        <div class="form-group col-5">
                            <label for="name">Tên loại món</label>
                            <input type="text" name="name" id="menu-category-name" class="form-control" value="{{ $category->name }}">
                        </div>
                    </div>
                    <button class="btn btn-success" style="float: left;">Cập nhật loại món</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection