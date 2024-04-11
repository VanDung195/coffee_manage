@extends('layout.master')
@push('css')
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
@endpush
@section('content')
<h1>Thống kê ngày hôm nay hoặc chọn ngày cụ thể để thống kê</h1>
    <div class="form-group col-2">
        <label for="example-date">Date</label>
        <input class="form-control" id="date" type="date" name="date" value="{{ date('Y-m-d') }}">
    </div>
    <button onclick="submitForm(event)">Choose</button>
    <figure class="highcharts-figure">
        <div id="container1"></div>
        <h1>Thống kê thứ 2</h1>
        <div id="container2"></div>
    </figure>
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
            let data = response.data;
            let ArrX = data.arrX;
            let ArrY = data.arrY;
            getChart1(ArrX,response,formattedDate);
            getChart2(ArrY,response,formattedDate);
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