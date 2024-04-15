@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
@endpush
@section('content')
<div class="form-group">
    <label for="example-month">Chọn tháng để thống kê</label>
    <input class="form-control col-2" id="date" type="month" name="month" value="{{ date('Y-m') }}">
</div>
<button class="btn btn-primary" onclick="ev()">Choose</button>
<div class="form-group" style="margin-top:20px;">
    <label>Tổng doanh thu của tháng: </label>
    <p class="form-control col-2" id="total-price"></p>
</div>
<figure class="highcharts-figure">
    <div id="container"></div>
</figure>
<figure class="highcharts-figure">
    <div id="container2"></div>
</figure>
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
{{-- script chart2 --}}
<script src="https://code.highcharts.com/modules/series-label.js"></script>

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

                //chart2
                let arrX = Object.keys(response.data.arrChart2);
                let arrY = Object.values(response.data.arrChart2);
                getChart2(arrX,arrY,formattedDate);

                let total_price = response.data.total_price;
                let p_total_price = document.getElementById('total-price');
                p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VNĐ';
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
                let data = response.data.arr1;
                let arr = Object.values(data);
                
                //chart2
                let arrX = Object.keys(response.data.arrChart2);
                let arrY = Object.values(response.data.arrChart2);

                //data arr2 (chart1)
                let data2 = response.data.arr2;
                // let arrDetail = Object.values(data2);
                const arrDetail = [];
                Object.values(data2).forEach((each)=>{
                    // console.log(Object.values(each.data));
                    each.data = Object.values(each.data);
                    arrDetail.push(each);
                    console.log(each);
                })

                getChart_1(arr,arrDetail,formattedDate);
                getChart2(arrX,arrY,formattedDate);

                let total_price = response.data.total_price;
                let p_total_price = document.getElementById('total-price');
                p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VNĐ';
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
    function getChart2(arrX, arrY, formattedDate)
    {
        Highcharts.chart('container2', {
            title: {
                text: 'Doanh thu của tháng: ' + formattedDate,
                align: 'left'
            },

            // subtitle: {
            //     text: 'By Job Category. Source: <a href="https://irecusa.org/programs/solar-jobs-census/" target="_blank">IREC</a>.',
            //     align: 'left'
            // },

            yAxis: {
                title: {
                    text: 'Tổng tiền' 
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
</script>
@endpush