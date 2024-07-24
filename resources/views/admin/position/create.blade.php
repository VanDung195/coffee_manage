@extends('layout.master')
@push('css')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

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
                    <form class="form-horizontal" action="{{ route('admin.positions.store') }}" method="POST" id="form-create-menu-item">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="name">Tên chức vụ</label>
                                <input type="text" name="position_name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group col-2">
                                <label for="price">Lương 1 giờ</label>
                                <input type="number" name="price" id="price" placeholder="VD: 2 = 2.000vnđ" class="form-control" required>
                            </div>
                            <div class="form-group col-3">
                                <button class="btn btn-success" style="margin-top:30px;margin-left:10px;">Thêm món</button>
                            </div>
                        </div>
                        <p style="font-size:17px;">Lưu ý: Không được thêm chức vụ một cách bừa bãi!!!</p>
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
        @if(session('error'))
            $(document).ready(function() {
                notifyError("{{ session('error') }}");
            });
        @endif
    </script>
@endpush