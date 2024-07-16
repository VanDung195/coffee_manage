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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('admin.menu_categories.store') }}" method="POST" id="form-create-menu-item">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-4">
                                <input type="text" name="menu_category_name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group col-3">
                                <button class="btn btn-success" style="">Thêm món</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        
    </script>
@endpush