<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css">
    <title>Document</title>
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

        
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 10px;
    }

    .form-row > div {
        margin: 5px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        box-sizing: border-box;
    }

    .btn-update-quantity {
        padding: 5px 10px;
        margin: 0 5px;
    }

    .div-select {
        flex: 1;
        min-width: 200px;
    }

    .form-group.quantity-group {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 150px;
        margin-left: 5px;
        margin-right: 5px;
    }

    .form-group.quantity-group input.quantity {
        width: 50px;
        text-align: center;
    }

    .form-group.quantity-group button {
        margin: 0 5px;
    }

    .form-group .price {
        flex: 1;
        min-width: 100px;
    }

    @media (max-width: 768px) {
        .form-row > div {
            flex: 1 1 100%;
            margin: 5px 0;
        }

        .form-group.quantity-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group.quantity-group button,
        .form-group.quantity-group input.quantity {
            margin: 5px 0;
        }
    }

    .btn-update-quantity{
        height: 40px;
    }
    .form-control{
        margin-top: 0px;
    }
    
    .form-invoice{
        /* border: 1px solid black; */
        margin-top: 20px;
        max-width: fit-content;
        margin-left: auto;
        margin-right: auto;
        width: 430px;
        padding: 5px;
    }
    .table-name{
        margin-left: 5px;
        
    }
    .icon-center{
        max-width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }







    .form-group label {
        font-size: 16px;
    }

    .quantity-container {
        display: flex;
        align-items: center;
        /* border-radius: 5px;  */
        overflow: hidden; /* Đảm bảo nội dung không vượt ra ngoài góc bo */
    }

    .btn-update-quantity {
        border-radius: 5px;
        background-color: #525d68; /* Màu nền xám tối */
        border: none; /* Loại bỏ viền */
        color: #ecf0f1; /* Màu chữ sáng */
        padding: 0 15px; /* Đệm nút */
        cursor: pointer; /* Con trỏ tay */
        font-size: 20px; /* Kích thước chữ lớn */
        /* height: 40px;  */
        height: 38.4px; 
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s; /* Hiệu ứng chuyển màu mượt mà */
    }

    .btn-update-quantity:hover {
        background-color: #3c566c; /* Màu đậm hơn khi hover */
    }

    .btn-update-quantity:disabled {
        background-color: #34495e; /* Màu nền cho trạng thái disabled */
        color: #7f8c8d; /* Màu chữ nhạt hơn cho trạng thái disabled */
        cursor: not-allowed; /* Con trỏ không cho phép */
    }

    .quantity {
        border-radius: 5px;
        background-color: #464f5b; /* Nền input màu tối */
        border: none; /* Loại bỏ viền */
        height: 40px; /* Chiều cao cố định */
        width: 50px; /* Chiều rộng cố định */
        text-align: center; /* Căn giữa chữ */
        font-size: 16px; /* Kích thước chữ */
        color: #ecf0f1; /* Màu chữ sáng */
    }

    .btn-delete{
        height: 38.4px;
        width: 100px;
    }
    .btn-delete-disabled{
        height: 38.4px;
        width: 60px;
    }
    .btn-delete-disabled:disabled{
        height: 38.4px;
        width: 80px;
        cursor: not-allowed;
        background-color: #34495e; /* Màu nền cho trạng thái disabled */
        border: none; 
    }
    .btn-submit-invoice{
        width: 100%;
        margin-top: 20px;
        height: 50px;
        font-size: 20px;
    }
    .btn-submit-invoice:disabled{
        background-color: rgb(178, 240, 178);
    }
    </style>
