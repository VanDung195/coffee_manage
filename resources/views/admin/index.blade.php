@extends('layout.master')
@push('css')
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
            /* Điều chỉnh kích thước font theo ý muốn */
        }
        .modal-container-change-invoice{
            max-width: 500px;
        }
        .icon-swap{
            margin-top: 30px;
            margin-left: 21px;
            margin-right: 21px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush
@section('content')
    <div id="left">
        <div class="header">
            <h1>Bàn</h1>
        </div>
        @foreach ($tables as $table)
            <div class="show-table" id="show_table_{{ $table->name }}" style="display: block;float: left;">
                <button class="btn-table" data-table-id="{{ $table->name }}">
                    {{ $table->name }}
                </button>
            </div>
            <div class="show-table-detail" id="show_detail_{{ $table->name }}" style="display: none;float: left;">
                <button class="btn-show-invoice-detail" data-table-id="{{ $table->name }}"
                    onclick="showInvoiceDetail('#invoice_detail_{{ $table->name }}')">
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
                                <input type="text" class="form-control" name="table-id" id="table-id"
                                    value="{{ $table->name }}" readonly>
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
                                    {{-- # Đổi id thành class ở đây --}}
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
                                        {{-- <span class="fl-right" style="margin-bottom: 20px;"> --}}
                                        <label for="">Tổng tiền: </label>
                                        <input type="text" value="0" class="total-price form-control" readonly>
                                        {{-- </span> --}}
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Số tiền khách trả: </label>
                                        <input class="customer-payment form-control" type="number" name="customer_payment"
                                            class="customer-payment" placeholder="VD: 1 = 1.000 VND" inputmode="numeric">
                                    </div>
                                    <div class="form-group col-3">
                                        <label for="">Tiền thừa: </label>
                                        <p class="remaining-money form-control">0</p>
                                        {{-- <input class="form-control" type="text" name="" id="remaining-money" readonly> --}}
                                    </div>
                                </div>
                                {{-- <button type="button" class="delete-test">Xoá div con</button> --}}
                                <button type="button" class="append btn btn-block btn-lg btn-fill btn-danger">Thêm
                                    món</button>
                            </form>
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-submit-invoice btn btn-primary" type="button" onclick="submitForm()">Reset
                                hoá đơn</button>
                            {{-- <button class="btn-submit-invoice-test btn btn-primary" type="button">test</button> --}}
                            {{-- <button class="btn-submit-invoice btn btn-success" type="button" onclick="submitForm()">Tạo hoá đơn</button> --}}
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

    {{-- Div chứa modal invoice detail --}}
    <div id="append_modal_invoice_detail">
    </div>
    <div id="modal-invoice-change">

    </div>
    <!-- Modal botstrap -->
    <div id="modal-invoice-bak" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
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
                                {{-- <span class="fl-right" style="margin-bottom: 20px;"> --}}
                                <label for="">Tổng tiền: </label>
                                <input type="text" id="total-price" value="0" class="form-control" readonly>
                                {{-- </span> --}}
                            </div>
                            <div class="form-group col-3">
                                <label for="">Số tiền khách trả: </label>
                                <input class="form-control" type="number" name="customer_payment" id="customer-payment"
                                    placeholder="VD: 1 = 1.000VND">
                            </div>
                            <div class="form-group col-3">
                                <label for="">Tiền thừa: </label>
                                <p class="form-control" id="remaining-money">0</p>
                                {{-- <input class="form-control" type="text" name="" id="remaining-money" readonly> --}}
                            </div>
                        </div>
                        {{-- <button type="button" class="delete-test">Xoá div con</button> --}}
                        <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm
                            món</button>
                    </form>
                    <br>

                </div>
                <div class="modal-footer">
                    {{-- <button id="btn-submit-invoice" type="button" onclick="submitForm()" class="btn btn-success">Tạo
                    hoá đơn</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @vite(['resources/js/app.js'])
    <script type="module">
        window.Echo.channel('order-channel')
            .listen('InvoicePlaced', (event) => {
                console.log(event);

                let formatTotalPrice = event.total_price.toLocaleString('vi-VN');
                let rowspanCount = Math.max(event.details.length, 1);

                let order_table = `
            <tr class="order_table_class_${event.table_id}">
                <td border="1" class="set-row" rowspan="${rowspanCount}">${event.table_id}<br>${event.checkin_time}<br>${event.created_at}</td>
            `;
                let count = 1;
                event.details.forEach(function(item, index) {
                    if (count != 1) {
                        order_table += `<tr class="order_table_class_${event.table_id}">`;
                    }
                    order_table += `
                <td>${item['name']}</td>
                <td class="price">${item['price']}</td>
                <td>${item['quantity']}</td>
            `;
                    if (count == 1) {
                        order_table += `
                    <td class="set-row" rowspan="${rowspanCount}">${formatTotalPrice}</td>
                    <td rowspan="${rowspanCount}"> 
                        <button class="btn btn-success btn-sm">Xuất HD</button>
                    </td>
                    <td rowspan="${rowspanCount}">
                        <button onclick="deleteInvoice('${event.table_id}','order_table_session')" class="btn btn-danger btn-sm">Xoá</button>
                    </td>
                `;
                    }
                    order_table += `</tr>`;
                    count++;
                });

                let targetRow = document.querySelector('.order-table tr:first-child');
                targetRow.insertAdjacentHTML('afterend', order_table);

            })
    </script>
    <script>

        
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    console.log("Checkbox được chọn: " + this.id);
                } else {
                    console.log("Checkbox bị hủy chọn: " + this.id);
                }
            });
        });
        //đảm bảo DOM content được load trước khi thêm sự kiện (chắc không cần)
        document.addEventListener('DOMContentLoaded', function() {

        });

        $('.select-item').on('change', function() {
            var selectedValue = $(this).val();
            var defaultOption = $(this).find('option:contains("Chọn món")');
            if (selectedValue !== "0") {
                defaultOption.prop('disabled', true);
            } else {
                defaultOption.prop('disabled', false);
            }
        });

        //khong cho thu phong (perfect)
        // window.addEventListener('wheel', function(event) {
        //     if (event.ctrlKey === true || event.metaKey) {
        //         event.preventDefault();
        //     }
        // }, { passive: false });
        /*
        // Ngăn chặn sự kiện thu phóng trên Firefox
        window.addEventListener('DOMMouseScroll', function(event) {
            if (event.ctrlKey === true || event.metaKey) {
                event.preventDefault();
            }
        }, { passive: false });

        // Ngăn chặn sự kiện thu phóng trên IE/Edge
        window.addEventListener('keydown', function(event) {
            if (event.ctrlKey === true || event.metaKey) {
                event.preventDefault();
            }
        }, { passive: false });
        */
        // let customer_payment = document.getElementById('customer_payment');
        // customer_payment.addEventListener('keyup', function(){

        // });
        let total_price_global = 0;
        $(".customer-payment").on('keyup', function() {
            let modal_body = $(this).closest('.modal-body');
            let total_price = modal_body.find('.total-price').val();
            let customer_payment = $(this).val();

            $.ajax({
                type: "get",
                url: '{{ route('invoice.update') }}',
                data: {
                    total_price: total_price,
                    customer_payment: customer_payment
                },
                dataType: "json",
                success: function(response) {
                    modal_body.find('.remaining-money').html(response.data.toLocaleString('vi-VN'));
                    modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", false);
                    // document.getElementById('remaining-money').innerHTML = response.data.toLocaleString(
                    //     'vi-VN');
                },
                error: function() {
                    modal_body.find('.remaining-money').html('NULL');
                    modal_body.closest('.modal-container').find('.btn-submit-invoice').prop( "disabled", true);
                    console.log('Sai con mịa nó rồi!!!');
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                url: '{{ route('api.invoices') }}',
                dataType: 'json',
                success: function(response) {

                    // console.log(response.data[0].total_price);
                    //invoice
                    let divapi = document.createElement("div");
                    response.data.invoices.forEach(function(item, index) {
                        let table_id_api = item.table_id;
                        let show_table_api = 'show_table_' + table_id_api;
                        let show_detail_api = 'show_detail_' + table_id_api;
                        //mọi phần tử trong Set là duy nhất, không trùng lặp và cung cấp các phương thức hiệu quả để kiểm tra các phần tử
                        const invalid_table_id = new Set(['unknow', 'unknow2', 'takeaway']);
                        // if(table_id_api != 'unknow' && table_id_api != 'unknow2' && table_id_api != 'takeaway')
                        if(!invalid_table_id.has(table_id_api))
                        {
                            document.getElementById(show_table_api).style.display = 'none';
                            document.getElementById(show_detail_api).style.display = 'block';
                        }

                        let modal_invoice_api = `
                    <div id="invoice_detail_${table_id_api}" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Hoá đơn chi tiết</h4>
                                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <h3>Bàn số: ${table_id_api}</h3>
                    `;
                        // console.log(item);
                        //invoice detail
                        item.details.forEach(function(item, index) {
                            // console.log(item);
                            modal_invoice_api += `
                        <div class="items form-row">
                            <div class="form-group col-6">
                                <label>Tên món: </label>
                                <input type="text" class="form-control" value="${item.menu_items.name}">
                            </div>
                            <div class="form-group col-2">
                                <label>Số lượng: </label>
                                <input type="text" class="form-control" id="" value="${item.quantity}">
                            </div>
                            <div class="form-group col-2">
                                <label>Giá: </label>
                                <input type="text" class="form-control" value="${(item.menu_items.price).toLocaleString('vi-VN')}" name="" id="">
                            </div>
                            <div class="form-group col-2">
                                <label>Thành tiền: </label>
                                <input type="text" class="form-control" value="${(item.quantity * item.menu_items.price).toLocaleString('vi-VN')}" name="" id="">
                            </div>
                        </div>
                        `;
                        }); //end foreach
                        // console.log(item);
                        modal_invoice_api += `
                    <div class="form-row" style="margin-top: 30px;">
                            <div class="form-group col-5" id="div-paid">
                                ${item.is_paid ? 
                                    `<input type="text" class="form-control" value="Đã thanh toán">` : 
                                    `<input type="text" class="form-control" value="Chưa thanh toán">`
                                }
                            </div> 
                            <div class="form-group col-6" style="margin-left: 60px;">
                                <h4>Tổng tiền: ${(item.total_price).toLocaleString('vi-VN')}</h4>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="deleteInvoice('${table_id_api}', 'modal_invoice')" class="btn btn-danger">Xoá hoá đơn</button>
                        <button type="button" onclick="exportInvoice()" class="btn btn-success">Xuất hoá đơn</button>
                    </div>
                    </div>
                    </div>
                    </div>
                    `;

                        let diva = document.createElement("div");
                        diva.innerHTML = modal_invoice_api;
                        diva.classList.add("form-group");
                        diva.setAttribute("id", "div_invoice_detail_" + table_id_api);

                        document.getElementById("append_modal_invoice_detail").appendChild(
                            diva);
                        console.log('---------------------');
                        // divapi.innerHTML = modal_invoice_api;
                        // console.log(modal_invoice_api);
                        // // modal_invoice_api = null;

                        // divapi.classList.add("form-group");
                        // document.getElementById("append_modal_invoice_detail").appendChild(divapi);
                        // console.log('thanh cong');
                    })


                    //Hoá đơn test
                    //-----------------------
                    // let order_table = `
                // <table class="order-table">
                //     <tr>
                //         <th>ID</th>
                //         <th>Sản phẩm</th>
                //         <th>Giá</th>
                //         <th>SL</th>
                //         <th>Tong tien</th>
                //         <th>Thành công</th>
                //     </tr>
                //     <tr>
                // `;
                    // response.data.forEach(function(item, index){
                    //     console.log(item);
                    //     let rowsspanCount = 0;
                    //     rowsspanCount = Math.max(item.details.length, 1);
                    //     order_table += `
                //         <td class="set-row" rowspan="${rowsspanCount}">${item.table_id}<br>${item.checkin_time}<br>${item.created_at}</td>
                //     `;
                    //     item.details.forEach(function(item,index){
                    //         order_table += `
                //         <td>${item.menu_items.name}</td>
                //         <td class="price">${item.menu_items.price}</td>
                //         <td>${item.quantity}</td>
                //         `;
                    //     })
                    //     order_table += `
                //         <td class="set-row" rowspan="${rowsspanCount}">${item.total_price}</td>
                //         <td class="set-row" class="status-success" rowspan="${rowsspanCount}">Thành công</td>
                //     </tr>
                //     `;
                    // })
                    // order_table += `
                //     </tr>
                // </table>
                // `;  ------------------
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

                    response.data.invoices.forEach(function(item, index) {
                        let div_modal_change_invoice = document.createElement('div');
                        div_modal_change_invoice.classList.add('form-group');

                        let modal_change_invoice = ``;
                        //modal để đổi thông tin hoá đơn (đổi bàn hoặc cũng có thể làm thêm số tiền khách trả)
                        console.log(123123123);
                        modal_change_invoice = `
                            <div class="modal-change-invoice-${item.table_id} modal fade" role="dialog">
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
                                                <input type="hidden" class="payment-status" name="payment-status" value="${item.is_paid}">
                                                <div class="form-row">
                                                    <div class="form-group col-5">
                                                        <label>Từ bàn</label>
                                                        <p class="from-table form-control">${item.table_id}</p>
                                                    </div>
                                                    <div class="icon-swap form-group"> 
                                                        <i style="font-size: 35px" class=" uil-exchange-alt"></i>
                                                    </div>
                                                    <div class="form-group col-5">
                                                        <label>Tới bàn</label>
                                                        <select name="to_table" class="select-to-table">
                                                            @foreach ($table_names_available as $item)
                                                                <option value="{{ $item['name'] }}" data-table="{{ $item['name'] }}">
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

                        let rowspanCount = Math.max(item.details.length, 1);
                        order_table += `<tr data-status="${item.is_paid}" class="order_table_class_${item.table_id}">`;
                        order_table += `
                            <td border="1" class="set-row" rowspan="${rowspanCount}">
                                ${item.table_id}
                                <br>${item.checkin_time}<br>${item.created_at}
                            </td>
                        `;

                        let count = 1;
                        item.details.forEach(function(detail, index) {
                            if (count != 1) {
                                order_table +=
                                    `<tr class="order_table_class_${item.table_id}">`;
                            }
                            order_table += `
                                <td>${detail.menu_items.name}</td>
                                <td class="price">${detail.menu_items.price}</td>
                                <td>${detail.quantity}</td>
                            `;
                            if (count == 1) {
                                order_table += `
                                    <td class="set-row" rowspan="${rowspanCount}">${item.total_price.toLocaleString('vi-VN')}</td>
                                    <td rowspan="${rowspanCount}"> 
                                        <button class="btn btn-success btn-sm">Xuất</button>
                                    </td>
                                    <td rowspan="${rowspanCount}">
                                        <li class="list-inline-item ml-2">
                                            ${item.is_paid ? 
                                            `<div style="font-size: 15px;" class="badge badge-success p-2s">Đã TT</div>` : 
                                            `<div style="font-size: 15px;" class="badge badge-secondary p-2s">Chưa TT</div>`
                                            }
                                        </li>
                                    </td>
                                    <td rowspan="${rowspanCount}">
                                        <button type="button" data-table-name="${item.table_id}" class="btn-change-invoice btn btn-danger btn-sm">Change</button>
                                    </td>
                                    <td rowspan="${rowspanCount}'">
                                        ${item.customer_payment}
                                    </td>
                                    <td rowspan="${rowspanCount}'">
                                        ${item.remaining_money}
                                    </td>
                                    <td rowspan="${rowspanCount}">
                                        <button onclick="deleteInvoice('${item.table_id}','order_table')" class="btn btn-danger btn-sm">Xoá</button>
                                    </td>
                                `;
                                    //cách 2 để dùng hiển thị cái badge
                                    // if (item.is_paid) {
                                    //     order_table += `
                                //         <div style="font-size: 15px;" class="badge badge-success p-2s">Đã TT</div>
                                //     `;
                                    // } else {
                                    //     order_table += `
                                //         <div style="font-size: 15px;" class="badge badge-secondary p-2s">Chưa TT</div>
                                //     `;
                                    // }
                            }
                            order_table += `</tr>`;
                            count++;
                        });
                    });
                    order_table += `
            </table>
        `;      
                // console.log(order_table);
                    let div_table = document.createElement("div");
                    div_table.innerHTML = order_table;
                    div_table.classList.add("form-group");
                    document.getElementById("right").appendChild(div_table);
                    // console.log(order_table);

                    console.log('Đây là số lượng tr');
                    let table = document.getElementById('order-table-id');
                    let rows = table.getElementsByTagName('tr');
                    console.log(rows.length);

                    //apend modal change invoice information
                    // let div_modal_change_invoice = document.createElement('div');
                    // div_modal_change_invoice.innerHTML = modal_change_invoice;
                    // div_modal_change_invoice.classList.add('form-group');
                    // document.getElementById('modal-invoice-change').appendChild(div_modal_change_invoice);

                    $('.form-row .select-to-table').select2({
                        tag: true
                    });
                    event_change_invoice();
                    /*
                    // console.log(response.data.table_names_available);
                    let table_available = response.data.table_names_available;
                    // console.log(table_available);
                    table_available.forEach(function(table,index){
                        console.log(table);
                    })*/
                },
                error: function(error) {
                    console.log(error);
                    console.log('Sai mia no roi may');
                }
            });


           
            // $(document).on('click', '.btn-change-invoice', function() {
            //     var tableName = $(this).data('table-name');
            //     console.log('Button clicked for table: ' + tableName);
            // });
        });

        // setTimeout(()=> {
        //     $('.btn-change-invoice').on('click', function(){
        //         let table_name = $(this).data('table-name');
        //         let modal_change_invoice = '.modal-change-invoice-'+table_name;
        //         $(modal_change_invoice).modal('show');
        //         console.log(123);
        //     })
        // }, 800);
        //delegation
        
        // setTimeout(() => {
        //     $('.btn-submit-change-invoice').on('click', function(){
        //             let modal_content = $(this).closest('.modal-content');
        //             let from_table = modal_content.find('.from-table').text();
        //             let to_table = modal_content.find('.select-to-table').val();
        //             let payment_status = modal_content.find('.payment-status').val();
        //             let csrf_token = modal_content.find('input[name="_token"]').val();
        //             console.log(from_table, to_table, payment_status);
        //             $.ajax({
        //                 type: 'post',
        //                 url: '{{ route('table_update') }}',
        //                 data: {
        //                     from_table: from_table,
        //                     to_table: to_table,
        //                     payment_status: payment_status,
        //                     _token: csrf_token
        //                 },
        //                 dataType: "json",
        //                 success: function (response) {
        //                     console.log(response);
        //                 }
        //             });
        //         }); 
        // }, 800);


        //https://stackoverflow.com/questions/7114780/convert-jquery-element-to-html-element
        $('.btn-submit-invoice').on('click', function() {
            let modal_content = $(this).closest('.modal-content');
            let obj = modal_content.find('.form-create');
            let object = $('#form-create');
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
                    let table_id = response.data.table_id;
                    let total_price = response.data.total_price;
                    let formatTotalPrice = total_price.toLocaleString('vi-VN');
                    let show_table = 'show_table_' + table_id;
                    let show_detail = 'show_detail_' + table_id;
                    let index = response.data.index;
                    let invoice_item = response.data.details;
                    let data = response.data;

                    //modal change invoice
                    let div_modal_change_invoice = document.createElement('div');
                    div_modal_change_invoice.classList.add('form-group');

                    let modal_change_invoice = ``;
                    //modal để đổi thông tin hoá đơn (đổi bàn hoặc cũng có thể làm thêm số tiền khách trả)
                    console.log(123123123);
                    modal_change_invoice = `
                        <div class="modal-change-invoice-${table_id} modal fade" role="dialog">
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
                                            <input type="hidden" class="payment-status" name="payment-status" value="${item.is_paid}">
                                            <div class="form-row">
                                                <div class="form-group col-5">
                                                    <label>Từ bàn</label>
                                                    <p class="from-table form-control">${table_id}</p>
                                                </div>
                                                <div class="icon-swap form-group"> 
                                                    <i style="font-size: 35px" class=" uil-exchange-alt"></i>
                                                </div>
                                                <div class="form-group col-5">
                                                    <label>Tới bàn</label>
                                                    <select name="to_table" class="select-to-table">
                                                        @foreach ($table_names_available as $item)
                                                            <option value="{{ $item['name'] }}" data-table="{{ $item['name'] }}">
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

                    // console.log('đây là response: ');
                    // console.log(response);
                    modal_invoice_close.modal('toggle');
                    let modal_invoice = `
                    <div id="invoice_detail_${table_id}" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Hoá đơn chi tiết</h4>
                                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <h3>Bàn số: ${table_id}</h3>
                                    
                `;
                    let rowspanCount = Math.max(response.data.details.length, 1);
                    let order_table = `
                        <tr data-status="${response.data.is_paid}" class="order_table_class_${table_id}">
                            <td border="1" class="set-row" id="new-row-${table_id}" rowspan="${rowspanCount}">${data.table_id} <span class="new-invoice-check badge badge-success p-2s">(New)</span>
                                <br>${data.checkin_time}<br>${data.created_at}
                            </td>
                        `;

                    setTimeout(()=> {
                        $(`#new-row-${table_id} .new-invoice-check`).remove();
                    }, 10000);
                        let count = 1;
                    //foreach of details
                    invoice_item.forEach(function(item, index) {
                        console.log(item);
                        modal_invoice += `
                                <div class="items form-row">
                                    <div class="form-group col-6">
                                        <label>Tên món: </label>
                                        <input type="text" class="form-control" value="${item['name']}">
                                    </div>
                                    <div class="form-group col-2">
                                        <label>Số lượng: </label>
                                        <input type="text" class="form-control" id="" value="${item['quantity']}">
                                    </div>
                                    <div class="form-group col-2">
                                        <label>Giá: </label>
                                        <input type="text" class="form-control" value="${item['price'].toLocaleString('vi-VN')}" name="" id="">
                                    </div>
                                    <div class="form-group col-2">
                                        <label>Thành tiền: </label>
                                        <input type="text" class="form-control" value="${item['thanh_tien'].toLocaleString('vi-VN')}" name="" id="">
                                    </div>
                                </div>
                                `;
                        //!=modal
                        if (count != 1) {
                            order_table += `<tr data-status="${response.data.is_paid}" class="order_table_class_${table_id}">`;
                        }
                        order_table += `
                            <td>${item['name']}</td>
                            <td class="price">${item['price']}</td>
                            <td>${item['quantity']}</td>
                        `;
                        if (count == 1) {
                            order_table += `
                            <td class="set-row" rowspan="${rowspanCount}">${formatTotalPrice}</td>
                            <td rowspan="${rowspanCount}"> 
                                <button class="btn btn-success btn-sm">Xuất</button>
                            </td>
                            <td rowspan="${rowspanCount}">
                                <li class="list-inline-item ml-2">
                                    ${parseInt(response.data.is_paid) ? 
                                    `<div style="font-size: 15px;" class="badge badge-success p-2s">Đã TT</div>` : 
                                    `<div style="font-size: 15px;" class="badge badge-secondary p-2s">Chưa TT</div>`
                                    }
                                </li>
                            </td>
                            <td rowspan="${rowspanCount}">
                                <button type="button" data-table-name="${table_id}" class="btn-change-invoice btn btn-danger btn-sm">Change</button>
                            </td>
                            <td rowspan="${rowspanCount}">
                                ${response.data.customer_payment}
                            </td>
                            <td rowspan="${rowspanCount}">
                                ${response.data.remaining_money}
                            </td>
                            <td rowspan="${rowspanCount}">
                                <button onclick="deleteInvoice('${table_id}','order_table')" class="btn btn-danger btn-sm">Xoá</button>
                            </td>
                        `;
                        }
                        order_table += `</tr>`;
                        count++;
                    });
                    //end foreach
                    modal_invoice += `
                        <div class="form-row" style="margin-top: 30px;">
                            <div class="form-group col-5" id="div-paid">
                                ${parseInt(response.data.is_paid) ? 
                                    `<input type="text" class="form-control" value="Đã thanh toán">` : 
                                    `<input type="text" class="form-control" value="Chưa thanh toán">`
                                }
                            </div> 
                            <div class="form-group col-6" style="margin-left: 60px;">
                                <h4>Tổng tiền: ${formatTotalPrice}</h4>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="deleteInvoice('${table_id}', 'modal_invoice')" class="btn btn-danger">Xoá hoá đơn</button>
                        <button type="button" onclick="exportInvoice()" class="btn btn-success">Xuất hoá đơn</button>
                    </div>
                    </div>
                    </div>
                    </div>
                `;

                    let div2 = document.createElement("div");
                    div2.innerHTML = modal_invoice;
                    div2.classList.add("form-group");
                    div2.setAttribute("id", "div_invoice_detail_" + table_id);
                    document.getElementById("append_modal_invoice_detail").appendChild(div2);

                    const invalid_table_id = new Set(['unknow', 'unknow2', 'takeaway']);
                    // if(table_id_api != 'unknow' && table_id_api != 'unknow2' && table_id_api != 'takeaway')
                    if(!invalid_table_id.has(table_id))
                    // if(table_id != 'unknow' && table_id != 'unknow2' && table_id != 'takeaway')
                    {
                        //show_table la cai nut de mo invoice and invoice detail
                        document.getElementById(show_table).style.display = 'none';
                        document.getElementById(show_detail).style.display = 'block';
                    }
                    //show_table la cai nut de mo invoice and invoice detail
                    // document.getElementById(show_table).style.display = 'none';
                    // document.getElementById(show_detail).style.display = 'block';

                    //inner order table 
                    // let targetRow = document.querySelector('.order-table tr:first-child');
                    // targetRow.insertAdjacentHTML('beforeend', order_table);

                    let table = document.getElementById('order-table-id');
                    let rows = table.getElementsByTagName('tr');
                    //limit the use of else 
                    /*
                    if(response.data.is_paid == 1)
                    {
                        //Chèn dòng invoice vào table nếu is_paid == 1 and chèn ở trên invoice đã thanh toán
                        if(rows.length == 1)
                        {
                            let targetRow = document.querySelector('.order-table tr:first-child');
                            targetRow.insertAdjacentHTML('afterend', order_table);
                            // return;
                        }

                        if(rows.length > 1)
                        {
                            for(let i = 1; i <= rows.length; i++)
                            {
                                if(rows[i+1].getAttribute('data-status') == '1')
                                {
                                    console.log(rows[i]);
                                    rows[i].insertAdjacentHTML('afterend', order_table);
                                    // return;
                                }
                            }
                        }
                    }

                    if(response.data.is_paid == 0)
                    {
                        let targetRow = document.querySelector('.order-table tr:first-child');
                        targetRow.insertAdjacentHTML('afterend', order_table);
                    }*/

                    if(response.data.is_paid == 1 && rows.length > 1)
                    {
                        let inserted = false;
                        //1 is the first tr tag
                        for(let i = 1; i < rows.length; i++)
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
                        // if(!inserted)
                        // {
                        //     console.log(12312);
                        // }
                    }
                    if(response.data.is_paid == 1 && rows.length == 1)
                    {
                        console.log('Đây là trường hợp khi chưa có dòng dữ liệu nào trong table');
                        let targetRow = document.querySelector('.order-table tr:first-child');
                        targetRow.insertAdjacentHTML('afterend', order_table);
                    }
                    if(response.data.is_paid == 0)
                    {
                        //Mặc định khi thêm 1 hoá đơn chưa thanh toán vào session thì nó luôn thêm vào đầu tiên của bảng
                        let targetRow = document.querySelector('.order-table tr:first-child');
                        console.log('Đây là targetrow');
                        console.log(targetRow);
                        targetRow.insertAdjacentHTML('afterend', order_table);
                    }

                    //reset modal after tạo invoice  modal-invoice-close
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
                    // while (child_div.length > 0) {
                    //     child_div.get(0).remove(); 
                    //     child_div = parent_div.find('.form-row'); 
                    // }

                    document.getElementById('remaining-money').textContent = "0";
                    

                    // $(document).on('click', '.btn-change-invoice', function(){
                    //     let table_name = $(this).data('table-name');
                    //     let modal_change_invoice = '.modal-change-invoice-' + table_name;
                    //     $(modal_change_invoice).modal('show');
                    //     console.log(123);
                    // });

                },
                error: function(response) {
                    console.log(response.responseJSON.message);
                    $.toast({
                        heading: 'Error',
                        text: response.responseJSON.message,
                        showHideTransition: 'slide',
                        icon: 'error'
                    })
                }
            });
        });

        $(document).on('click', '.btn-change-invoice', function(){
            let table_name = $(this).data('table-name');
            let modal_change_invoice = '.modal-change-invoice-' + table_name;
            $(modal_change_invoice).modal('show');
            console.log(123);
        });
        $(document).on('click', '.btn-submit-change-invoice', function(){
            let modal_content = $(this).closest('.modal-content');
            let from_table = modal_content.find('.from-table').text();
            let to_table = modal_content.find('.select-to-table').val();
            let payment_status = modal_content.find('.payment-status').val();
            let csrf_token = modal_content.find('input[name="_token"]').val();
            
            $.ajax({
                type: 'post',
                url: '{{ route('table_update') }}',
                data: {
                    from_table: from_table,
                    to_table: to_table,
                    payment_status: payment_status,
                    _token: csrf_token
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });

        /*
        $(document).on('click', '.btn-change-invoice', function(){
            let table_name = $(this).data('table-name');
            let modal_change_invoice = '.modal-change-invoice-' + table_name;
            $(modal_change_invoice).modal('show');
            console.log(123);
        });
        $(document).on('click', '.btn-submit-change-invoice', function(){
            let modal_content = $(this).closest('.modal-content');
            let from_table = modal_content.find('.from-table').text();
            let to_table = modal_content.find('.select-to-table').val();
            let payment_status = modal_content.find('.payment-status').val();
            let csrf_token = modal_content.find('input[name="_token"]').val();
            
            $.ajax({
                type: 'post',
                url: '{{ route('table_update') }}',
                data: {
                    from_table: from_table,
                    to_table: to_table,
                    payment_status: payment_status,
                    _token: csrf_token
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });*/


        // function showModal() {
        //     $("#modal-invoice").modal("show");
        // }

        function showInvoiceDetail(table_name) {
            $(table_name).modal("show");
        }

        function close_modal() {
            $('#modal-invoice').modal('toggle');
        }

        function deleteInvoice(table_name, type) {
            console.log(type);
            $.ajax({
                type: 'get',
                url: '{{ route('table.update') }}',
                data: {
                    table_name
                },
                success: function(response) {
                    //delete invoice detail modal
                    let modal_invoice = "#invoice_detail_" + table_name;
                    if (type == 'modal_invoice') {
                        $(modal_invoice).modal('toggle');
                    }
                    let div_invoice = "div_invoice_detail_" + table_name;
                    let divR = document.getElementById(div_invoice);
                    divR.remove();

                    //switch tu red button to green button
                    let btn_show_table = "show_table_" + table_name;
                    let btn_show_invoice_detail = "show_detail_" + table_name;
                    document.getElementById(btn_show_table).style.display = 'block';
                    document.getElementById(btn_show_invoice_detail).style.display = 'none';

                    // <div class=" fade show"></div>
                    let modal_bg = document.getElementsByClassName('modal-backdrop');
                    modal_bg.remove;

                    //remove tr table
                    let elements = document.querySelectorAll('.order_table_class_' + table_name);
                    elements.forEach(function(element) {
                        console.log(element);
                        element.remove();
                    })
                    console.log('thanh cmn cong roi');
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
            modal_body.find('.total-price').val(total_price.toLocaleString('vi-VN'));


            /*
            console.log('updateTotalPrice');
            // let formRow = $(this).closest('.form-row');
            // let type = parseInt($(this).data('type'));
            // let quantityInput = $(this).closest('.form-row').find('.quantity');
            // let item = form_row.find('.item');
            let item  = modal_content.find('.item');
            console.log(item);
            console.log($('.item'));
            let total = 0;
            // item.each(function() {
            $(".item").each(function() {
                let quantity = parseInt($(this).find('.quantity').val());
                let price = $(this).find(".select-item").find(":selected").data("price");
                let totalPrice = quantity * price;
                total += price * quantity;
            })
            console.log(total);
            // console.log(total_price_global);
            $("#total-price").val(total.toLocaleString('vi-VN'));*/
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

            //reset modal khi đóng modal
            // $('#modal-invoice').on('hidden.bs.modal', function() {
            //     $(this).find('form').trigger('reset');
            //     $(".select-item").select2({
            //         tag: true
            //     });
            //     let parentDiv = document.getElementById('append-item');
            //     let childDiv = parentDiv.getElementsByClassName('form-row');
            //     while (childDiv.length > 0) {
            //         parentDiv.removeChild(childDiv[0]);
            //     }
            //     document.getElementById('remaining-money').textContent = "0";
            // });
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
                // console.log(quantityInput);
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
                // // Sử dụng closest để tìm đến các phần tử trong cùng một form-row
                // $(this).closest('.form-row').find("#price").val(sum);
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
                // document.getElementById('append-item').appendChild(div);
                $(".form-row .select-item").select2({
                    tag: true
                });
                envent();
            })
            /*
            let add_btns = document.getElementsByClassName('append');
            Array.from(add_btns).forEach(function(add_btn) {
                add_btn.addEventListener('click', function() {
                    console.log(123123123123);
                });
            });*/


            function envent() {
                $(".form-row .select-item").on('change', function() {
                    // $(".btn-update-quantity").attr('disabled', false);
                    let formRow = $(this).closest('.form-row');
                    let quantityInput = formRow.find('.quantity');
                    let btnUpdateQuantity = formRow.find('.btn-update-quantity');
                    btnUpdateQuantity.attr('disabled', false);
                    quantityInput.val('1');
                    let quantity = parseInt(quantityInput.val());
                    // let price = $(this).find(":selected").data("price");
                    // let sum = price * quantity;

                    // // $("#price").val(sum);
                    // let price = $(this).closest('.form-row').find(".select-item").find(":selected").data("price");
                    // let sum = price * quantity;
                    // // Sử dụng closest để tìm đến các phần tử trong cùng một form-row
                    // $(this).closest('.form-row').find("#price").val(sum);
                    let modal_body = $(this).closest('.modal-body');
                    updateRowTotal(formRow, modal_body);
                });
                // Sự kiện cho nút tăng giảm trong form-row mới
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
