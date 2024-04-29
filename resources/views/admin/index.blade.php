@extends('layout.master')
@push('css')
<style>
    .btn-table{
        width: 70px;
        height: 70px;
        background-color: black;
        border: 1px solid black;
        margin: 50px;
        color: rgb(14, 225, 52);
    }
    .btn-show-invoice-detail {
        width: 70px;
        height: 70px;
        background-color: black;
        border: 1px solid black;
        margin: 50px;
        color: red;
    }
    .btn-update-quantity {
        padding: 5px 10px;
        background-color: #f8f9fa;
        border: none;
        color: #333;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-update-quantity:hover {
        background-color: #e2e6ea;
    }

    .btn-update-quantity:active {
        background-color: #dae0e5;
    }
    .form-test{
        color: red;
    }

    #left{
        width: 50%;
        float: left;
    }
    #right{
        width: 50%;
        float: right;
        background-color: rgb(75, 32, 134);
    }
</style>

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
        <div class="show-table" id="show_table_unknow" style="display: block;float: left;">
            <button class="btn-table" data-table-id="unknow">
                unknow
            </button>
        </div>
        <div class="show-table-detail" id="show_detail_unknow" style="display: none;float: left;">
            <button class="btn-show-invoice-detail" data-table-id="unknow" onclick="showInvoiceDetail('#invoice_detail_unknow')">
                unknow
            </button>
        </div>
