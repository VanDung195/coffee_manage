@extends('layout.master')
@push('css')
    <style>
        .btn-table{
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
    @foreach ($table as $item)
    <button class="btn-table" data-table-id="{{ $item->name }}">
        {{ $item->name }}
    </button>
    @endforeach


    <!-- Modal botstrap -->
<div id="modal-company" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create invoice</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            {{-- <input type="text" name="search" id="search" class="form-control">
            <table>
                <thead>
                    <tr>
                        <th>name</th>
                    </tr>
                </thead>
                <tbody>
                    <h1>asdasd</h1>
                </tbody>
            </table> --}}

            <form action="" method="POST" id="form">
                @csrf
                <input type="text" class="form-control" name="table-id" id="table-id" readonly>
                <div class="form-row" id="item">
                    <div class="form-group col-5" id="div-select">
                        <label for="">Món</label>
                        <select name="item" class="select-item">
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
                            <input type="text" name="quantity" id="quantity" value="2" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" class="form-control" readonly>
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
                            <input type="text" name="price" id="price" class="form-control" readonly>
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
                </div>
                
            </form>
            <button type="button" class="btn btn-block btn-lg btn-fill btn-danger" id="append">Thêm món</button>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="submitForm('company')" class="btn btn-success" >Create</button>
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
        function showModal() {
            $("#modal-company").modal("show");
        }
        function updatePrice() {
            
        }
        $(document).ready(function () {
            $(".select-item").select2({tag: true});
            $(document).on("click", ".open-AddBookDialog", function () {
                var myBookId = $(this).data('id');
                $(".modal-body #bookId").val( myBookId );
                // As pointed out in comments, 
                // it is unnecessary to have to manually call the modal.
                // $('#addBookDialog').modal('show');
            });
            // $(document).on('click', '.table', function(){
            //     $('#modal-company').modal('show')
            // })
            $('.btn-table').click(function(){
                var tableId = $(this).data('table-id');
                $("#table-id").val(tableId);
                $("#price").val('0')
                $("#quantity").val('0')
                $("#modal-company").modal("show");

            })
            $('.btn-close').click(function(){
                $("#modal-company").modal('toggle');
                $("#search").val('');
            })
            $('#modal-company').on('hidden.bs.modal', function(){
                // $("#search").val('');
                $("#price").val('');
                $("#quantity").val('1');
                // $('#select-item option:selected').removeAttr('selected');
                $(".select-item option:selected").each(function(){
                    $(this).removeAttr('selected');
                })
                $("#div-select select").val("0").change();
            });
            // $('#modal-cpmpany').on('hidden.bs.modal', function () {
            //     // $(this).find('form').trigger('reset');
            //     $("#select-item option:selected").each(function(){
            //         $(this).removeAttr('selected');
            //     })
            //     $("#div-select select").val("0").change();

            //     $("$modal-company").html("");
                
                
            // })
            $('.select-item').on('change',function(){
                $(".btn-update-quantity").attr('disabled', false);
                $("#quantity").val('1');
                let price = $(this).find(":selected").data("price");
                let quantity = parseInt(document.getElementById('quantity').value);
                let sum = price * quantity;
                $("#price").val(sum);
            });
            $(".btn-update-quantity").on('click', function(){
                let type = parseInt($(this).data('type'));
                let quantity = parseInt(document.getElementById('quantity').value);
                if(type===0)
                {
                    if(quantity>1){
                        quantity = quantity - 1;
                        $("#quantity").val(quantity);
                    }
                }else{
                    quantity += 1;
                    $("#quantity").val(quantity);
                }
                let price = $(".select-item").find(":selected").data("price");
                let sum = price * quantity;
                $("#price").val(sum);
            })

            // $("#append-item").on('click',function(){
            //     let html = `<div class="form-row" id="item">
            //         <div class="form-group col-5" id="div-select">
            //             <label for="">Món</label>
            //             <select name="item" id="select-item">
            //                 <option value="0" data-price="0" selected>Chọn món</option>
            //                 @foreach ($items as $item)
            //                     <option value="{{$item->id}}" data-price="{{ $item->price }}">
            //                         {{ $item->name }}
            //                     </option>
            //                 @endforeach
            //             </select>
            //         </div>
            //         <div class="form-group" style="margin-left: 42px;width:135px;">
            //             <label for="">Quantity (Min: 1)</label>
            //             <br>
            //             <button
            //             type="button"
            //             class="btn-update-quantity"
            //             data-type='0'
            //             style="float: left"
            //             disabled
            //             >
            //             -
            //             </button>
            //                 <input type="text" name="quantity" id="quantity" value="0" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" class="form-control" readonly>
            //             <button
            //             type="button"
            //             class="btn-update-quantity"
            //             data-type='1'
            //             style="float: left;"
            //             disabled
            //             >
            //             +
            //             </button>
            //         </div>
            //         <div class="form-group col-3">
            //             <span class="span-sum">
            //                 <label>Price</label>
            //                 <input type="text" name="price" id="price" class="form-control" readonly>
            //             </span>
            //         </div>
            //         <div class="form-group col-1">
            //             <label>Delete</label>
            //             <button
            //             type="button"
            //             class="btn-delete"
            //             >X</button>
            //         </div>
            //     </div>`;
            //     let newItem = $(html).appendTo("#form");
            //     let parent = $("#div-select").parent();
            //     console.log(parent);
            //     newItem.find('*').trigger('change');
            //     // if(parent.length > 1){
            //     //     parent.remove();
            //     // }
            //     // let itemParent = $("#item").parent();

            //     // console.log(itemParent.childrent());
            // })

                var addBtn = document.getElementById('append');
                addBtn.addEventListener('click', function(){
                    console.log(1);

                    let div = document.createElement("div");
                    div.innerHTML = `
                    <div class="form-group col-5" class="div-select">
                        <label for="">Món</label>
                        <select name="item" class="select-item">
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
                            <input type="text" name="quantity" id="quantity" value="2" style="background-color: #515c69;border:none;height:30px;width:40px;float: left;" class="form-control" readonly>
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
                            <input type="text" name="price" id="price" class="form-control" readonly>
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
                document.getElementById('form').appendChild(div);
                $(".form-row .select-item").select2({tag: true});

                })





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