</head>
<body>
{{-- <div class="form-invoice">
    <form action="{{ route('invoice.store_qr') }}" method="POST" id="form-create">
        @csrf
        <div class="table-name form-row">
            <input type="hidden" name="table-id" value="{{ $table_name }}">
            <h3>Bàn số: {{ $table_name }}</h3>
        </div>
        <div class="item" id="item">
            <div class="form-row">
                <div class="form-group col-7 div-select" id="div-select">
                    <label for="id[]">Món: </label>
                    <select name="id[]" class="form-control select-item">
                        <option selected>Chọn món</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-4" style="margin-left: 5px; margin-right: 5px;">
                    <label for="quantity">Số lượng: </label>
                    <div style="display: flex;">
                        <button type="button" class="btn-update-quantity" data-type='0' disabled>-</button>
                        <input type="text" id="quantity" name="quantity[]" class="quantity form-control" value="0" style="background-color: #515c69; border: none; height: 40px; width: 40px; text-align: center;" readonly>
                        <button type="button" class="btn-update-quantity" data-type='1' disabled>+</button>
                    </div>
                </div>
            </div>
            <div class="form-group col-4" style="padding:0px;">
                <label for="price">Giá: </label>
                <input type="text" id="price" class="price form-control" value="0" readonly>
            </div>
        </div>
        
        <div id="append-item"></div>
        <div class="form-row" style="margin-top: 10px;">
            <div class="form-group col-5" id="div-paid">
                <select name="is_paid" id="select_paid" class="form-control">
                    <option value="0">Thanh toán sau</option>
                    <option value="1" selected>Thanh toán luôn</option>
                </select>
            </div> 
            <div class="form-group col-2" style="margin-left: 15px;float: left;">
                <h4>Tổng tiền:</h4>
            </div>
            <div class="form-group col-4" style="margin-top: 5px; margin-left: 0;">
                <input type="text" id="total-price" value="0" class="form-control" readonly>
            </div>
        </div>
        <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
    </form>
    <button type="button" onclick="submitForm()" class="btn btn-success">Tạo hoá đơn</button>
</div> --}}

<div class="form-invoice">
    <form action="{{ route('invoice.store_qr') }}" method="POST" id="form-create">
        @csrf
        <div class="table-name form-row">
            <input type="hidden" name="table_id" value="{{ $table_name }}">
            <h3>Bàn số: {{ $table_name }}</h3>
        </div>
        <div class="item" id="item">
            <div class="form-row">
                <div class="form-group col-12 div-select" id="div-select">
                    <label for="id[]">Món: </label>
                    <select name="id[]" class="form-control select-item">
                        <option data-price="0" selected>Chọn món</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-5">
                    <label for="quantity">Số lượng: </label>
                    <div class="quantity-container" style="width:160px;">
                        <button type="button" class="btn-update-quantity" data-type='0' disabled style="width:41.68px;margin-left:0px;">-</button>
                        <input type="text" name="quantity[]" class="quantity" value="0" readonly>
                        <button type="button" class="btn-update-quantity" data-type='1' disabled>+</button>
                    </div>
                </div>
                <div class="form-group col-4" style="padding:0px;">
                    <label for="price">Giá: </label>
                    <input type="text" id="price" class="price form-control" value="0" readonly>
                </div>
                <div class="form-group col-2">
                    <label>Xoá: </label>
                    <button type="button" class="btn-delete-disabled btn-danger btn-sm" disabled><i class="dripicons-cross"></i></button>
                </div>
            </div>
        </div>
        
        <div id="append-item"></div>
        <div class="form-row" style="margin-top: 10px;">
            <div class="form-group col-5" id="div-paid">
                <select name="is_paid" id="select_paid" class="form-control">
                    <option value="0">Thanh toán sau</option>
                    <option value="1" selected>Thanh toán luôn</option>
                </select>
            </div> 
            <div class="form-group col-2" style="margin-left: 15px;float: left;">
                <h4>Tổng tiền:</h4>
            </div>
            <div class="form-group col-4" style="margin-top: 5px; margin-left: 0;">
                {{-- <input type="text" name="total_price" id="total-price" value="0" class="form-control" readonly> --}}
                <p style="margin:0px;display: flex; align-items: center;" id="total-price" class="form-control">0</p>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="">Số tiền trả (1 = 10.000):</label>
                <input class="customer-payment form-control" type="text" name="customer_payment" id="">
            </div>
            <div class="form-group col-5"  style="margin-left: 20px;">
                <label for="">Số tiền còn lại:</label>
                {{-- <input class="form-control" type="text" name="" id=""> --}}
                <p style="margin: 0px;display: flex; align-items: center;" class="remaining-money form-control">0</p>
            </div>
        </div>
        <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
    </form>
    <button type="button" onclick="submitForm()" class="btn btn-submit-invoice btn-success">Tạo hoá đơn</button>
