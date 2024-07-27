@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="{{ route('admin.positions.create') }}" class="btn btn-success">Thêm chức vụ</a> --}}
                <form id="form-inline" class="form-inline">
                    <div class="form-group">
                        <label>Sắp xếp theo giá: </label>
                        <div class="col-4">
                            <select name="sort_total_price" id="sort-total-price" class="form-control select-filter">
                                <option value="none" selected @if ($selected_sort_total_price == 'none') selected @endif>
                                    Mặc định
                                </option>
                                <option value="asc" @if ($selected_sort_total_price == 'asc') selected @endif>
                                    Giá tăng dần
                                </option>
                                <option value="desc" @if ($selected_sort_total_price == 'desc') selected @endif>
                                    Giá giảm dần
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sắp xếp theo ngày: </label>
                        <div class="col-4">
                            <select name="sort_date" id="sort-date" class="form-control select-filter">
                                <option value="none" selected @if ($selected_sort_date == 'none') selected @endif>
                                    Mặc định
                                </option>
                                <option value="asc" @if ($selected_sort_date == 'asc') selected @endif>
                                    Cũ nhất trước
                                </option>
                                <option value="desc" @if ($selected_sort_date == 'desc') selected @endif>
                                    Mới nhất trước
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
                <h4>Lưu ý: Chỉ được chọn 1 trong 2 kiểu sắp xếp!!!</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>Ngày tạo</th>
                            <th>TG vào</th>
                            <th>TG ra</th>
                            <th>Tg.Tiền</th>
                            <th>K.hàng</th>
                            <th>Thừa</th>
                            <th>Bàn</th>
                            <th>Ng.tạo</th>
                            <th>Detail</th>
                            {{-- <th>Xoá</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <a>{{ $invoice->id }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->created_at_formatted }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->checkin_time }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->checkout_time }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->total_price_formatted }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->customer_payment_formatted }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->remaining_money_formatted }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->tables->name }}</a>
                                </td>
                                <td>
                                    <a>{{ $invoice->users->name }}</a>
                                </td>
                                <td>
                                    <button data-table-id="{{ $invoice->id }}" class="btn btn-view-detail btn-dark">Xem</button>
                                </td>
                                {{-- <td>
                                    <button class="btn btn-danger">Xoá</button>
                                </td> --}}
                            </tr>
                            <div id="modal-invoice-{{ $invoice->id }}">
                                <div id="invoice-detail-{{ $invoice->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hoá đơn chi tiết</h4>
                                                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <h3>Bàn số: {{ $invoice->tables->name }}</h3>
                                                <div class="form-row">
                                                    <div class="form-group col-2">
                                                        <label >Người tạo: </label>
                                                        <p class="form-control">{{ $invoice->users->name }}</p>
                                                    </div>
                                                    <div class="form-group col-2">
                                                        <label for="">Ngày tạo: </label>
                                                        <p class="form-control">{{ $invoice->created_at_formatted }}</p>
                                                    </div>
                                                    <div class="form-group col-2">
                                                        <label for="">Thời gian vào: </label>
                                                        <p class="form-control">{{ $invoice->checkin_time }}</p>
                                                    </div>
                                                    <div class="form-group col-2">
                                                        <label for="">Thời gian ra: </label>
                                                        <p class="form-control">{{ $invoice->checkout_time }}</p>
                                                    </div>
                                                </div>
                                                @foreach ($invoice->details as $detail)
                                                    <div class="items form-row">
                                                        <div class="form-group col-6">
                                                            <label>Tên món: </label>
                                                            <p class="form-control">{{ $detail->menuItems->name }}</p>
                                                        </div>
                                                        <div class="form-group col-1">
                                                            <label>Số lượng: </label>
                                                            <p class="form-control">{{ $detail->quantity }}</p>
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label>Giá: </label>
                                                            <p class="form-control">{{ number_format($detail->menuItems->price, 0, ',', '.') . ' VNĐ' }}</p>
                                                        </div>
                                                        <div class="form-group col-3">
                                                            <label>Thành tiền: </label>
                                                            <p class="form-control">{{ number_format($detail->quantity * $detail->menuItems->price, 0, ',', '.') . ' VNĐ' }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="form-row" style="margin-top: 30px;">
                                                    <div class="form-group col-6" style="">
                                                        <h4>Tổng tiền: {{ number_format($invoice->total_price, 0, ',', '.') . ' VNĐ' }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-close-modal btn-dark">Đóng</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                {{-- <nav>
                    <ul class="pagination pagination-rounded mb-0">
                        {{ $invoices->links() }}
                    </ul>
                </nav> --}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('.btn-view-detail').on('click', function(){
                let table_id = $(this).data('table-id');
                console.log(table_id);
                let modal_invoice = '#invoice-detail-'+table_id;
                $(modal_invoice).modal('show');
            })

            $('.btn-close-modal').on('click', function() {
                let modal = $(this).closest('.modal');
                $(modal).modal('toggle');
            })

            $('.select-filter').change(function() {
                if (this.name === 'sort_total_price') {
                    $('#sort-date').val('none');
                } else if (this.name === 'sort_date') {
                    $('#sort-total-price').val('none');
                }
                setTimeout(()=> {
                    $('#form-inline').submit();
                }, 300);
            })
        });
    </script>
@endpush