</div>
<div id="right">
    <h1>asdasdssasdasd</h1>
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
<script>
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
                    <button type="button" onclick="deleteInvoice('${table_id_api}')" class="btn btn-danger">Xoá hoá đơn</button>
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
        },
        error: function (error) {
            console.log(error);
            console.log('Sai mia no roi may');
        }
    });
});
    function submitForm() {
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
                let invoice_item = response.data.invoice_details;
                
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
                });
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
                    <button type="button" onclick="deleteInvoice('${table_id}')" class="btn btn-danger">Xoá hoá đơn</button>
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
                // divabc.innerHTML = modal_invoice;
                // let divabc = document.getElementById("modal-invoice-detail");
                // divabc.appendChild(modal_invoice);
                document.getElementById("append_modal_invoice_detail").appendChild(div2);
                //show_table la cai nut de mo invoice and invoice detail
                document.getElementById(show_table).style.display = 'none';
                document.getElementById(show_detail).style.display = 'block';

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
        console.log(table_name);
        // let modal_name = "#invoice_detail_" + table_name;
        $(table_name).modal("show");
    }
    function closeModal() {
        $('#modal-invoice').modal('toggle');
    }
    function deleteInvoice(table_id) {
        //table_name (table_id)
        let table_name = table_id;
        $.ajax({
            type: 'get',
            url: '{{ route('table.update') }}',
            data: {table_name},
            success: function (response) {
                let modal_invoice = "#invoice_detail_"+table_name;
                $(modal_invoice).modal('toggle');

                let div_invoice = "div_invoice_detail_"+table_id;
                let divR = document.getElementById(div_invoice);
                divR.remove();

                let btn_show_table = "show_table_"+table_id;
                let btn_show_invoice_detail = "show_detail_"+table_id;
                // let test = document.getElementById(btn_show_table);
                // let test2 = document.getElementById(btn_show_invoice_detail);
                // console.log(test2);
                document.getElementById(btn_show_table).style.display = 'block';
                document.getElementById(btn_show_invoice_detail).style.display = 'none';

                console.log('thanh cmn cong roi');
            }
        });





        // console.log(table_id);
        // let modal_invoice = "#invoice_detail_"+table_id;
        // // console.log(table_id);
        // $(modal_invoice).modal('toggle');
        // console.log(1231232131231231231736173127361863187);
        // let div_invoice = "div_invoice_detail_"+table_id;
        // let test = document.getElementById(div_invoice);
        // test.remove();
        // console.log(test);


        // $(div_invoice).remove();
        // let btn_show_table = "show_table_"+table_id;
        // let btn_show_invoice_detail = "show_detail_"+table_id;
        // let element = document.getElementById(div_invoice);
        // element.remove();
        // document.getElementById(btn_show_table).style.display = 'block';
        // document.getElementById(btn_show_invoice_detail).style.display = 'none';

        // // let modal_invoice = '#invoice_detail_'+table_id;
        // // console.log(modal_invoice);
        // let modal_invoice = document.getElementById("invoice_detail_"+table_id);
        // console.log(modal_invoice);
    }
    function updateRowTotal(formRow) {
        let quantity = parseInt(formRow.find('.quantity').val());
        let price = formRow.find(".select-item").find(":selected").data("price");
        let sum = price * quantity;
        formRow.find(".price").val(sum.toLocaleString('vi-VN'));
            // console.log(1);
            // Cập nhật tổng tiền của tất cả sản phẩm
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
                // $("#search").val('');

            $(this).find('form').trigger('reset');
            $(".select-item").select2({tag: true});
            ///Cũ méo ổn
            // $(".btn-update-quantity").attr('disabled', false);

            let parentDiv = document.getElementById('append-item');
            let childDiv = parentDiv.getElementsByClassName('form-row');
            while(childDiv.length > 0) {
                parentDiv.removeChild(childDiv[0]);
            }
            // $("#price").val('');
            // $(".quantity").val('1');
            //     // $('#select-item option:selected').removeAttr('selected');
            // $(".select-item option:selected").each(function(){
            //     $(this).removeAttr('selected');
            // })
            // $("#div-select select").val("0").change();
            // $("#select-paid option:selected").each(function(){
            //     $(this).removeAttr('selected');
            // })
            // $("#div-paid select").val('0').change();
        });
            // $('.delete-test').on('click', function(){
            //     let parentDiv = document.getElementById('append-item');
            //     let childDiv = parentDiv.getElementsByClassName('form-row');
            //     while(childDiv.length > 0) {
            //         parentDiv.removeChild(childDiv[0]);
            //     }
            // })
            // $('#modal-cpmpany').on('hidden.bs.modal', function () {
            //     // $(this).find('form').trigger('reset');
            //     $("#select-item option:selected").each(function(){
            //         $(this).removeAttr('selected');
            //     })
            //     $("#div-select select").val("0").change();

            //     $("$modal-invoice").html("");
        
        
            // })
            // $('.select-item').on('change',function(){
            //     $(".btn-update-quantity").attr('disabled', false);
            //     $(".quantity").val('1');
            //     let price = $(this).find(":selected").data("price");
            //     let quantity = parseInt(document.getElementById('quantity').value);
            //     let sum = price * quantity;
            //     $("#price").val(sum);
            // });

        $(".form-row .select-item").on('change', function(){
                    // $(".btn-update-quantity").attr('disabled', false);
            let formRow = $(this).closest('.form-row');
            let quantityInput = formRow.find('.quantity').val('1');
            let btnUpdateQuantity = formRow.find('.btn-update-quantity');
            btnUpdateQuantity.attr('disabled', false);

                    // // quantityInput.val('1');  OLD
                    // let quantity = parseInt(quantityInput.val());
                    // // let price = $(this).find(":selected").data("price");
                    // // let sum = price * quantity;
                    // // $("#price").val(sum);
                    // let price = $(this).closest('.form-row').find(".select-item").find(":selected").data("price");
                    // let sum = price * quantity;
                    // // Sử dụng closest để tìm đến các phần tử trong cùng một form-row
                    // $(this).closest('.form-row').find("#price").val(sum);
            updateRowTotal(formRow);
            
                    // let totalPrice = formRow.find('#price').val();
                    // console.log(totalPrice);
                    // getTotal($(this).closest('form-row'));
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
                // console.log(quantity);
                // let price = $(this).closest('.form-row').find(".select-item").find(":selected").data("price");
                // let sum = price * quantity;
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
            >X</button>
            </div>
            `;
            div.classList.add("form-row")
            div.classList.add("item")
                // document.getElementById('form').appendChild(div);
            document.getElementById('append-item').appendChild(div);
            $(".form-row .select-item").select2({tag: true});
            envent();
        })


        function envent()
        {
                // $('.form-row:last-child .select-item').on('change',function(){
                //     // let parent = $("#div-select").parent();
                //     // let child = parent.find($('#div-select'));
                //     // console.log(child);
                //     let quantityInput = $(".quantity").closest('.form-row').find('.quantity');
                //     let priceInput = $(".price").closest('.form-row').find('.price');
                //     let btnUpdate = $(".btn-update-quantity").closest('.form-row').find('.quantity');
                //     // $(".btn-update-quantity").attr('disabled', false);
                //     // $(".quantity").val('1');
                //     // $(".price").val('0')
                //     quantityInput.val('1');
                //     priceInput.val('0');
                //     btnUpdate.attr('disabled', false);

                //     let price = $(this).find(":selected").data("price");
                //     let quantity = parseInt(document.getElementById('quantity').value);
                //     let sum = price * quantity;
                //     $("#price").val(sum);
                // });
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
                    // let price = $(this).closest('.form-row').find(".select-item").find(":selected").data("price");
                    // let sum = price * quantity;
                    // Sử dụng closest để tìm đến các phần tử trong cùng một form-row
                        // $(this).closest('.form-row').find("#price").val(sum);
                updateRowTotal(formRow);
            });

            $(".form-row .btn-delete").on('click', function(){
                let divDelete = $(this).closest('.form-row');
                divDelete.remove();
                updateTotalPrice();
            })
        } 


                    //livesearch
                    // $('#search').on('keyup', function(){
                    //     $value = $(this).val();
                    //     console.log(1);
                    //     $.ajax({
                    //         type: 'get',
                    //         url: '{{ route('item.search') }}',
                    //         data: {'search': $value},
                    //         success: function (response) {
                    //             console.log(response);
                    //             $('tbody').html(response.data);
                    //         },
                    //         error: function (response) {
                    //             console.log(12312);
                    //         }
                    //     });
                    // })
    });

</script>
@endpush