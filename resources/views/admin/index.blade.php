@extends('layout.master')
@push('css')
<style>
   
</style>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush
@section('content')
<div id="left">
    @foreach ($tables as $table)
        <div class="show-table" id="show_table_{{ $table->name }}" style="display: block;float: left;">
            <button class="btn-table" data-table-id="{{ $table->name }}">
                {{ $table->name }}
            </button>
        </div>
        <div class="show-table-detail" id="show_detail_{{ $table->name }}" style="display: none;float: left;">
            <button class="btn-show-invoice-detail" data-table-id="{{ $table->name }}" onclick="showInvoiceDetail('#invoice_detail_{{ $table->name }}')">
                {{$table->name}}
            </button>
        </div>
    @endforeach
</div>
<div id="right">
    <div class="header">
        <h1>Quản Lý Hoá Đơn</h1>
    </div>

    
    {{-- <table class="order-table">
        <tr>
            <th>ID</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>SL</th>
            <th>Tong tien</th>
            <th>Thành công</th>
        </tr>
        //đây là chỗ tôi muốn thêm order_table, hi
        <tr>
            <td class="set-row" rowspan="1">296332349<br>16/03 03:10</td>
            <td>Bàn chải điện Huawei Lebooo Smart Sonic Star Diamond - Trắng</td>
            <td class="price">399.000₫</td>
            <td>1</td>
            <td class="set-row" rowspan="1">1</td>
            <td class="set-row" rowspan="1" class="status-success">Thành công</td>
        </tr>
        <tr>
            <td class="set-row" rowspan="3">204858587<br>17:24 28/09</td>
            <td>
                Combo Cáp Lightning Remax + Sạc Baseus cho Iphone sạc nhanh 20W - Trắng
            </td>
            <td class="price">199.000₫</td>
            <td>1</td>
            <td class="set-row" rowspan="3">123</td>
            <td class="set-row" rowspan="3" class="status-success">Chap nhan</td>
        </tr>
        <tr>
            <td>
                Combo Cáp Lightning Remax + Sạc Baseus cho Iphone sạc nhanh 20W - Đen
            </td>
            <td class="price">199.000₫</td>
            <td>1</td>
        </tr>
        <tr>
            <td>
                Combo Type-C Cáp Remax + Sạc Baseus cho Android sạc nhanh 20W -Đen
            </td>
            <td class="price">1.088.000₫</td>
            <td>1</td>
        </tr>
    </table> --}}
</div>


{{-- Div chứa modal invoice detail --}}
<div id="append_modal_invoice_detail">
    
</div>

