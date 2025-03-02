@extends('layout.master')
@push('css')
{{-- <link href="{{ asset('css/css-invoice.css') }}" rel="stylesheet" type="text/css"> --}}
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .jq-toast-single {
            font-size: 16px;
        }
        .modal-container-change-invoice{
            max-width: 500px;
        }
        .icon-swap{
            margin-top: 30px;
            margin-left: 21px;
            margin-right: 21px;
        }
        .p-table{
            margin-bottom: 0px;
        }
        .p-table span {
            display: inline;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-invoice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/invoice-test.css') }}">
@endpush
@section('content')
    <div id="left">
        <div class="header">
            <h1>Bàn</h1>
        </div>
        <button data-button-id="12" class="btn btn-click-me btn-danger">click me for delete!!</button>
        <div>
        </div>
        @foreach ($tables as $table)
            <div class="show-table" id="show_table_{{ $table->id }}" style="display: block;float: left;">
                <button class="btn-table" data-table-id="{{ $table->name }}">
                    {{ $table->name }}
                </button>
            </div>
            <div class="show-table-detail" id="show_detail_{{ $table->id }}" style="display: none;float: left;">
                <button class="btn-show-invoice-detail" data-table-id="{{ $table->id }}">
                    {{ $table->name }}
                </button>
            </div>

            <div id="modal-invoice-{{ $table->name }}" class="modal-invoice modal fade" role="dialog">
                <div class="modal-container modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tạo hoá đơn</h4>
                            <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('invoice.store') }}" method="POST" class="form-create">
                                @csrf
                                <input type="hidden" name="table_id" id="table_id" class="form-control" value="{{ $table->id }}" readonly>
                                <input type="hidden" name="is_create" value="true">
                                <p style="font-size: 20px;" id="table-id">{{ $table->name }}</p>
                                <div class="item form-row">
                                    <div class="div-select form-group col-5" id="div-select">
                                        <label for="">Món</label>
                                        <select name="id[]" class="select-item">
                                            <option value="0" selected>Chọn món</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-left: 42px;width:135px;">
                                        <label for="">Số lượng (Min: 1)</label>
                                        <br>
                                        <button type="button" class="btn-update-quantity" data-type='0'
                                            style="float: left" disabled>
                                            -
                                        </button>
                                        <input type="text" id="quantity" name="quantity[]" class="quantity form-control"
                                            value="0"
                                            style="background-color: none;border:none;height:30px;width:42px;float: left;"
                                            readonly>
                                        <button type="button" class="btn-update-quantity" data-type='1'
                                            style="float: left;" disabled>
                                            +
                                        </button>
                                    </div>
                                    <div class="form-group col-3">
                                        <span class="span-sum">
                                            <label>Giá</label>
                                            <input type="text" id="price" class="price form-control" value="0"
                                                readonly>
                                        </span>
                                    </div>
                                </div>
                                <div class="append-item">
                                </div>
                                <div class="form-row" style="margin-top: 30px;">
                                    <div class="form-group col-3" id="div-paid">
                                        <label for="">Trạng thái thanh toán: </label>
                                        <select name="is_paid" id="select_paid" class="form-control">
                                            @if ($table->name != 'takeaway')
                                                <option value="0">Chưa thanh toán</option>
                                            @endif
                                            <option value="1" selected>Đã thanh toán</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-3" style="margin-left:0px;">
                                        <label for="">Tổng tiền: </label>
                                        <input type="text" value="0" class="total-price form-control" readonly>
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Số tiền khách trả: </label>
                                        <input class="customer-payment form-control" type="number" name="customer_payment"
                                            placeholder="VD: 1 = 1.000 VND" inputmode="numeric">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Tiền thừa: </label>
                                        <p class="remaining-money form-control">0</p>
                                    </div>
                                </div>
                                <button type="button" class="append btn btn-block btn-lg btn-fill btn-danger">Thêm
                                    món</button>
                            </form>
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-submit-invoice btn btn-primary" type="button" onclick="submitForm()">Reset
                                hoá đơn</button>
                            <button class="btn-submit-invoice btn btn-success" type="button">Tạo hoá đơn</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="right">
        <div class="header">
            <h1>Quản Lý Hoá Đơn</h1>
        </div>
    </div>
    <div id="append_modal_invoice_detail"></div>
    <div id="modal-invoice-change"></div>
    <div id="modal-invoice-bak" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tạo hoá đơn</h4>
                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('invoice.store') }}" method="POST" id="form-create">
                        @csrf
                        <input type="text" class="form-control" name="table-id" id="table-id" readonly>
                        <div class="item form-row" id="item">
                            <div class="div-select form-group col-5" id="div-select">
                                <label for="">Món</label>
                                <select name="id[]" class="select-item">
                                    <option selected>Chọn món</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-left: 42px;width:135px;">
                                <label for="">Số lượng (Min: 1)</label>
                                <br>
                                <button type="button" class="btn-update-quantity" data-type='0' style="float: left"
                                    disabled>
                                    -
                                </button>
                                <input type="text" id="quantity" name="quantity[]" class="quantity form-control"
                                    value="0"
                                    style="background-color: none;border:none;height:30px;width:42px;float: left;"
                                    readonly>
                                <button type="button" class="btn-update-quantity" data-type='1' style="float: left;"
                                    disabled>
                                    +
                                </button>
                            </div>
                            <div class="form-group col-3">
                                <span class="span-sum">
                                    <label>Giá</label>
                                    <input type="text" id="price" class="price form-control" readonly>
                                </span>
                            </div>
                        </div>
                        <div id="append-item">

                        </div>
                        <div class="form-row" style="margin-top: 30px;">
                            <div class="form-group col-3" id="div-paid">
                                <label for="">Trạng thái thanh toán: </label>
                                <select name="is_paid" id="select_paid" class="form-control">
                                    <option value="0">Chưa thanh toán</option>
                                    <option value="1" selected>Đã thanh toán</option>
                                </select>
                            </div>
                            <div class="form-group col-3" style="margin-left:0px;">
                                <label for="">Tổng tiền: </label>
                                <input type="text" id="total-price" value="0" class="form-control" readonly>
                            </div>
                            <div class="form-group col-3">
                                <label for="">Số tiền khách trả: </label>
                                <input class="form-control" type="number" name="customer_payment" id="customer-payment"
                                    placeholder="VD: 1 = 1.000VND">
                            </div>
                            <div class="form-group col-3">
                                <label for="">Tiền thừa: </label>
                                <p class="form-control" id="remaining-money">0</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm
                            món</button>
                    </form>
                    <br>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @vite(['resources/js/app.js'])
    <script type="module">
        let soundEnabled = false;
        window.Echo.channel('order-channel')
            .listen('InvoicePlaced', (response) => {
                $.ajax({
                    type: "get",
                    url: '{{ route('putInvoice') }}',
                    data: {
                        invoice: response,
                        _token: '{{ csrf_token() }}',
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        let invoice = response.data;
                        let table_id = invoice.table_id;
                        let table_name = invoice.table_name;
                        let customer_payment_check = response.data.customer_payment_check;
                        let user_name = response.data.user_name;
                        let checkin_time = invoice.checkin_time;
                        let checkout_time = invoice.checkout_time;
                        let details = invoice.details;
                        let is_qr = invoice.is_qr;
                        let is_paid = invoice.is_paid;
                        let total_price = invoice.total_price;
                        let customer_payment = invoice.customer_payment;
                        let remaining_money = invoice.remaining_money;
                        let invoice_id = invoice.invoice_id;
                        let created_at = invoice.created_at;

                        let modal_invoice_data = modal_invoice(table_id,table_name,customer_payment_check,user_name,checkin_time,
                            checkout_time,details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id
                        );
                        let order_table = invoice_table_and_modal_change_invoice(table_id,table_name,is_paid,details,is_qr,checkin_time,
                            created_at,customer_payment,remaining_money,total_price,invoice_id,true
                        )
                        let rowspanCount = Math.max(details.length, 1);

                        let show_table = 'show_table_' + table_id;
                        let show_detail = 'show_detail_' + table_id;
                        let div2 = document.createElement("div");
                        div2.innerHTML = modal_invoice_data;
                        div2.classList.add("form-group");
                        div2.setAttribute("id", "div_invoice_detail_" + table_id);
                        document.getElementById("append_modal_invoice_detail").appendChild(div2);

                        const invalid_table_id = new Set([5]);
                        if(!invalid_table_id.has(table_id))
                        {
                            document.getElementById(show_table).style.display = 'none';
                            document.getElementById(show_detail).style.display = 'block';
                        }

                        let table = document.getElementById('order-table-id');
                        let rows = table.getElementsByTagName('tr');
                        if(rows.length == 1 && is_paid == 0 || rows.length == 1 && is_paid == 2)
                        {
                            let targetRow = document.querySelector('.order-table tr:first-child');
                            targetRow.insertAdjacentHTML('afterend', order_table);
                        }

                        if(is_paid == 0 || is_paid == 2 && rows.length > 1) {
                            Array.from(rows).some((row, index) => {
                                if(rows[index + 1] == undefined || rows[index + 1].getAttribute('data-status') == '1') {
                                    row.insertAdjacentHTML('afterend', order_table);
                                    return true;
                                }
                                return false;
                            });
                        }

                        setTimeout(()=> {
                            $('#new-row-remove-'+ table_id).remove();
                        }, 10000);
                        $('.form-row .select-to-table').select2({
                            tag: true
                        });
                    },
                    error: function(status ,error) {
                        console.error('AJAX request failed:', status, error);
                    }
                });
            })
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        });

        function modal_invoice(table_id,table_name,customer_payment_check,user_name,checkin_time,
                                checkout_time,details,is_qr,is_paid,total_price,customer_payment,
                                remaining_money,invoice_id)
        {
            console.log(customer_payment);
            console.log('123123123123123123121232131231231231231231212312313213254354345354');
            let modal_invoice = `
                <div id="invoice_detail_${table_id}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Hoá đơn chi tiết</h4>
                                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form class="form-create">
                                @csrf
                                <input type="hidden" name="paid" value="${customer_payment_check}" readonly>
                                <input type="hidden" name="is_create" value="false" readonly>
                                <div class="form-row">
                                    <div class="form-group col-1">
                                        <label>Bàn số</label>
                                        <p id="table-id">${table_name}</p>
                                    </div>
                                    <div class="form-group col-5">
                                        <label for="">Tên người lập</label>
                                        <p class="form-control">${user_name}</p>
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="">Giờ vào</label>
                                        <p class="checkin-time form-control">${checkin_time}</p>
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="">Giờ ra</label>
                                        <p class="checkout-time form-control">${checkout_time}</p>
                                    </div>
                                </div>
                `;

            //invoice detail
            details.forEach(function(item, index) {
                modal_invoice += `
                <div class="items form-row">
                    <div class="form-group col-6">
                        <label>Tên món: </label>
                        <input type="hidden" name="id[]" value="${item.menu_item_id}" readonly>
                        <p class="form-control">${item.name}</p>
                    </div>
                    <div class="form-group col-2">
                        <label>Số lượng: </label>
                        <input type="hidden" name="quantity[]" value="${item.quantity}" readonly>
                        <p class="form-control">${item.quantity}</p>
                    </div>
                    <div class="form-group col-2">
                        <label>Giá: </label>
                        <p class="form-control">${(item.price)}</p>
                    </div>
                    <div class="form-group col-2">
                        <label>Thành tiền: </label>
                        <p class="form-control">${item.thanh_tien}</p>
                    </div>
                </div>
                `;
            }); //end foreach
            // console.log(item.is_paid);
            modal_invoice += `
                    <div class="form-row" style="margin-top: 30px;">
                        <div class="form-group col-3" id="div-paid">
                            <label for="">Trạng thái thanh toán: </label>
                                ${function() {
                                    const text = {
                                        0: is_qr == 1 ? 'Chưa thanh toán trước (QR code)' : 'Chưa thanh toán (Cashier)',
                                        1: 'Đã thanh toán',
                                        2: 'Thanh toán luôn (QR code)'
                                    };
                                    const badgeText = text[is_paid];
                                    return `<p class="form-control">${badgeText}</p>`;
                                }()}
                        </div>
                        <div class="form-group col-3" style="margin-left: 5px;">
                            <label for="">Tổng tiền: </label>
                            <p class="form-control">${(total_price).toLocaleString('vi-VN')}</p>
                        </div>
                        ${customer_payment_check ?
                            `<div class="form-group col-2">
                                <label>Số tiền khách trả: </label>
                                <p class="customer-payment form-control">${customer_payment}</p>
                            </div>
                            <div class="form-group col-2">
                                <label>Tiền thừa: </label>
                                <p class="remaining-money form-control">${remaining_money}</p>
                            </div>`
                        :
                            `<div class="form-group col-2">
                                <label>Tiền thừa: </label>
                                <input class="customer-payment form-control" type="number" name="customer_payment"
                                placeholder="VD: 1 = 1.000 VND" inputmode="numeric">
                                <span id="customer-payment-error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-2">
                                <label>Tiền thừa: </label>
                                <p class="remaining-money form-control">0</p>
                                <span id="remaining-money-error" class="text-danger"></span>
                            </div>`
                        }
                            </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="deleteInvoice('${table_id}', 'modal_invoice')" class="btn btn-delete-invoice btn-danger">Xoá hoá đơn</button>
                    <button data-invoice-id="${invoice_id}"
                        id="btn-generate-invoice-modal-${table_id}"
                        class="btn btn-generate-invoice btn-success">
                        Xuất hoá đơn
                    </button>
                </div>
                </div>
                </div>
                </div>
            `;
            return modal_invoice;
        }

        function invoice_table_and_modal_change_invoice(table_id,table_name,is_paid,details,
                is_qr,checkin_time,created_at,customer_payment,remaining_money,total_price,invoice_id,is_create)
        {
            let order_table = ``;
            let div_modal_change_invoice = document.createElement('div');
            div_modal_change_invoice.classList.add('form-group');
            let modal_change_invoice = ``;
            modal_change_invoice = `
                <div id="modal-change-invoice-id-${table_id}" class="modal-change-invoice-${table_id} modal fade" role="dialog">
                    <div class="modal-container-change-invoice modal-dialog modal-sm">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Sửa thông tin</h4>
                                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form class="form-change-invoice">
                                    @csrf
                                    <input type="hidden" class="payment-status" name="payment-status" value="${is_paid}">
                                    <div class="form-row">
                                        <input type="hidden" class="from-table-id" name="table_id" value="${table_id}">
                                        <div class="form-group col-5">
                                            <label>Từ bàn</label>
                                            <p class="from-table-name form-control">${table_name}</p>
                                        </div>
                                        <div class="icon-swap form-group">
                                            <i style="font-size: 35px" class=" uil-exchange-alt"></i>
                                        </div>
                                        <div class="form-group col-5">
                                            <label>Tới bàn</label>
                                            <select name="to_table" class="select-to-table">
                                                @foreach ($table_names_available as $item)
                                                    <option value="{{ $item['id'] }}" data-table="{{ $item['name'] }}">
                                                        {{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-submit-change-invoice btn btn-success" type="button">Đổi thông tin</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            div_modal_change_invoice.innerHTML = modal_change_invoice;
            document.getElementById('modal-invoice-change').appendChild(div_modal_change_invoice);

            let rowspanCount = Math.max(details.length, 1);
            order_table += `<tr data-status="${is_paid}" class="order_table_class_${table_id}">`;
            order_table += `
                <td border="1" class="set-row" rowspan="${rowspanCount}" id="new-row-${table_id}">
                    <p id="p-table-id-${table_id}" class="p-table">
                        <span id="span-table-id-${table_id}" style="font-weight: bold;font-size:17px;">${table_name}</span>
                        <span>${is_qr ? '(QR)' : ''}</span>
                        ${is_create == true ?
                        `<span class="new-invoice-check badge badge-success p-2s">(New)</span>`
                        :``}
                    </p>
                    ${checkin_time}<br>${created_at}
                </td>
            `;
            let count = 1;
            details.forEach(function(detail, index) {
                if (count != 1) {
                    order_table +=
                        `<tr class="order_table_class_${table_id}">`;
                }
                order_table += `
                    <td>${detail.name}</td>
                    <td class="price">${detail.price.toLocaleString('vi-VN')}</td>
                    <td>${detail.quantity}</td>
                `;
                if (count == 1) {
                    order_table += `
                        <td class="set-row" rowspan="${rowspanCount}">${total_price.toLocaleString('vi-VN')}</td>
                        <td rowspan="${rowspanCount}">
                            <button data-table-id="${table_id}"
                                    id="btn-generate-invoice-${table_id}"
                                    class="btn btn-generate-invoice btn-success btn-sm">
                                Xuất
                            </button>
                        </td>
                        <td rowspan="${rowspanCount}">
                            <li class="list-inline-item ml-2">
                                ${function() {
                                    const badgeStyles = "font-size: 15px; p-2s";
                                    const badges = {
                                        0: is_qr == 1 ? 'badge-warning' : 'badge-secondary',
                                        1: 'badge-success',
                                        2: 'badge-warning'
                                    };
                                    const badgeTexts = {
                                        0: 'TT sau',
                                        1: 'Đã TT',
                                        2: 'TT trước'
                                    };

                                    const badgeClass = badges[is_paid] || 'badge-secondary';
                                    const badgeText = badgeTexts[is_paid] || 'TT sau';

                                    return `<div style="${badgeStyles}" class="badge ${badgeClass}">${badgeText}</div>`;
                                }()}
                            </li>
                        </td>
                        <td rowspan="${rowspanCount}">
                            <button type="button" data-table-id="${table_id}"
                                    class="btn-change-invoice btn btn-danger btn-sm"
                                    id="btn-change-invoice-${table_id}">Change</button>
                        </td>
                        <td rowspan="${rowspanCount}'">
                            ${customer_payment}
                        </td>
                        <td rowspan="${rowspanCount}'">
                            ${remaining_money}
                        </td>
                        <td rowspan="${rowspanCount}">
                            <button onclick="deleteInvoice('${table_id}','order_table')"
                                    class="btn btn-delete-invoice btn-danger btn-sm">Xoá</button>
                        </td>
                    `;
                }
                order_table += `</tr>`;
                count++;
            });

            return order_table;
        }

        function generateInvoice(table_name,user_name,checkin_time,checkout_time,details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id,created_at)
        {
            let invoiceHtml = `
                <div class="invoice" id="invoice">
                    <div class="header">
                        <div class="title">
                            <h1>Quán đồ án 1</h1>
                            <p>Số hoá đơn: <span>${invoice_id}</span></p>
                            <p>Ngày xuất hoá đơn: <span>${created_at}</span></p>
                        </div>
                    </div>
                    <div class="products">
                        <h2>Danh sách sản phẩm</h2>
                        <table class="table-invoice">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá thành</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
            details.forEach(function (item) {
                invoiceHtml += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price} VND</td>
                    </tr>
                `;
            });
            invoiceHtml += `
                            </tbody>
                        </table>
                    </div>
                    <div class="total">
                        <p><strong>Tổng cộng:</strong> ${total_price} VND</p>
                        <p><strong>Phương thức thanh toán:</strong> Tiền mặt</p>
                    </div>
                    <p class="footer-p">Người lập hoá đơn: Hồ Văn Dũng</p>
                    <p class="footer-p">Liên hệ: 0000000000</p>
                </div>
            `;

            const tempDiv = $('<div>').html(invoiceHtml).appendTo('body');
            html2canvas(document.getElementById('invoice'), { scale: 2 }).then(canvas => {
                canvas.toBlob(blob => {
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.href = url;
                    link.download = `invoice-${invoice_id}.png`;
                    link.click();
                    URL.revokeObjectURL(url); // Giải phóng URL
                    tempDiv.remove(); // Xóa div tạm thời
                }, 'image/png');
            }).catch(error => {
                console.error('Error capturing the canvas:', error);
            });
        }
        //nice
        function generateInvoice2(table_name,user_name,checkin_time,checkout_time,details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id,created_at)
        {
            let invoiceHtml = `
                <div class="receipt" id="invoice-print" style="background-color: white !important;color:black;border:none !important;">
                    <h1>PROJECT 01</h1>
                    <div class="center">
                        <p class="center">123ABC, Thành phố Huế, Tỉnh TT Huế</p>
                    </div>
                    <div class="center">
                        <p class="bold center">Bàn: ${table_name}</p>
                    </div>
                    <p>Thời gian: ${created_at}</p>
                    <p>Giờ in: ${checkout_time}</p>
                    <div class="amount-row" style="margin: 1px 0;">
                        <p>Giờ vào: ${checkin_time}</p>
                        <p style="font-weight:100;">Giờ ra: ${checkout_time}</p>
                    </div>
                    <p>Thu ngân: ${user_name}</p>
                    <p class="bold">Số Bill: <span class="bill-number">${invoice_id}</span></p>

                    <table class="custom-table" style="color:black;border-collapse: collapse;width: 100%;border-top: 2px solid black;border-bottom: 2px solid black;">
                        <thead>
                            <tr style="border-bottom: 1px solid black !important;margin:0px;border-top: 1px solid black !important;">
                                <th style="border: none;padding: 6px;text-align: left;">TT</th>
                                <th style="border: none;padding: 6px;text-align: left;">Tên món</th>
                                <th style="border: none;padding: 6px;text-align: left;">SL</th>
                                <th style="border: none;padding: 6px;text-align: left;">Đ.Giá</th>
                                <th style="border: none;padding: 6px;text-align: left;">T.Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            let count = 1;
            details.forEach(function(item) {
                invoiceHtml += `
                    <tr style="border-bottom: 1px solid black !important;">
                        <td style="border: none;padding: 6px;text-align: left;">${count}</td>
                        <td style="border: none;padding: 6px;text-align: left;">${item.name}</td>
                        <td style="border: none;padding: 6px;text-align: left;">${item.quantity}</td>
                        <td style="border: none;padding: 6px;text-align: left;">${item.price}</td>
                        <td style="border: none;padding: 6px;text-align: left;">${item.thanh_tien}</td>
                    </tr>
                `;
                count++;
            });

            invoiceHtml += `
                    </tbody>
                </table>
                <div class="amount-row">
                    <p class="bold">Thành tiền: </p>
                    <p class="bold">${total_price}</p>
                </div>
                <div class="amount-row">
                    <p class="bold">Tiền khách đưa: </p>
                    <p class="bold">${customer_payment}</p>
                </div>
                <div class="amount-row">
                    <p class="bold">Tiền thừa: </p>
                    <p class="bold">${remaining_money}</p>
                </div>
                <p class="bold">Password Wifi: asdhjasdkjgaskjdh</p>
            </div>
            `;
            const tempDiv = $('<div>').html(invoiceHtml).appendTo('body');
            html2canvas(document.getElementById('invoice-print'), { scale: 2 }).then(canvas => {
                canvas.toBlob(blob => {
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.href = url;
                    link.download = `invoice-${invoice_id}.png`;
                    link.click();
                    URL.revokeObjectURL(url); // Giải phóng URL
                    tempDiv.remove(); // Xóa div tạm thời
                }, 'image/png');
            }).catch(error => {
                console.error('Error capturing the canvas:', error);
            });
        }


        $('.select-item').on('change', function() {
            var selectedValue = $(this).val();
            var defaultOption = $(this).find('option:contains("Chọn món")');
            if (selectedValue !== "0") {
                defaultOption.prop('disabled', true);
            } else {
                defaultOption.prop('disabled', false);
            }
        });
        let total_price_global = 0;
        $(document).on('keyup', '.customer-payment',function() {
            console.log(123);
            let modal_body = $(this).closest('.modal-body');
            let total_price = modal_body.find('.total-price').val();
            let customer_payment = $(this).val();

            let modal_content = $(this).closest('.modal-content');
            let object = modal_content.find('.form-create');
            let form_data = new FormData(object[0]);
            $.ajax({
                type: "post",
                url: '{{ route('invoice.update') }}',
                data: form_data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    let remaining_money = response.data.remaining_money;
                    let remaining_money2 = remaining_money != 'NULL' ? remaining_money.toLocaleString('vi-VN') : 'NULL';
                    let is_create = response.data.is_create;
                    if(is_create == 'true')
                    {
                        console.log('true1');
                        modal_body.find('.remaining-money').html(remaining_money2);
                        modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", false);
                    } else {
                        console.log('true2');
                        modal_body.find('.remaining-money').html(remaining_money2);
                        modal_body.closest('.modal-content').find('.btn-generate-invoice').prop( "disabled", false);
                    }
                },
                error: function(error) {
                    let is_create = error.responseJSON.message;
                    if(is_create == 'true')
                    {
                        modal_body.find('.remaining-money').html('NULL');
                        modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", true);
                    } else {
                        modal_body.find('.remaining-money').html('NULL');
                        modal_body.closest('.modal-content').find('.btn-generate-invoice').prop( "disabled", true);
                    }
                    console.log('Sai rồi!!!');
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                url: '{{ route('api.invoices') }}',
                dataType: 'json',
                success: function(response) {
                    let divapi = document.createElement("div");
                    response.data.invoices.forEach(function(item, index) {
                        console.log(123);
                        console.log(item);
                        let table_id_api = item.table_id;
                        let table_name = item.table_name;

                        let show_table_api = 'show_table_' + table_id_api;
                        let show_detail_api = 'show_detail_' + table_id_api;
                        const invalid_table_id = new Set([5]);
                        if(!invalid_table_id.has(table_id_api))
                        {
                            document.getElementById(show_table_api).style.display = 'none';
                            document.getElementById(show_detail_api).style.display = 'block';
                        }
                        let customer_payment_check = item.customer_payment_check;
                        let user_name = item.user_name;
                        let checkin_time = item.checkin_time;
                        let checkout_time = item.checkout_time;
                        let details = item.details;
                        let is_qr = item.is_qr;
                        let is_paid = item.is_paid;
                        let total_price = item.total_price;
                        let customer_payment = item.customer_payment;
                        let remaining_money = item.remaining_money;
                        let invoice_id = item.invoice_id;


                        let modal_invoice_api = modal_invoice(table_id_api,table_name,customer_payment_check,user_name,checkin_time,
                            checkout_time,details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id);
                        let diva = document.createElement("div");
                        diva.innerHTML = modal_invoice_api;
                        diva.classList.add("form-group");
                        diva.setAttribute("id", "div_invoice_detail_" + table_id_api);
                        document.getElementById("append_modal_invoice_detail").appendChild(diva);
                    }) //end foreach

                    let order_table = `
                        <table class="order-table" id="order-table-id">
                            <tr>
                                <th>ID</th>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>SL</th>
                                <th>Tổng tiền</th>
                                <th>Xuất</th>
                                <th>Tình trạng</th>
                                <th>Đổi bàn</th>
                                <th>Tiền</th>
                                <th>Thừa</th>
                                <th>Xoá</th>
                            </tr>
                    `;
                    console.log(123123);
                    // console.log(response.data.invoices);
                    console.log(response.data);

                    response.data.invoices.forEach(function(item, index) {
                        let table_id = item.table_id;
                        let table_name = item.table_name;
                        let is_paid = item.is_paid;
                        let details = item.details;
                        let is_qr = item.is_qr;
                        let checkin_time = item.checkin_time;
                        let created_at = item.created_at;
                        let customer_payment = item.customer_payment;
                        let remaining_money = item.remaining_money;
                        let total_price = item.total_price;
                        let invoice_id = item.invoice_id;
                        console.log(item);

                        order_table += invoice_table_and_modal_change_invoice(table_id,table_name,is_paid,details,is_qr,
                                            checkin_time,created_at,customer_payment,remaining_money,total_price,invoice_id,false);

                    })
                    order_table += `
                        </table>
                    `;
                    let div_table = document.createElement("div");
                    div_table.innerHTML = order_table;
                    div_table.classList.add("form-group");
                    document.getElementById("right").appendChild(div_table);
                    $('.form-row .select-to-table').select2({
                        tag: true
                    });

                    order_table += `
                        </table>
                    `;
                    let div_table = document.createElement("div");
                    div_table.innerHTML = order_table;
                    div_table.classList.add("form-group");
                    document.getElementById("right").appendChild(div_table);
                    let table = document.getElementById('order-table-id');
                    let rows = table.getElementsByTagName('tr');
                    $('.form-row .select-to-table').select2({
                        tag: true
                    });
                },
                error: function(error) {
                    console.log(error);
                    console.log('Sai mia no roi may');
                }
            });

        });
        //https://stackoverflow.com/questions/7114780/convert-jquery-element-to-html-element
        $('.btn-submit-invoice').on('click', function() {
            let modal_content = $(this).closest('.modal-content');
            let obj = modal_content.find('.form-create');
            let modal_invoice_close = $(this).closest('.modal-invoice');

            let formData = new FormData(obj[0]);
            $.ajax({
                type: 'post',
                url: obj.attr('action'),
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    $.toast({
                        heading: 'Thành công',
                        text: response.message,
                        showHideTransition: 'slide',
                        icon: 'success'
                    })
                    let invoice = response.data;
                    let table_id = invoice.table_id;
                    let table_name = invoice.table_name;
                    let customer_payment_check = invoice.customer_payment_check;
                    let user_name = invoice.user_name;
                    let checkin_time = invoice.checkin_time;
                    let checkout_time = invoice.checkout_time;
                    let details = invoice.details;
                    let is_qr = invoice.is_qr;
                    let is_paid = invoice.is_paid;
                    let total_price = invoice.total_price;
                    let customer_payment = invoice.customer_payment;
                    let remaining_money = invoice.remaining_money;
                    let invoice_id = invoice.invoice_id;
                    let created_at = invoice.created_at;
                    let show_table = 'show_table_' + table_id;
                    let show_detail = 'show_detail_' + table_id;
                    console.log(invoice);
                    if(invoice_id > 0)
                    {
                        generateInvoice2(table_name,user_name,checkin_time,checkout_time,details,is_qr,is_paid,
                                             total_price,customer_payment,remaining_money,invoice_id,created_at);
                    }

                    let modal_invoice_data = modal_invoice(table_id,table_name,customer_payment_check,user_name,checkin_time,checkout_time,
                                                        details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id)
                    let diva = document.createElement("div");
                    diva.innerHTML = modal_invoice_data;
                    diva.classList.add("form-group");
                    diva.setAttribute("id", "div_invoice_detail_" + table_id);
                    document.getElementById("append_modal_invoice_detail").appendChild(diva);

                    let order_table =  invoice_table_and_modal_change_invoice(table_id,table_name,is_paid,details,is_qr,checkin_time,created_at,
                                                            customer_payment,remaining_money,total_price,invoice_id,true);

                    const invalid_table_id = new Set(['999']);
                    if(!invalid_table_id.has(table_id))
                    {
                        document.getElementById(show_table).style.display = 'none';
                        document.getElementById(show_detail).style.display = 'block';

                        let table = document.getElementById('order-table-id');
                        let rows = table.getElementsByTagName('tr');
                        if(response.data.is_paid == 1 && rows.length > 1)
                        {
                            for(let i = 0; i < rows.length; i++)
                            {
                                //trường hợp khi dữ liệu trong bảng toàn 'Chưa thanh toán'
                                if(rows[i+1] == undefined)
                                {
                                    rows[i].insertAdjacentHTML('afterend', order_table);
                                    break;
                                }
                                if(rows[i+1].getAttribute('data-status') == '1')
                                {
                                    rows[i].insertAdjacentHTML('afterend', order_table);
                                    break;
                                }
                            }
                        }
                        if(response.data.is_paid == 1 && rows.length == 1 || response.data.is_paid == 0 && rows.length == 1)
                        {
                            let targetRow = document.querySelector('.order-table tr:first-child');
                            targetRow.insertAdjacentHTML('afterend', order_table);
                        }
                        if(response.data.is_paid == 0 && rows.length > 1) {
                            Array.from(rows).some((row, index) => {
                                if(rows[index + 1] == undefined || rows[index + 1].getAttribute('data-status') == '1') {
                                    row.insertAdjacentHTML('afterend', order_table);
                                    return true;
                                }
                                return false;
                            });
                        }


                        //reset modal sau khi tao hoa don
                        modal_invoice_close.find('form').trigger('reset');
                        let parent_div = modal_invoice_close.find('.append-item');
                        let child_div = parent_div.find('.form-row');
                        let select_item = modal_invoice_close.find('.select-item');
                        select_item.select2({
                            tag: true
                        });
                        $('.form-row .select-to-table').select2({
                            tag: true
                        });
                        //2 cách để xoá
                        for (let index = 0; index < child_div.length; index++) {
                            child_div.get(index).remove();
                        }
                        setTimeout(()=> {
                            $(`#new-row-${table_id} .new-invoice-check`).remove();
                        }, 10000);
                    }
                    modal_invoice_close.modal('toggle');
                    document.getElementById('remaining-money').textContent = "0";
                    $('.form-row .select-to-table').select2({
                        tag: true
                    });
                },
                error: function(error) {
                    console.log(error);
                    $.toast({
                        heading: 'Error',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'error'
                    })
                }
            });
        });

        $(document).on('click', '.btn-change-invoice', function(){
            let table_id = $(this).data('table-id');
            console.log(table_id);
            let modal_change_invoice = '.modal-change-invoice-'+table_id;
            $(modal_change_invoice).modal('show');
        });

        $(document).on('click', '.btn-click-me', function() {
            console.log(123);

            let table_id = $(this).data('button-id');
            $.ajax({
                type: "get",
                url: '{{ route('table.update') }}',
                data: {
                    table_id
                },
                success: function (response) {

                }
            });
        })
        $(document).on('click', '.btn-generate-invoice, .btn-show-invoice-detail', function () {
            let table_id = $(this).data('table-id');
            let invoice_detail = '#invoice_detail_' + table_id;
            $(invoice_detail).modal('show');

            let btn_generate_invoice = $(invoice_detail).find('.btn-generate-invoice');

                $(invoice_detail).off('click', '.btn-generate-invoice');
                $(invoice_detail).on('click', '.btn-generate-invoice', function () {
                    let nested_invoice_id = $(this).data('invoice-id');
                    let modal_content = $(this).closest('.modal-content');
                    let modal_body = modal_content.find('.modal-body');
                    let customer_payment = modal_body.find('.customer-payment').val();
                    console.log('Clicked button inside modal with nested_invoice_id:', nested_invoice_id);

                    $.ajax({
                        type: "GET",
                        url: '{{ route('generateInvoice') }}',
                        data: {
                            invoice_id: nested_invoice_id,
                            table_id: table_id,
                            customer_payment: customer_payment,
                        },
                        success: function (response) {
                            let invoice = response.data.invoice;
                            console.log(response.data);
                            let is_update_invoice = response.data.is_update_invoice;
                            console.log(invoice);
                            let table_id = invoice.table_id;
                            let table_name = invoice.table_name;
                            let customer_payment_check = invoice.customer_payment_check;
                            let user_name = invoice.user_name;
                            let checkin_time = invoice.checkin_time;
                            let checkout_time = invoice.checkout_time;
                            let details = invoice.details;
                            let is_qr = invoice.is_qr;
                            let is_paid = invoice.is_paid;
                            let total_price = invoice.total_price;
                            let customer_payment = invoice.customer_payment;
                            let remaining_money = invoice.remaining_money;
                            let invoice_id = invoice.invoice_id;
                            let created_at = invoice.created_at;
                            let modal_invoice_1 = '#invoice_detail_'+table_id;
                            setTimeout(() => {
                                $(modal_invoice_1).modal('toggle');
                            }, 200);
                            if(is_update_invoice == false)
                            {
                                generateInvoice2(table_name,user_name,checkin_time,checkout_time,details,is_qr,is_paid,
                                             total_price,customer_payment,remaining_money,invoice_id,created_at);
                            } else {
                                //trường hợp này là khi khách hàng thanh toán sau và khi xuất hoá đơn tức là khách hàng đã đi về nên xoá
                                generateInvoice2(table_name,user_name,checkin_time,checkout_time,details,is_qr,is_paid,
                                             total_price,customer_payment,remaining_money,invoice_id,created_at);

                                //xoá để thêm các loại modal mới
                                let modal_change_invoice = '.modal-change-invoice-'+table_id;
                                setTimeout(()=> {
                                    $(modal_change_invoice).remove();
                                    let div_invoice = "div_invoice_detail_" + table_id;
                                    let divR = document.getElementById(div_invoice);
                                    console.log(div_invoice);
                                    divR.remove();
                                    let elements = document.querySelectorAll('.order_table_class_' + table_id);
                                    elements.forEach(function(element) {
                                        console.log(element);
                                        element.remove();
                                    })

                                    //add modal invoice
                                    let modal_invoice_data = modal_invoice(table_id,table_name,customer_payment,user_name,checkin_time,checkout_time,
                                                                details,is_qr,is_paid,total_price,customer_payment,remaining_money,invoice_id)
                                    let diva = document.createElement("div");
                                    diva.innerHTML = modal_invoice_data;
                                    diva.classList.add("form-group");
                                    diva.setAttribute("id", "div_invoice_detail_" + table_id);
                                    document.getElementById("append_modal_invoice_detail").appendChild(diva);

                                    //add invoice o table
                                    let order_table = invoice_table_and_modal_change_invoice(table_id,table_name,is_paid,details,is_qr,checkin_time,created_at,
                                                            customer_payment,remaining_money,total_price,invoice_id,false
                                    );

                                    let table = document.getElementById('order-table-id');
                                    let rows = table.getElementsByTagName('tr');

                                    if(rows.length > 1)
                                    {
                                        for(let i = 0; i < rows.length; i++)
                                        {
                                            //trường hợp khi dữ liệu trong bảng toàn 'Chưa thanh toán'
                                            if(rows[i+1] == undefined)
                                            {
                                                rows[i].insertAdjacentHTML('afterend', order_table);
                                                break;
                                            }
                                            if(rows[i+1].getAttribute('data-status') == '1')
                                            {
                                                // table.insertBefore(order_table, rows[i]);
                                                console.log(rows[i]);
                                                rows[i].insertAdjacentHTML('afterend', order_table);
                                                inserted = true;
                                                break;
                                            }
                                        }
                                    }
                                    if(rows.length == 1)
                                    {
                                        let targetRow = document.querySelector('.order-table tr:first-child');
                                        targetRow.insertAdjacentHTML('afterend', order_table);
                                    }

                                    $('.form-row .select-to-table').select2({
                                        tag: true
                                    });
                                }, 800);
                            }
                        },
                        error: function (error) {
                            // console.error('AJAX Error:', status, error);
                            console.log(error);
                            $.toast({
                                heading: 'Error',
                                text: error.responseJSON.message,
                                showHideTransition: 'slide',
                                icon: 'error'
                            })
                        }
                    });
                });
        $(document).on('click', '.btn-submit-change-invoice', function(){
            let modal_content = $(this).closest('.modal-content');
            let modal = $(this).closest('.modal');

            let from_table_id = modal_content.find('.from-table-id').val();
            let to_table_id = modal_content.find('.select-to-table').val();

            let payment_status_old = modal_content.find('.payment-status').val();
            let csrf_token = modal_content.find('input[name="_token"]').val();

            let modal_class_new = '.modal-change-invoice-'+to_table_id;
            let payment_status_new = $(modal_class_new).find('.payment-status').val();
            console.log(payment_status_new);
            console.log("day la ajax de doi thong tin ban");
            $.ajax({
                type: 'post',
                url: '{{ route('table_update') }}',
                data: {
                    from_table_id: from_table_id,
                    to_table_id: to_table_id,
                    payment_status_old: payment_status_old,
                    payment_status_new: payment_status_new,
                    _token: csrf_token
                },
                dataType: "json",
                success: function (response) {
                    $.toast({
                        heading: 'Thành công',
                        text: response.message,
                        showHideTransition: 'slide',
                        icon: 'success'
                    })
                    $(modal).modal('toggle');
                    let new_key = response.data.new_key;
                    let old_key = response.data.old_key;

                    let new_key_name = response.data.new_key_name;
                    let old_key_name = response.data.old_key_name;
                    let span_id_old_key = 'span-table-id-' + old_key;
                    let span_id_new_key = 'span-table-id-' + new_key;
                    let span_id_table_new = document.getElementById(span_id_new_key);
                    let span_id_table_old = document.getElementById(span_id_old_key);

                    let id_modal_invoice_new = 'invoice_detail_' + new_key;
                    let modal_invoice_new = document.getElementById(id_modal_invoice_new);

                    let modal_invoice_id_new = 'invoice_detail_'+new_key;
                    let modal_invoice_id_old = 'invoice_detail_'+old_key;

                    //change button delete
                    let btn_delete_data_table_old = $('.order_table_class_'+old_key).find('.btn-delete-invoice');
                    let btn_delete_data_table_new = $('.order_table_class_'+new_key).find('.btn-delete-invoice');
                    let btn_delete_modal_old = $('#invoice_detail_'+old_key).find('.btn-delete-invoice');
                    let btn_delete_modal_new = $('#invoice_detail_'+new_key).find('.btn-delete-invoice');

                    let invoice_detail_old = document.getElementById('invoice_detail_'+old_key);
                    let invoice_detail_new = document.getElementById('invoice_detail_'+new_key);

                    let div_invoice_detail_old = document.getElementById('div_invoice_detail_'+old_key);
                    let div_invoice_detail_new = document.getElementById('div_invoice_detail_'+new_key);

                    let elements_old = document.querySelectorAll('.order_table_class_'+old_key);
                    let elements_new = document.querySelectorAll('.order_table_class_'+new_key);

                    let class_invoice_new = '.modal-change-invoice-'+new_key;
                    let class_invoice_old = '.modal-change-invoice-'+old_key;

                    let class_invoice_new_without_dot = 'modal-change-invoice-'+new_key;
                    let class_invoice_old_without_dot = 'modal-change-invoice-'+old_key;

                    let btn_change_invoice_id_old = 'btn-change-invoice-'+old_key;
                    let btn_change_invoice_id_new = 'btn-change-invoice-'+new_key;
                    let btn_change_invoice_old = $('#'+btn_change_invoice_id_old);
                    let btn_change_invoice_new = $('#'+btn_change_invoice_id_new);

                    let btn_generate_invoice_old = $('#btn-generate-invoice-'+old_key);
                    let btn_generate_invoice_new = $('#btn-generate-invoice-'+new_key);

                    let btn_generate_invoice_modal_old = $('#btn-generate-invoice-modal-'+old_key);
                    let data_invoice_id_old = $(btn_generate_invoice_modal_old).data('invoice-id');

                    let btn_generate_invoice_modal_new = $('#btn-generate-invoice-modal-'+new_key);
                    let data_invoice_id_new = $(btn_delete_modal_new).data('invoice-id');
                    if(modal_invoice_new != null)  //done
                    {
                        btn_generate_invoice_old.data('table-id', new_key).attr('data-table-id', new_key);
                        btn_generate_invoice_new.data('table-id', old_key).attr('data-table-id', old_key);
                        btn_generate_invoice_old.attr('id', 'btn-generate-invoice-'+new_key);
                        btn_generate_invoice_new.attr('id', 'btn-generate-invoice-'+old_key);
                        btn_generate_invoice_modal_old.attr('id', 'btn-generate-invoice-modal-'+new_key);
                        btn_generate_invoice_modal_new.attr('id', 'btn-generate-invoice-modal-'+old_key);



                        console.log(span_id_table_new);
                        console.log(span_id_table_old);
                        console.log('co modal invoice new nhe!!!!!');
                        //data table change

                        $(class_invoice_new).find('.from-table-name').html(old_key_name);
                        $(class_invoice_new).find('.from-table-id').val(old_key);
                        $(class_invoice_new).removeClass(class_invoice_new_without_dot).addClass(class_invoice_old_without_dot); ////

                        btn_change_invoice_new.data('table-id', old_key);
                        btn_change_invoice_new.attr('id', btn_change_invoice_id_old);

                        span_id_table_new.textContent = old_key_name;
                        span_id_table_new.id = span_id_old_key;

                        modal_content.find('.from-table-name').html(new_key_name);
                        modal_content.find('.from-table-id').val(new_key);
                        modal_content.closest(class_invoice_old).removeClass(class_invoice_old_without_dot).addClass(class_invoice_new_without_dot);

                        btn_change_invoice_old.data('table-id', new_key);
                        btn_change_invoice_old.attr('id', btn_change_invoice_id_new);

                        span_id_table_old.textContent = new_key_name;
                        span_id_table_old.id = span_id_new_key;

                        //modal invoice change
                        invoice_detail_old.id = 'invoice_detail_'+new_key;
                        invoice_detail_new.id = 'invoice_detail_'+old_key;

                        //change btn delete
                        btn_delete_data_table_old.attr('onclick', `deleteInvoice('${new_key}', 'order_table')`);
                        btn_delete_data_table_new.attr('onclick', `deleteInvoice('${old_key}', 'order_table')`);
                        btn_delete_modal_old.attr('onclick', `deleteInvoice('${new_key}', 'modal_invoice')`);
                        btn_delete_modal_new.attr('onclick', `deleteInvoice('${old_key}', 'modal_invoice')`);

                        //change div invoice detail
                        div_invoice_detail_old.id = 'div_invoice_detail_'+new_key;
                        div_invoice_detail_new.id = 'div_invoice_detail_'+old_key;

                        div_invoice_detail_old.querySelector('#table-id').innerHTML = new_key_name;
                        div_invoice_detail_new.querySelector('#table-id').innerHTML = old_key_name;

                        elements_old.forEach(function(element){
                            element.className = 'order_table_class_'+new_key;
                        });
                        elements_new.forEach(function (element) {
                            element.className = 'order_table_class_'+old_key;
                        });

                    }
                    else
                    {
                        btn_generate_invoice_old.data('table-id', new_key).attr('data-table-id', new_key);
                        btn_generate_invoice_old.attr('id', 'btn-generate-invoice-'+new_key);
                        btn_generate_invoice_modal_old.attr('id', 'btn-generate-invoice-modal-'+new_key);

                        console.log(123);
                        modal_content.find('.from-table-name').html(new_key_name);
                        modal_content.find('.from-table-id').val(new_key);
                        modal_content.closest(class_invoice_old).removeClass(class_invoice_old_without_dot).addClass(class_invoice_new_without_dot);

                        btn_change_invoice_old.data('table-id', new_key);
                        btn_change_invoice_old.attr('id', btn_change_invoice_id_new);

                        span_id_table_old.textContent = new_key_name;
                        span_id_table_old.id = span_id_new_key;

                        let btn_show_table_new = "show_table_" + new_key;
                        let btn_show_invoice_detail_new = "show_detail_" + new_key;
                        document.getElementById(btn_show_table_new).style.display = 'none';
                        document.getElementById(btn_show_invoice_detail_new).style.display = 'block';

                        let btn_show_table_old = "show_table_" + old_key;
                        let btn_show_invoice_detail_old = "show_detail_" + old_key;
                        document.getElementById(btn_show_table_old).style.display = 'block';
                        document.getElementById(btn_show_invoice_detail_old).style.display = 'none';

                        btn_delete_data_table_old.attr('onclick', `deleteInvoice('${new_key}', 'order_table')`);
                        btn_delete_modal_old.attr('onclick', `deleteInvoice('${new_key}', 'modal_invoice')`);
                        div_invoice_detail_old.id = 'div_invoice_detail_'+new_key;
                        invoice_detail_old.id = 'invoice_detail_'+new_key;
                        div_invoice_detail_old.querySelector('#table-id').innerHTML = new_key_name;


                        elements_old.forEach(function(element){
                            element.className = 'order_table_class_'+new_key;
                        });

                    }
                },
                error: function (error) {
                    $.toast({
                        heading: 'Error',
                        text: error.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'error'
                    })
                }
            });
        });

        function close_modal() {
            $('#modal-invoice').modal('toggle');
        }

        function deleteInvoice(table_id, type) {
            console.log(type);
            $.ajax({
                type: 'get',
                url: '{{ route('table.update') }}',
                data: {
                    table_id
                },
                success: function(response) {
                    console.log('day la ham delete invoice');
                    //delete invoice detail modal
                    let modal_invoice = "#invoice_detail_" + table_id;
                    let modal_change_invoice = '.modal-change-invoice-'+table_id;
                    $(modal_change_invoice).remove();
                    if (type == 'modal_invoice') {
                        $(modal_invoice).modal('toggle');
                    }
                    let div_invoice = "div_invoice_detail_" + table_id;
                    let divR = document.getElementById(div_invoice);
                    console.log(div_invoice);
                    divR.remove();

                    //switch tu red button to green button
                    let btn_show_table = "show_table_" + table_id;
                    let btn_show_invoice_detail = "show_detail_" + table_id;
                    document.getElementById(btn_show_table).style.display = 'block';
                    document.getElementById(btn_show_invoice_detail).style.display = 'none';


                    //remove tr table
                    console.log(table_id);
                    let elements = document.querySelectorAll('.order_table_class_' + table_id);
                    elements.forEach(function(element) {
                        console.log(element);
                        element.remove();
                    })
                    console.log('thanh cong roi');
                }
            });
        }

        $('.test-btn').on('click', function(event) {
            let modal_body = $(this).closest('.modal-body');
            let item = modal_body.find('.item');
            let total_price = 0;

            item.each(function(key, value) {
                let quantity = parseInt(($(value).find('.quantity')).val());
                let price = $(value).find('.select-item').find(':selected').data('price');
                let total = price * quantity;
                total_price += total;
            });
            modal_body.find('.total-price').val(total_price.toLocaleString('vi-VN'));
        });

        function updateRowTotal(formRow, modalBody) {
            let quantity = parseInt(formRow.find('.quantity').val());
            let price = formRow.find(".select-item").find(":selected").data("price");
            let sum = price * quantity;
            formRow.find(".price").val(sum.toLocaleString('vi-VN'));

            let modal_body = modalBody;
            updateTotalPrice(modal_body);
        }

        function updateTotalPrice(modal_body) {
            // let modal_body = $(this).closest('.modal-body');
            let item = modal_body.find('.item');
            let total_price = 0;

            item.each(function(key, value) {
                let quantity = parseInt(($(value).find('.quantity')).val());
                let price = $(value).find('.select-item').find(':selected').data('price');
                let total = price * quantity;
                total_price += total;
            });
            let customer_payment = parseFloat(modal_body.find('.customer-payment').val());
            if(!isNaN(customer_payment))
            {
                let object = modal_body.find('.form-create');
                let form_data = new FormData(object[0]);
                $.ajax({
                    type: "post",
                    url: '{{ route('invoice.update') }}',
                    data: form_data,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        modal_body.find('.remaining-money').html(response.data.toLocaleString('vi-VN'));
                        modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", false);
                    },
                    error: function(error) {
                        modal_body.find('.remaining-money').html('NULL');
                        modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", true);
                    }
                });
            }

            modal_body.find('.total-price').val(total_price.toLocaleString('vi-VN'));
        }
        $(document).ready(function() {
            $(".select-item").select2({
                tag: true
            });
            $('.btn-table').click(function() {
                var table_id = $(this).data('table-id');
                $("#table-id").val(table_id);
                var modal_inoice = "#modal-invoice-" + table_id;
                $(modal_inoice).modal("show");

            })
            $('.btn-close').click(function() {
                $("#modal-invoice").modal('toggle');
                $("#search").val('');
            })
            $(".form-row .select-item").on('change', function() {
                let formRow = $(this).closest('.form-row');
                let quantityInput = formRow.find('.quantity').val('1');
                let btnUpdateQuantity = formRow.find('.btn-update-quantity');
                btnUpdateQuantity.attr('disabled', false);

                let modal_body = $(this).closest('.modal-body');
                updateRowTotal(formRow, modal_body);
            });


            $(".btn-update-quantity").on('click', function() {
                let formRow = $(this).closest('.form-row');
                let type = parseInt($(this).data('type'));
                let quantityInput = $(this).closest('.form-row').find('.quantity');
                let quantity = parseInt(quantityInput.val());
                if (type === 0) {
                    if (quantity > 1) {
                        quantity = quantity - 1;
                        quantityInput.val(quantity);
                    }
                } else {
                    quantity += 1;
                    quantityInput.val(quantity);
                }
                let modal_body = $(this).closest('.modal-body');
                updateRowTotal(formRow, modal_body);

            })

            $('.append').on('click', function() {
                let div = document.createElement("div");
                div.innerHTML = `
                <div class="form-group col-5 class="div-select">
                    <label for="">Món</label>
                    <select name="id[]" class="select-item">
                        <option value="0" data-price="0" selected>Chọn món</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-left: 42px;width:135px;">
                    <label for="">Quantity (Min: 1)</label>
                    <br>
                    <button
                        type="button"
                        class="btn-update-quantity"
                        data-type='0'
                        style="float: left"
                        disabled
                    >
                        -
                    </button>
                    <input type="text" id="quantity" name="quantity[]" class="quantity form-control" value="0" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" readonly>
                    <button
                        type="button"
                        class="btn-update-quantity"
                        data-type='1'
                        style="float: left;"
                        disabled
                    >
                        +
                    </button>
                </div>
                <div class="form-group col-3">
                    <span class="span-sum">
                        <label>Price</label>
                        <input type="text" id="price" class="price form-control" value=0 readonly>
                    </span>
                </div>
                <div class="form-group col-1">
                    <label>Delete</label>
                    <button
                        style="background-color: red"
                        type="button"
                        class="btn-delete btn btn-danger"
                    >
                        X
                    </button>
                </div>
                `;

                let modal_body = $(this).closest('.modal-body');
                div.classList.add("form-row")
                div.classList.add("item")
                modal_body.find('.append-item').append(div);
                $(".form-row .select-item").select2({
                    tag: true
                });
                envent();
            })

            function envent() {
                $(".form-row .select-item").on('change', function() {
                    let formRow = $(this).closest('.form-row');
                    let quantityInput = formRow.find('.quantity');
                    let btnUpdateQuantity = formRow.find('.btn-update-quantity');
                    btnUpdateQuantity.attr('disabled', false);
                    quantityInput.val('1');
                    let quantity = parseInt(quantityInput.val());

                    let modal_body = $(this).closest('.modal-body');
                    updateRowTotal(formRow, modal_body);
                });
                $(".form-row:last-child .btn-update-quantity").on('click', function() {
                    let formRow = $(this).closest('.form-row');
                    let type = parseInt($(this).data('type'));
                    let quantityInput = $(this).closest('.form-row').find('.quantity');
                    let quantity = parseInt(quantityInput.val());
                    if (type === 0 && quantity > 1) {
                        quantity = quantity - 1;
                        quantityInput.val(quantity);
                    } else if (type === 1) {
                        quantity += 1;
                        quantityInput.val(quantity);
                    }
                    let modal_body = $(this).closest('.modal-body');
                    updateRowTotal(formRow, modal_body);
                });

                $(".form-row .btn-delete").on('click', function() {
                    let divDelete = $(this).closest('.form-row');
                    divDelete.remove();

                    let modal_body = $(this).closest('.modal_body');
                    updateTotalPrice(modal_body);
                })
            }
        });

    </script>
@endpush
