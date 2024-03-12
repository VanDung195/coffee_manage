@extends('layout.master')

@section('content')
    
<h1>Trang này để test modal</h1>

<button style="width:200px;height:200px;" id="testmodal">test</button>

<div id="modal-invoice-detail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hoá đơn chi tiết</h4>
                <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Thêm div mới ở đây -->
                <h3>Bàn số: </h3>
                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Tên món: </label>
                        <input type="text" class="form-control" value="asdasdasdasd">
                    </div>
                    <div class="form-group col-2">
                        <label>Số lượng: </label>
                        <input type="text" class="form-control" id="" value="12">
                    </div>
                    <div class="form-group col-2">
                        <label>Giá: </label>
                        <input type="text" class="form-control" value="12312" name="" id="">
                    </div>
                    <div class="form-group col-2">
                        <label>Thành tiền: </label>
                        <input type="text" class="form-control" value="123123" name="" id="">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 30px;">
                    <div class="form-group col-5" id="div-paid">
                        <input type="text" class="form-control" value="Đã thanh toán">
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
            </div>
            <div class="modal-footer">
                <button type="button" onclick="deleteModal()" class="btn btn-danger">Xoá hoá đơn</button>
                <button type="button" onclick="exportInvoice()" class="btn btn-success">Xuất hoá đơn</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#testmodal').click(function(){
            $("#modal-invoice-detail").modal("show");
        })
})
</script>
@endpush
