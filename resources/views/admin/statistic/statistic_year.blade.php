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
        <h2>Thống kê theo năm</h2>
        <p style="font-size: 20px;">Lưu ý: Các món đã xoá không xuất hiện trong phần thống kê số lượng món bán ra, nhưng phần thống kê doanh thu thì có!</p>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="example-number">Năm</label>
                <select name="" id="year" class="form-control">
                    @for ($i = date('Y'); $i >= 2022; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group col-4">
                <button class="btn btn-submit-form btn-primary" onclick="ev()">Choose</button>
            </div>
        </div>
        <div class="form-group" style="margin-top:20px;">
            <label>Tổng doanh thu của tháng: </label>
            <p class="form-control col-4" id="total-price"></p>
        </div>
    </div>
    <div id="right">
        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>
        <figure class="highcharts-figure">
            <div id="container2"></div>
        </figure>
    </div>
</div>

@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    // document.querySelector("input[type=number]")
    // .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))
</script>
<script>
    function ev()
    {
        let year = $('#year').val();
        $.ajax({
            type: "get",
            url: '{{ route('admin.statistic.year') }}',
            data: {year},
            dataType: "json",
            success: function (response) {
                // console.log(year);
                let data1 = response.data.arr1;
                let arr1 = Object.values(data1);

                let data2 = response.data.arr2;
                let arr2 = Object.values(data2);

                const arrDetail = [];
                arr2.forEach((each)=>{
                    each.data = Object.values(each.data);
                    arrDetail.push(each);
                });
                getChart1(arr1,arrDetail,year);

                let arrX = Object.keys(response.data.arrChart2);
                let arrY = Object.values(response.data.arrChart2);
                getChart2(arrX,arrY,year);

                let total_price = response.data.total_price;
                let p_total_price = document.getElementById('total-price');
                p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VND';
            }
        });
    }
    function getChart1(arr1, arrDetail, year){
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Số lượng sản phẩm đã bán ra trong năm: ' + year,
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
                    text: 'Số lượng'
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
                    data: arr1
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
    function getChart2(arrX, arrY,year)
    {
        Highcharts.chart('container2', {
            title: {
                text: 'Doanh thu của năm: '+year,
                align: 'left'
            },
            yAxis: {
                title: {
                    text: 'Số tiền'
                }
            },

            xAxis: {
                categories: arrX
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            series: [{
                name: 'Doanh thu',
                data: arrY
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    }
    $(document).ready(function () {
        $.ajax({
            type: "get",
            url:  '{{ route('admin.statistic.year') }}',
            // data: "data",
            dataType: "json",
            success: function (response) {
                let year = $('#year').val();
                // console.log(response);
                let data1 = response.data.arr1;
                let arr1 = Object.values(data1);

                let data2 = response.data.arr2;
                let arr2 = Object.values(data2);

                const arrDetail = [];
                arr2.forEach((each)=>{
                    each.data = Object.values(each.data);
                    arrDetail.push(each);
                });

                getChart1(arr1,arrDetail,year);

                let arrX = Object.keys(response.data.arrChart2);
                let arrY = Object.values(response.data.arrChart2);

                getChart2(arrX,arrY,year);

                let total_price = response.data.total_price;
                let p_total_price = document.getElementById('total-price');
                p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VND';
            }
        });
    });
</script>
@endpush
