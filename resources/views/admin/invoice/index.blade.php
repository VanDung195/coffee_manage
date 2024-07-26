@extends('layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="{{ route('admin.positions.create') }}" class="btn btn-success">Thêm chức vụ</a> --}}
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered mb-0">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>Ngày tạo</th>
                            <th>TG vào</th>
                            <th>TG ra</th>
                            <th>T.Tiền</th>
                            <th>K.hàng</th>
                            <th>Thừa</th>
                            <th>Bàn</th>
                            <th>N.tạo</th>
                            <th>Detail</th>
                            <th>Xoá</th>
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
                                    <button class="btn btn-dark">Xem</button>
                                </td>
                                <td>
                                    <button class="btn btn-danger">Xoá</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
    </script>
@endpush