<!-- Modal botstrap -->
<div id="modal-invoice" class="modal fade" role="dialog">
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
                                <option value="{{$item->id}}" data-price="{{ $item->price }}">
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-left: 42px;width:135px;">
                            <label for="">Số lượng (Min: 1)</label>
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
                        <input type="text" id="quantity" name="quantity[]" class="quantity form-control" value="0" style="background-color: none;border:none;height:30px;width:42px;float: left;" readonly>
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
                        <label>Giá</label>
                        <input type="text" id="price" class="price form-control" readonly>
                    </span>
                </div>
            </div>
            <div id="append-item">

            </div>
            <div class="form-row" style="margin-top: 30px;">
                <div class="form-group col-5" id="div-paid">
                    <select name="is_paid" id="select_paid" class="form-control">
                        <option value="0">Chưa thanh toán</option>
                        <option value="1" selected>Đã thanh toán</option>
                    </select>
                </div> 
                <div class="form-group col-2" style="margin-left: 60px;">
                    <h4>Tổng tiền: </h4>
                </div>
                <div class="form-group col-4" style="margin-top: 5px;margin-left:0px;">
                    <span class="fl-right" style="margin-bottom: 20px;">
                        <input type="text" id="total-price" value="0" class="form-control" readonly>
                    </span>
                </div>
            </div>
            {{-- <button type="button" class="delete-test">Xoá div con</button> --}}
            <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
        </form>
        <br>
        
    </div>
    <div class="modal-footer">
        <button type="button" onclick="submitForm()" class="btn btn-success" >Tạo hoá đơn</button>
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
            event.details.forEach(function(item, index){
                if(count != 1)
                {
                    order_table += `<tr class="order_table_class_${event.table_id}">`;
                }
                order_table += `
                    <td>${item['name']}</td>
                    <td class="price">${item['price']}</td>
                    <td>${item['quantity']}</td>
                `;
                if(count == 1)
                {
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
function test(t){
    console.log(t);
    let testt = document.getElementById(t);
    // testt.setAttribute("disabled", "");
}
function table_invoice(response)
{

}
$(document).ready(function () {
    $.ajax({
        url: '{{ route('api.invoices') }}',
        dataType: 'json',
        success: function (response) {
            // console.log(response.data[0].total_price);
            //invoice
            console.log(response.data);
            let divapi = document.createElement("div");
            response.data.forEach(function (item, index){
                let table_id_api = item.table_id;
                let show_table_api = 'show_table_'+table_id_api;
                let show_detail_api = 'show_detail_'+table_id_api; 
                document.getElementById(show_table_api).style.display = 'none';
                document.getElementById(show_detail_api).style.display = 'block';
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
                item.details.forEach(function (item, index){
                    // console.log(item.menu_items.name);
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
                })
                modal_invoice_api += `
                <div class="form-row" style="margin-top: 30px;">
                        <div class="form-group col-5" id="div-paid">
                            <input type="text" class="form-control" value="Đã thanh toán">
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
                diva.setAttribute("id","div_invoice_detail_"+table_id_api);

                document.getElementById("append_modal_invoice_detail").appendChild(diva);
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
                <table class="order-table">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Tổng tiền</th>
                        <th>Xuất</th>
                        <th>Xoá</th>
                    </tr>
            `;

            response.data.forEach(function(item, index){
                console.log(item);
                let rowspanCount = Math.max(item.details.length, 1);
                order_table += `<tr class="order_table_class_${item.table_id}">`;
                order_table += `
                    <td border="1" class="set-row" rowspan="${rowspanCount}">${item.table_id}<br>${item.checkin_time}<br>${item.created_at}</td>
                `;
                let count = 1;
                item.details.forEach(function(detail, index){
                    if(count != 1) {
                        order_table += `<tr class="order_table_class_${item.table_id}">`;
                    }
                    order_table += `
                        <td>${detail.menu_items.name}</td>
                        <td class="price">${detail.menu_items.price}</td>
                        <td>${detail.quantity}</td>
                    `;
                    if(count == 1) {
                        order_table += `
                            <td class="set-row" rowspan="${rowspanCount}">${item.total_price.toLocaleString('vi-VN')}</td>
                            <td rowspan="${rowspanCount}"> 
                                <button class="btn btn-success btn-sm">Xuất HD</button>
                            </td>
                            <td rowspan="${rowspanCount}">
                                <button onclick="deleteInvoice('${item.table_id}','order_table')" class="btn btn-danger btn-sm">Xoá</button>
                            </td>
                        `;
                    }
                    order_table += `</tr>`;
                    count++;
                });
            });
            order_table += `
                </table>
            `;
            let div_table = document.createElement("div");
            div_table.innerHTML = order_table;
            div_table.classList.add("form-group");
            document.getElementById("right").appendChild(div_table);
            console.log(order_table);
        },
        error: function (error) {
            console.log(error);
            console.log('Sai mia no roi may');
        }
    });
});
    function submitForm() {
        console.log(111111);
        const obj = $("#form-create");
        let formData = new FormData(obj[0]);
        console.log(formData);
        $.ajax({
            type: 'post',
            url: obj.attr('action'),
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                let table_id = response.data.table_id;
                let total_price = response.data.total_price;
                let formatTotalPrice = total_price.toLocaleString('vi-VN');
                let show_table = 'show_table_'+ table_id;
                let show_detail = 'show_detail_'+table_id;
                let index = response.data.index;
                let invoice_item = response.data.details;
                let data = response.data;
                console.log(response.data);

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
                <tr class="order_table_class_${table_id}">
                    <td border="1" class="set-row" rowspan="${rowspanCount}">${data.table_id}<br>${data.checkin_time}<br>${data.created_at}</td>
                `;
                let count = 1;
                invoice_item.forEach(function(item, index) {
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

                    if(count != 1)
                    {
                        order_table += `<tr class="order_table_class_${table_id}">`;
                    }
                    order_table += `
                        <td>${item['name']}</td>
                        <td class="price">${item['price']}</td>
                        <td>${item['quantity']}</td>
                    `;
                    if(count == 1)
                    {
                        order_table += `
                            <td class="set-row" rowspan="${rowspanCount}">${formatTotalPrice}</td>
                            <td class="set-row" rowspan="${rowspanCount}">
                            <td rowspan="${rowspanCount}"> 
                                <button class="btn btn-success btn-sm">Xuất HD</button>
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
                            <input type="text" class="form-control" value="Đã thanh toán">
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
                div2.setAttribute("id","div_invoice_detail_"+table_id);
                document.getElementById("append_modal_invoice_detail").appendChild(div2);
                //show_table la cai nut de mo invoice and invoice detail
                document.getElementById(show_table).style.display = 'none';
                document.getElementById(show_detail).style.display = 'block';

                //inner order table 
                let targetRow = document.querySelector('.order-table tr:first-child');
                    targetRow.insertAdjacentHTML('afterend', order_table);

                //thêm vào cuối
                // let existing_table = document.querySelector(".order-table");
                // existing_table.insertAdjacentHTML('beforeend', order_table);


                $('#modal-invoice').modal('toggle');

            },
            error: function(response) {
                console.log(3232);
                console.log(response);
            }
        });
    }
    function showModal() {
        $("#modal-invoice").modal("show");
    }
    function showInvoiceDetail(table_name){
        $(table_name).modal("show");
    }
    function closeModal() {
        $('#modal-invoice').modal('toggle');
    }
    function deleteInvoice(table_name,type) {
        console.log(type);
        $.ajax({
            type: 'get',
            url: '{{ route('table.update') }}',
            data: {table_name},
            success: function (response) {
                

                let modal_invoice = "#invoice_detail_"+table_name;
                if(type == 'modal_invoice')
                {
                    $(modal_invoice).modal('toggle');
                }

                let div_invoice = "div_invoice_detail_"+table_name;
                let divR = document.getElementById(div_invoice);
                divR.remove();

                let btn_show_table = "show_table_"+table_name;
                let btn_show_invoice_detail = "show_detail_"+table_name;
                document.getElementById(btn_show_table).style.display = 'block';
                document.getElementById(btn_show_invoice_detail).style.display = 'none';

                // <div class=" fade show"></div>
                let modal_bg = document.getElementsByClassName('modal-backdrop');
                modal_bg.remove;

                //remove tr table
                let elements = document.querySelectorAll('.order_table_class_'+table_name);
                elements.forEach(function(element){
                    console.log(element);
                    element.remove();
                })
                console.log('thanh cmn cong roi');
            }
        });
    }
    function updateRowTotal(formRow) {
        let quantity = parseInt(formRow.find('.quantity').val());
        let price = formRow.find(".select-item").find(":selected").data("price");
        let sum = price * quantity;
        formRow.find(".price").val(sum.toLocaleString('vi-VN'));
        updateTotalPrice();
    }

    function updateTotalPrice() {
        console.log(12387123987123871289);
        let total = 0;
        $(".item").each(function(){
            let quantity = parseInt($(this).find('.quantity').val());
            let price = $(this).find(".select-item").find(":selected").data("price");
            let totalPrice = quantity*price;
            total += price * quantity;
        })
        console.log(total);
        $("#total-price").val(total.toLocaleString('vi-VN'));
    }
    $(document).ready(function () {
        $(".select-item").select2({tag: true});
        $('.btn-table').click(function(){
            var tableId = $(this).data('table-id');
            $("#table-id").val(tableId);
            $("#price").val('0')
            $(".quantity").val('0')
            $("#modal-invoice").modal("show");

        })
        $('.btn-close').click(function(){
            $("#modal-invoice").modal('toggle');
            $("#search").val('');
        })

        //reset modal khi đóng modal
        $('#modal-invoice').on('hidden.bs.modal', function(){
            $(this).find('form').trigger('reset');
            $(".select-item").select2({tag: true});
            let parentDiv = document.getElementById('append-item');
            let childDiv = parentDiv.getElementsByClassName('form-row');
            while(childDiv.length > 0) {
                parentDiv.removeChild(childDiv[0]);
            }
        });
        $(".form-row .select-item").on('change', function(){
            let formRow = $(this).closest('.form-row');
            let quantityInput = formRow.find('.quantity').val('1');
            let btnUpdateQuantity = formRow.find('.btn-update-quantity');
            btnUpdateQuantity.attr('disabled', false);
            updateRowTotal(formRow);
        });
        

        $(".btn-update-quantity").on('click', function(){
            let formRow = $(this).closest('.form-row');
            let type = parseInt($(this).data('type'));
            let quantityInput = $(this).closest('.form-row').find('.quantity');
                // console.log(quantityInput);
            let quantity = parseInt(quantityInput.val());
            if(type===0)
            {
                if(quantity>1){
                    quantity = quantity - 1;
                    quantityInput.val(quantity);
                }
            }else{
                quantity += 1;
                quantityInput.val(quantity);
            }
                // // Sử dụng closest để tìm đến các phần tử trong cùng một form-row
                // $(this).closest('.form-row').find("#price").val(sum);
            updateRowTotal(formRow);
            
        })
        
        //Append item 
        var addBtn = document.getElementById('append');
        addBtn.addEventListener('click', function(){
            console.log(1);

            let div = document.createElement("div");
            div.innerHTML = `
                <div class="form-group col-5 class="div-select">
                    <label for="">Món</label>
                    <select name="id[]" class="select-item">
                        <option value="0" data-price="0" selected>Chọn món</option>
                        @foreach ($items as $item)
                        <option value="{{$item->id}}" data-price="{{ $item->price }}">
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
                        class="btn-delete"
                    >
                        X
                    </button>
                </div>
            `;

            div.classList.add("form-row")
            div.classList.add("item")
            document.getElementById('append-item').appendChild(div);
            $(".form-row .select-item").select2({tag: true});
            envent();
        })


        function envent()
        {
            $(".form-row .select-item").on('change', function(){
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
                updateRowTotal(formRow);
            });
                // Sự kiện cho nút tăng giảm trong form-row mới
            $(".form-row:last-child .btn-update-quantity").on('click', function(){
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
                updateRowTotal(formRow);
            });

            $(".form-row .btn-delete").on('click', function(){
                let divDelete = $(this).closest('.form-row');
                divDelete.remove();
                updateTotalPrice();
            })
        } 
    });

</script>
@endpush