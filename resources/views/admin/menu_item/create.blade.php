@extends('layout.master')
@push('css')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
@section('content')
    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('admin.menu_items.store') }}" method="POST" id="form-create-menu-item">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-2">
                                <label for="category">Loại món</label>
                                <select name="category" id="category" class="form-control">
                                    @foreach ($menu_categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach 
                                </select>
                            </div>
                            <div class="form-group col-5">
                                <label for="name">Tên món</label>
                                <input type="text" name="name" id="menu-item-name" class="form-control">
                            </div>
                            <div class="form-group col-2">
                                <label for="price">Giá</label>
                                <input type="number" name="price" id="price" placeholder="Ví dụ: 1 = 10.000đ, 19 = 19.000đ" class="form-control" inputmode="numeric">
                            </div>
                        </div>
                        <button class="btn btn-success" style="float: left;">Thêm món</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
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
        $('#form-create-menu-item').validate({
            rules: {
                name: {
                    required: true,
                }
            }
        });
    </script>
@endpush