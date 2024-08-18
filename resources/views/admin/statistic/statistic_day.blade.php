@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
    <style>
        .btn-submit-form{
            height: 37.39px;
            margin-top: 29.2px;
        }
        #left{
            width: 38%;
            float: left;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
            background-color: #37404a;
            background-clip: border-box;
            border: 1px solid #4d5764;
            border-radius: .25rem;
        }
        #right{
            background-color: #37404a;
            background-clip: border-box;
            border: 1px solid #4d5764;
            border-radius: .25rem;
            height: 100%;
            width: 60%;
            float: right;
            box-sizing: border-box;
        }
        .main{
            margin-top: 30px;
            height: 900px;
        }
    </style>
@endpush
@section('content')
<div class="main">
    <div id="left">
        <p style="font-size: 20px;">Lưu ý: Các món đã xoá không xuất hiện trong phần thống kê số lượng món bán ra, nhưng phần thống kê doanh thu thì có!</p>
            <div class="form-row">
                <div class="form-group col-5">
                    <label for="example-date">Date</label>
                    <input class="form-control" id="date" type="date" name="date" value="{{ date('Y-m-d') }}" maxlength="10">
                </div>
                <div class="form-row col-3">
                    <button class="btn btn-submit-form btn-primary" onclick="submitForm(event)">Choose</button>
                </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
                <label>Tổng doanh thu của tháng: </label>
                <p class="form-control col-4" id="total-price"></p>
            </div>
        </div>
        <div id="right">
            <figure class="highcharts-figure">
                <div id="container1"></div>
                <h1>Thống kê thứ 2</h1>
                <div id="container2"></div>
            </figure>
        </div>
</div>

{{-- 
<h1>Thống kê ngày hôm nay hoặc chọn ngày cụ thể để thống kê</h1>
<p style="font-size: 20px;">Lưu ý: Các món đã xoá không xuất hiện trong phần thống kê số lượng món bán ra, nhưng phần thống kê doanh thu thì có!</p>
    <div class="form-row">
        <div class="form-group col-2">
            <label for="example-date">Date</label>
            <input class="form-control" id="date" type="date" name="date" value="{{ date('Y-m-d') }}" maxlength="10">
        </div>
        <div class="form-row col-2">
            <button class="btn btn-submit-form btn-primary" onclick="submitForm(event)">Choose</button>
        </div>
    </div>
    <div class="form-group" style="margin-top:20px;">
        <label>Tổng doanh thu của tháng: </label>
        <p class="form-control col-2" id="total-price"></p>
    </div>
    <figure class="highcharts-figure">
        <div id="container1"></div>
        <h1>Thống kê thứ 2</h1>
        <div id="container2"></div>
    </figure>
 --}}
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
function submitForm(){
    event.preventDefault();
    let date_input = $('#date').val();
    let date = new Date(date_input);
    let formattedDate = date.toLocaleDateString('vi-VN');
    $.ajax({
        type: "get",
        url: '{{ route('admin.statistic.day') }}',
        data: {date_input},
        dataType: "json",
        success: function (response) {
            console.log(response);
            let data = response.data;
            let ArrX = data.arrX;
            let ArrY = data.arrY;
            let today = data.day;
            getChart1(ArrX,response,formattedDate);
            getChart2(ArrY,response,formattedDate);

            let total_price = response.data.total_price;
            let p_total_price = document.getElementById('total-price');
            p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VNĐ';
        }
    });
}
$(document).ready(function () {
    // let date = $('#date').val();
    let date_input = $('#date').val();
    let date = new Date(date_input);
    let formattedDate = date.toLocaleDateString('vi-VN');
    $.ajax({
        url: '{{ route('admin.statistic.day') }}',
        dataType: "json",
        success: function (response) {
            // console.log(response);
            let data = response.data;
            let ArrX = data.arrX;
            let ArrY = data.arrY;
            getChart1(ArrX,response,formattedDate);
            getChart2(ArrY,response,formattedDate);
            
            let total_price = response.data.total_price;
            let p_total_price = document.getElementById('total-price');
            p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VNĐ';
        }
    });
});

function getChart1(ArrX, response,date){
    Highcharts.chart('container1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Thống kê số lượng sản phẩm bán được theo ngày: ' + date,
            align: 'left'
        },
        subtitle: {
            text:
                'hahahahahahaahahahahaah',
            align: 'left'
        },
        xAxis: {
            categories: Object.keys(ArrX),
            crosshair: true,
            accessibility: {
                description: 'Countries'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Số lượng sản phẩm bán ra'
            }
        },
        tooltip: {
            valueSuffix: ' sản phẩm'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Số lượng sản phẩm đã bán',
                data: Object.values(ArrX)
            }
        ]
    });
}
function getChart2(ArrY,response,date){
    Highcharts.chart('container2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Thống kê doanh thu ngày: ' + date,
            align: 'left'
        },
        subtitle: {
            text:
                'HAHAHAHAHAHA',
            align: 'left'
        },
        xAxis: {
            categories: Object.keys(ArrY),
            crosshair: true,
            accessibility: {
                description: 'Countries'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Doanh thu của sản phẩm'
            }
        },
        tooltip: {
            valueSuffix: ' VND'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Doanh thu của sản phẩm đã bán',
                data: Object.values(ArrY)
            }
        ]
    });
}
</script>
@endpush