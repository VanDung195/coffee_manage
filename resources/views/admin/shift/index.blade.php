@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="{{ route('admin.tables.create') }}" class="btn btn-success">Thêm bàn</a> --}}
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>Số giờ</th>
                            <th>Mô tả</th>
                            <th>Sửa ca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shifts as $shift)
                            <tr>
                                <td>
                                    <a>{{ $shift->id }}</a>
                                </td>
                                <td>
                                    <a>{{ $shift->time }}</a>
                                </td>
                                <td>
                                    <a>{{ $shift->description }}</a>
                                </td>
                                <td>
                                    <button data-shift-id="{{ $shift->id }}" class="btn btn-open-modal btn-dark">Sửa ca</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="modal-edit" class="modal fade" role="dialog" tabindex="-1"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sửa ca làm việc</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="form-accept">
                    <input type="hidden" name="shift_id" id="shift-id">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-3">
                            <label>Số giờ</label>
                            <input type="number" name="time" id="time" class="form-control">
                        </div>
                        <div class="form-group col-9">
                            <label>Mô tả</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel btn btn-danger">Huỷ</button>
                <button class="btn-submit-form btn btn-success">Chấp nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('.btn-open-modal').on('click', function() {
            let shift_id = $(this).data('shift-id');
            $.ajax({
                type: "get",
                url: '{{ route('admin.shifts.get_data') }}',
                data: {
                    id: shift_id
                },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    // console.log(response.data.shift.description);
                    let data = response.data.shift;
                    let id = data.id;
                    let time = data.time;
                    let description = data.description;

                    $('#shift-id').val(id);
                    $('#time').val(time);
                    $('#description').val(description);

                    $('#modal-edit').modal('show');
                }
            });
        })
    });
</script>
@endpush