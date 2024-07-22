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
                    <form class="form-horizontal" method="POST" id="form-create-menu-item">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-2">
                                <label for="name">Tên bàn (VD: T2_9)</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="VD: T(Tầng)_(Số bàn)" required>
                            </div>
                            {{-- <div class="form-group col-1">
                                <label for="price">Tầng</label>
                                <input type="number" name="price" id="price" placeholder="VD: 2 = 2.000vnđ" class="form-control" required>
                            </div> --}}
                            <div class="form-group col-1">
                                <label for="">Chọn tầng</label>
                                <select name="floor" id="floor" class="form-control">
                                    <option value="1" selected>Tầng 1</option>
                                    <option value="2">Tầng 2</option>
                                    <option value="3">Tầng 3</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <button class="btn-submit btn btn-success" style="margin-top:30px;margin-left:10px;">Thêm bàn</button>
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
        $(document).ready(function () {
            $('.btn-submit').on('click', function(event){
                event.preventDefault()
                let name = $('#name').val();
                let floor = $('#floor').val();
                $.ajax({
                    type: "post",
                    url: '{{ route('admin.tables.store') }}',
                    data: {
                        name: name,
                        floor: floor,
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                }
            });
            })
            
        });
    </script>
@endpush