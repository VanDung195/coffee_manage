<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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

    </style>
</head>
<body>
    {{-- <form action="{{ route('invoice.store') }}" method="POST" id="form-create">
        @csrf
        <div class="form-row">
            <label for="">Bàn số</label>
            <input type="text" class="form-control" name="table-id" id="table-id" readonly value="{{ $table_name }}">
        </div>
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
            <input type="text" id="price" class="price form-control" value="0" readonly>
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
<button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
</form>  --}}


<form action="{{ route('invoice.store') }}" method="POST" id="form-create">
    @csrf
    <input type="hidden" name="is_qr_code" value="1">
    <div class="form-row">
        <label for="">Bàn số</label>
        <input type="text" class="form-control" name="table-id" id="table-id" readonly value="{{ $table_name }}">
    </div>
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
        <div class="form-group" style="margin-left: 5px;margin-right:5px;">
            <label for="">Số lượng</label>
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
        <div class="form-group">
            <span class="span-sum">
                <label>Giá</label>
                <input type="text" id="price" class="price form-control" value="0" readonly>
            </span>
        </div>
    </div>
    <div id="append-item"></div>
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
    <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
</form>  
<button type="button" onclick="submitForm()" class="btn btn-success" >Tạo hoá đơn</button>




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
        $(".form-group .select-item").on('change', function(){
            let formRow = $(this).closest('.form-row');
            let quantityInput = formRow.find('.quantity').val('1');
            let btnUpdateQuantity = formRow.find('.btn-update-quantity');
            btnUpdateQuantity.attr('disabled', false);
            updateRowTotal(formRow);
            console.log(123123123);
        });
        
        $(".btn-update-quantity").on('click', function(){
        console.log(1);
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
            updateRowTotal(formRow);
            
        })
        
                //Append item 
        var addBtn = document.getElementById('append');
        addBtn.addEventListener('click', function(){
            console.log(1);

            let div = document.createElement("div");
            div.innerHTML = `
                <div class="form-group col-5 div-select">
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
                    <label for="">Số lượng (Min: 1)</label>
                    <br>
                    <button type="button" class="btn-update-quantity" data-type='0' style="float: left" disabled>-</button>
                    <input type="text" id="quantity" name="quantity[]" class="quantity form-control" value="0" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" readonly>
                    <button type="button" class="btn-update-quantity" data-type='1' style="float: left;" disabled>+</button>
                </div>
                <div class="form-group col-3">
                    <span class="span-sum">
                        <label>Giá</label>
                        <input type="text" id="price" class="price form-control" value=0 readonly>
                    </span>
                </div>
                <div class="form-group col-1">
                    <label>Delete</label>
                    <button type="button" class="btn-delete btn-danger">X</button>
                </div>
            `;

            div.classList.add("form-row");
            div.classList.add("item");
            document.getElementById('append-item').appendChild(div);
            $(".form-row .select-item").select2({ tag: true });
            envent();
        })


        function envent()
        {
            $(".form-row .select-item").on('change', function(){
                let formRow = $(this).closest('.form-row');
                let quantityInput = formRow.find('.quantity');
                let btnUpdateQuantity = formRow.find('.btn-update-quantity');
                btnUpdateQuantity.attr('disabled', false);
                quantityInput.val('1');
                let quantity = parseInt(quantityInput.val());
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