@extends('layout.master')
@push('css')
<style>
    .btn-table{
        width: 50px;
        height: 50px;
        background-color: black;
        border: 1px solid black;
        margin: 50px;
        color: rgb(14, 225, 52);
    }
    .btn-show-invoice-detail {
        width: 50px;
        height: 50px;
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
</style>

@endpush
@section('content')
@foreach ($tables as $table)
{{-- @if ($table->status == 'Trong')
    
@else
    
@endif --}}
<div class="show-table" id="show_table_{{ $table->name }}" style="display: block;float: left;">
    <button class="btn-table" data-table-id="{{ $table->name }}">
        {{ $table->name }}
    </button>
</div>
<div class="show-table-detail" id="show_detail_{{ $table->name }}" style="display: none;float: left;">
    <button class="btn-show-invoice-detail" data-table-id="{{ $table->name }}" onclick="showInvoiceDetail('#invoice_detail_{{ $table->stt }}')">
        {{$table->name}}
    </button>
</div>
@endforeach

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
                
                let modal_invoice = `
                    <div id="invoice_detail_${index}" class="modal fade" role="dialog">
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
                    <button type="button" onclick="deleteModal()" class="btn btn-danger">Xoá hoá đơn</button>
                    <button type="button" onclick="exportInvoice()" class="btn btn-success">Xuất hoá đơn</button>
                </div>
                </div>
                </div>
                </div>
                `;

                let div2 = document.createElement("div");
                div2.innerHTML = modal_invoice;

                div2.classList.add("form-group");
                // divabc.innerHTML = modal_invoice;
                // let divabc = document.getElementById("modal-invoice-detail");
                // divabc.appendChild(modal_invoice);
                document.getElementById("append_modal_invoice_detail").appendChild(div2);
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
        // console.log(modal_name);
    }
    function closeModal() {
        
    }
    function showModalIncvoiceDetail(modal_id) {

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