</div>
</body>
</html>
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>    
<script src="{{ asset('js/helper.js') }}"></script> 
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
                console.log('thanh cong roi nhe');
            },
            error: function(response) {
            }
        });
    }

    function update_row_total(item_class) {
        let quantity_input = parseInt(item_class.find('.quantity').val());
        let price = item_class.find(".select-item").find(":selected").data('price');
        let sum = price * quantity_input;
        item_class.find('.price').val(sum.toLocaleString('vi-VN'));
        update_total_price();
    }

    function update_total_price() {
        let total = 0;
        $(".item").each(function(){
            let quantity = parseInt($(this).find('.quantity').val());
            let price = parseInt($(this).find(".select-item").find(":selected").data("price"));
            let totalPrice = quantity*price;
            total += price * quantity;
        })
        // $("#total-price").val(total.toLocaleString('vi-VN'));
        $("#total-price").html(total.toLocaleString('vi-VN'));
    }
    

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.customer-payment').on('keyup', function() {
            // let modal_content = $(this).closest('.modal-content');
            // let object = modal_content.find('.form-create');
            // let form_data = new FormData(object[0]);
            let object = $('#form-create');
            let form_data = new FormData(object[0]);
            console.log(123);
            $.ajax({
                type: "post",
                url: '{{ route('invoice.update') }}',
                data: form_data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    $('.remaining-money').html(response.data.toLocaleString('vi-VN'));
                    $('.btn-submit-invoice').prop( "disabled", false);
                },
                error: function(error) {
                    $('.remaining-money').html('NULL');
                    $('.btn-submit-invoice').prop( "disabled", true);
                }
            });
        });


        $(".select-item").select2({tag: true});
        $('.btn-table').click(function(){
            var tableId = $(this).data('table-id');
            $("#table-id").val(tableId);
            $("#price").val('0')
            $(".quantity").val('0')
            $("#modal-invoice").modal("show");

        })
        $(".form-group .select-item").on('change', function(){
            let item_class = $(this).closest('.item');
            let quantity_input = item_class.find('.quantity').val('1');
            let btn_update_quantity = item_class.find('.btn-update-quantity');
            btn_update_quantity.attr('disabled', false);

            update_row_total(item_class);
        });
        
        $(".btn-update-quantity").on('click', function(){
            let item_class = $(this).closest('.item');
            let type = parseInt($(this).data('type'));
            let quantity_input = $(this).closest('.item').find('.quantity');
            let quantity = parseInt(quantity_input.val());
            if(type === 0)
            {
                if(quantity > 1)
                {
                    quantity = quantity - 1;
                    quantity_input.val(quantity);
                }
            }else{
                quantity += 1;
                quantity_input.val(quantity);
            }
            update_row_total(item_class);
            
        })

        var addBtn = document.getElementById('append');
        addBtn.addEventListener('click', function() {
            let div = document.createElement("div");
            div.innerHTML = `
                <div class="item" id="item">
                    <div class="icon-center">
                        <i class="mdi mdi-minus"></i>
                        <i class="dripicons-minus"></i>
                        <i class="dripicons-minus"></i>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 div-select" id="div-select">
                            <label for="id[]">Món: </label>
                            <select name="id[]" class="form-control select-item">
                                <option data-price="0" selected>Chọn món</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-5">
                            <label for="quantity">Số lượng: </label>
                            <div class="quantity-container">
                                <button type="button" class="btn-update-quantity" data-type='0' disabled style="width:41.68px;margin-left:0px;">-</button>
                                <input type="text" id="quantity" name="quantity[]" class="quantity" value="0" readonly>
                                <button type="button" class="btn-update-quantity" data-type='1' disabled>+</button>
                            </div>
                        </div>
                        <div class="form-group col-4" style="padding:0px;">
                            <label for="price">Giá: </label>
                            <input type="text" id="price" class="price form-control" value="0" readonly>
                        </div>
                        <div class="form-group col-1">
                            <label>Xoá:</label>
                            <button type="button" class="btn-delete btn-danger btn-sm"><i class="dripicons-cross"></i></button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('append-item').appendChild(div);
            $(".form-row .select-item").select2({ tag: true });
            envent();
        });

        
                //Append item 
        // var addBtn = document.getElementById('append');
        // addBtn.addEventListener('click', function(){
        //     let div = document.createElement("div");
        //     div.innerHTML = `
        //         <div class="form-group col-5 div-select">
        //             <label for="">Món</label>
        //             <select name="id[]" class="select-item">
        //                 <option value="0" data-price="0" selected>Chọn món</option>
        //                 @foreach ($items as $item)
        //                     <option value="{{$item->id}}" data-price="{{ $item->price }}">
        //                         {{ $item->name }}
        //                     </option>
        //                 @endforeach
        //             </select>
        //         </div>
        //         <div class="form-group" style="margin-left: 42px;width:135px;">
        //             <label for="">Số lượng (Min: 1)</label>
        //             <br>
        //             <button type="button" class="btn-update-quantity" data-type='0' style="float: left" disabled>-</button>
        //             <input type="text" id="quantity" name="quantity[]" class="quantity form-control" value="0" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" readonly>
        //             <button type="button" class="btn-update-quantity" data-type='1' style="float: left;" disabled>+</button>
        //         </div>
        //         <div class="form-group col-3">
        //             <span class="span-sum">
        //                 <label>Giá</label>
        //                 <input type="text" id="price" class="price form-control" value=0 readonly>
        //             </span>
        //         </div>
        //         <div class="form-group col-1">
        //             <label>Delete</label>
        //             <button type="button" class="btn-delete btn-danger">X</button>
        //         </div>
        //     `;

        //     div.classList.add("form-row");
        //     div.classList.add("item");
        //     document.getElementById('append-item').appendChild(div);
        //     $(".form-row .select-item").select2({ tag: true });
        //     envent();
        // })


        function envent()
        {
            $(".form-group .select-item").on('change', function(){
                let item_class = $(this).closest('.item');
                let quantity_input = item_class.find('.quantity').val('1');
                let btn_update_quantity = item_class.find('.btn-update-quantity');
                btn_update_quantity.attr('disabled', false);
                update_row_total(item_class);
            });
                // Sự kiện cho nút tăng giảm trong form-row mới
            // $(".form-row:last-child .btn-update-quantity").on('click', function(){
            //     let formRow = $(this).closest('.form-row');
            //     let type = parseInt($(this).data('type'));
            //     let quantityInput = $(this).closest('.form-row').find('.quantity');
            //     let quantity = parseInt(quantityInput.val());
            //     if (type === 0 && quantity > 1) {
            //         quantity = quantity - 1;
            //         quantityInput.val(quantity);
            //     } else if (type === 1) {
            //         quantity += 1;
            //         quantityInput.val(quantity);
            //     }
            //     updateRowTotal(formRow);
            // });

            $(".btn-update-quantity").on('click', function(){
                let item_class = $(this).closest('.item');
                let type = parseInt($(this).data('type'));
                let quantity_input = $(this).closest('.item').find('.quantity');
                let quantity = parseInt(quantity_input.val());
                if(type === 0)
                {
                    if(quantity > 1)
                    {
                        quantity = quantity - 1;
                        quantity_input.val(quantity);
                    }
                }else{
                    quantity += 1;
                    quantity_input.val(quantity);
                }
                update_row_total(item_class);
                
            });

            $(".item .btn-delete").on('click', function(){
                let divDelete = $(this).closest('.item');
                divDelete.remove();
                update_total_price();
            })
        } 
    });
</script>