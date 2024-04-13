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
<div class="form-group">
    <label for="example-month">Month</label>
    <input class="form-control col-2" id="date" type="month" name="month" value="{{ date('Y-m') }}">
</div>
<button onclick="ev()">Choose</button>
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Chart showing browser market shares. Clicking on individual columns
        brings up more detailed data. This chart makes use of the drilldown
        feature in Highcharts to easily switch between datasets.
    </p>
</figure>
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
function ev(){
    let date_input = $('#date').val();
    let [year, month] = date_input.split('-');
    let formattedDate = `${month}/${year}`;
    console.log(formattedDate);
    // console.log(date_input);
    $.ajax({
        type: "get",
        url: '{{ route('admin.statistic.month') }}',
        data: {date_input},
        dataType: "json",
        success: function (response) {
            let data = response.data.arr1;
            let arr = Object.values(data);
            let data2 = response.data.arr2;
            const arrDetail = [];
            Object.values(data2).forEach((each)=>{
                each.data = Object.values(each.data);
                arrDetail.push(each);
                console.log(each);
            })
            getChart_1(arr,arrDetail,formattedDate);
            // console.log(response);
        }
    });
}


    $(document).ready(function () {
        $.ajax({
            // type: "method",
            url: '{{ route('admin.statistic.month') }}',
            // data: "data",
            dataType: "json",
            success: function (response) {
                let date_input = $('#date').val();
                let [year, month] = date_input.split('-');
                let formattedDate = `${month}/${year}`;
                console.log(formattedDate);
                // console.log(response.data.arr1);
                let data = response.data.arr1;
                let arr = Object.values(data);
                // console.log(arr);

                let data2 = response.data.arr2;
                // let arrDetail = Object.values(data2);
                const arrDetail = [];
                Object.values(data2).forEach((each)=>{
                    // console.log(Object.values(each.data));
                    each.data = Object.values(each.data);
                    arrDetail.push(each);
                    console.log(each);
                })
                // console.log(arrDetail);

                getChart_1(arr,arrDetail,formattedDate);
            }
        });
    });

    function getChart_1(arr, arrDetail, formattedDate){
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Số lượng sản phẩm bán ra trong tháng: '+ formattedDate,
            },
            subtitle: {
                align: 'left',
                text: 'Click vào cột để hiển thị chi tiết'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Số lượng sản phẩm được bán'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.f}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">Sản phẩm</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.f}</b> <br/>'
            },

            series: [
                {
                    name: 'Products',
                    colorByPoint: true,
                    data: arr
                }
            ],
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                },
                series: arrDetail
            }
        });
    }
</script>
@endpush