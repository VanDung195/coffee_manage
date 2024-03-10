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
<div class="show-table" style="float: left;">
    <button class="btn-table" data-table-id="{{ $table->name }}">
        {{ $table->name }}
    </button>
    <div class="tinh_trang">Trong</div>
</div>
<div class="show-table-detail" style="display: none;float: left;">
    <button class="btn-show-invoice-detail" data-table-id="{{ $table->name }}">
        {{$table->name}}
    </button>
</div>
@endforeach


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
                {{-- <input type="text" name="search" id="search" class="form-control">
                <table>
                    <thead>
                        <tr>
                            <th>Món</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table> --}}

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
                {{-- <div class="form-group col-1">
                    <label>Delete</label>
                    <button
                    style="background-color: red"
                    type="button"
                    class="btn-delete"
                    >X</button>
                </div> --}}
            </div>
            <div id="append-item">

            </div>
            <div class="form-row" style="margin-top: 30px;">
                <div class="form-group col-5" id="div-paid">
                    <select name="is_paid" id="select_paid" class="form-control">
                        {{-- @foreach ($is_paids as $key => $value)
                        <option value="{{$key}}" >
                            {{ $value }}
                        </option>
                        @endforeach --}}
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
{{-- <p>Link 1</p>
<a data-toggle="modal" data-id="ISBN564541" title="Add this item" class="open-AddBookDialog btn btn-primary" href="#addBookDialog">test</a>

<p>&nbsp;</p>


<p>Link 2</p>
<a data-toggle="modal" data-id="ISBN-001122" title="Add this item" class="open-AddBookDialog btn btn-primary" href="#addBookDialog">test</a>

<div class="modal hide" id="addBookDialog">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Modal header</h3>
    </div>
    <div class="modal-body">
        <p>some content</p>
        <input type="text" name="bookId" id="bookId" value=""/>
    </div>
</div> --}}
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
                // console.log(response.data);
                let response_1 = {
                    table_id: response.data,
                }
                // console.log(response_1);


                // console.log(response.data.name.length);
                // for(let i = 0; i < 3; i++)
                // {
                //     console.log(response.data.name[i]);
                // }
                // console.log(response.data.name[0]);
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

        // function updateRowTotal(formRow) {
        //     let quantity = parseInt(formRow.find('.quantity'),val());
        //     let price = formRow.find('.select-item').find(":selected").data("price");

        //     let sum = price * quantity;

        //     formRow.find('#price').val(sum);

        //     updateTotalPrice();
        // }
    function updateRowTotal(formRow) {
        let quantity = parseInt(formRow.find('.quantity').val());
        let price = formRow.find(".select-item").find(":selected").data("price");
        let sum = price * quantity;
        formRow.find(".price").val(sum);
            // console.log(1);
            // Cập nhật tổng tiền của tất cả sản phẩm
        updateTotalPrice();
    }

    function updateTotalPrice() {
        let total = 0;
        $(".item").each(function(){
            let quantity = parseInt($(this).find('.quantity').val());
            let price = $(this).find(".select-item").find(":selected").data("price");
            let totalPrice = quantity*price;
            total += price * quantity;
                // console.log(quantity,price);
        })
        console.log(total);
        $("#total-price").val(total);
    }
    $(document).ready(function () {
        $(".select-item").select2({tag: true});
            // $(document).on("click", ".open-AddBookDialog", function () {
            //     var myBookId = $(this).data('id');
            //     $(".modal-body #bookId").val( myBookId );
            //     // As pointed out in comments, 
            //     // it is unnecessary to have to manually call the modal.
            //     // $('#addBookDialog').modal('show');
            // });
            // $(document).on('click', '.table', function(){
            //     $('#modal-invoice').modal('show')
            // })
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
        $('#modal-invoice').on('hidden.bs.modal', function(){
                // $("#search").val('');
            $(".btn-update-quantity").attr('disabled', false);

            let parentDiv = document.getElementById('append-item');
            let childDiv = parentDiv.getElementsByClassName('form-row');
            while(childDiv.length > 0) {
                parentDiv.removeChild(childDiv[0]);
            }
            $("#price").val('');
            $(".quantity").val('1');
                // $('#select-item option:selected').removeAttr('selected');
            $(".select-item option:selected").each(function(){
                $(this).removeAttr('selected');
            })
            $("#div-select select").val("0").change();
            $("#select-paid option:selected").each(function(){
                $(this).removeAttr('selected');
            })
            $("#div-paid select").val('0').change();
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
            <div class="form-group col-5" class="div-select">
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