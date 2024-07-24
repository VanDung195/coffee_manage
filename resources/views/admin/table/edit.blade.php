@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" method="POST" id="form-create-menu-item">
                    @csrf
                    @method('put')
                    <input type="hidden" name="table_id" id="table-id" value="{{ $table->id }}">
                     <div class="form-row">
                        <div class="form-group col-1">
                            <label for="">Tầng</label>
                            <select name="floor" id="floor" class="form-control">
                                <option value="1" @if ($table->floor == 1) selected @endif>1</option>
                                <option value="2" @if ($table->floor == 2) selected @endif>2</option>
                                <option value="3" @if ($table->floor == 3) selected @endif>3</option>
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <label for="name">Tên bàn: (VD: T1_2)</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $table->name }}" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-submit btn-success" style="float: left; margin-top:27px; margin-left:10px;">Sửa bàn</button>
                        </div>
                        <div class="form-group">
                            {{-- <button class="btn btn-danger" style="float: left; margin-top:27px;; margin-left:10px;">Huỷ</button> --}}
                            <a class="btn btn-danger" style="float: left; margin-top:27px;; margin-left:10px;" href="{{ route('admin.tables.index') }}">Huỷ</a>
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
            let table_id = $('#table-id').val();
            $.ajax({
                type: "put",
                url: '{{ route('admin.tables.update') }}',
                data: {
                    name: name,
                    floor: floor,
                    table_id: table_id,
            },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $.toast({
                        heading: 'Thành công',
                        text: response.message,
                        showHideTransition: 'slide',
                        icon: 'success'
                    })

                    let routeUrl = '{{ route('admin.tables.index') }}';
                    setTimeout(()=> {
                        window.location.href = routeUrl;
                    }, 3000);
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
    });
</script>
@endpush