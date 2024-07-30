@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="{{ route('admin.tables.create') }}" class="btn btn-success">Thêm bàn</a> --}}
                <h3>Lưu ý: Hạn chế thay đổi số giờ của một ca làm việc.</h3>
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
                                    <a class="shift-id-{{ $shift->id }}">{{ $shift->id }}</a>
                                </td>
                                <td>
                                    <a class="time-{{ $shift->id }}">{{ $shift->time }}</a>
                                </td>
                                <td>
                                    <a class="description-{{ $shift->id }}">{{ $shift->description }}</a>
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
                <form action="" method="put" id="form-accept">
                    <input type="hidden" name="shift_id" id="shift-id">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-3">
                            <label>Số giờ</label>
                            <input type="number" name="time" id="time" class="form-control" required>
                        </div>
                        <div class="form-group col-9">
                            <label>Mô tả</label>
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel btn btn-danger">Huỷ</button>
                <button class="btn-submit-form btn btn-success" type="submit">Chấp nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        // $('.btn-open-modal').on('click', function() {
        //     let shift_id = $(this).data('shift-id');
        //     $.ajax({
        //         type: "get",
        //         url: '{{ route('admin.shifts.get_data') }}',
        //         data: {
        //             id: shift_id
        //         },
        //         dataType: "json",
        //         success: function (response) {
        //             // console.log(response);
        //             // console.log(response.data.shift.description);
        //             let data = response.data.shift;
        //             let id = data.id;
        //             let time = data.time;
        //             let description = data.description;

        //             $('#shift-id').val(id);
        //             $('#time').val(time);
        //             $('#description').val(description);

        //             $('#modal-edit').modal('show');
        //         }
        //     });
        // })
        $('.btn-cancel').on('click', function() {
            $('#modal-edit').modal('toggle');
        })

        $('.btn-open-modal').on('click', function() {
            let shift_id = $(this).data('shift-id');
            
            let id = $('.shift-id-'+shift_id).text();
            let time = $('.time-'+shift_id).text();
            let description = $('.description-'+shift_id).text();
            
            $('#shift-id').val(id);
            $('#time').val(time);
            $('#description').val(description);

            $('#modal-edit').modal('show');
        })

        $('.btn-submit-form').on('click', function(event) {
            event.preventDefault();

            let id = $('#shift-id').val();
            let time = $('#time').val();
            let description = $('#description').val();

            $.ajax({
                type: "put",
                url: '{{ route('admin.shifts.update') }}',
                data: {
                    id: id,
                    time: time,
                    description: description
                },
                dataType: "json",
                success: function (response) {
                    let shift_id = response.data.id;
                    let time = response.data.time;
                    let description = response.data.description;
                    let message = response.message;


                    $('.time-'+shift_id).text(time);
                    $('.description-'+shift_id).text(description);

                    $.toast({
                        heading: 'Thành công',
                        text: message,
                        showHideTransition: 'slide',
                        icon: 'success'
                    })

                    $('#modal-edit').modal('toggle');
                }, 
                error: function() {
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