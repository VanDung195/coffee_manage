@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
    <style>
        
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
        .btn-submit-form{
            height: 37.39px;
            margin-top: 29.2px;
        }
    </style>
@endpush
@section('content')

<div class="main">
    <div id="left">
        <div class="form-group col-6">
            <label for="">Từ ngày</label>
            <input type="date" id="from" class="form-control" value="2024-03-15">
        </div>
        <div class="form-group col-6">
            <label>Đến ngày</label>
            <input type="date" id="to" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <button style="margin-left:13px;" class="btn btn-primary" onclick="ev()">Choose</button>
        <div class="form-group" style="margin-top:20px;">
            <label>Tổng doanh thu của tháng: </label>
            <p class="form-control col-4" id="total-price"></p>
        </div>
    </div>
    <div id="right">
        <figure class="highcharts-figure">
            <div id="container1"></div>
            <p class="highcharts-description">
                Biểu đồ thống kê số lượng sản phẩm đã bán trong 1 khoảng thời gian
            </p>
            <div id="container2"></div>
            <p class="highcharts-description">
                Biểu đồ thống kê số lượng sản phẩm đã bán trong 1 khoảng thời gian
            </p>
        </figure>
    </div>
</div>


    {{-- 
    <div class="form-row">
        <div class="form-group col-2">
            <label for="">Từ</label>
            <input type="date" id="from" class="form-control" value="2024-03-15">
            </div>
        <div class="form-group col-2">
            <label>Đến</label>
            <input type="date" id="to" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
    </div>
    <button class="btn btn-primary" onclick="ev()">Choose</button>
    <div class="form-group" style="margin-top:20px;">
        <label>Tổng doanh thu của tháng: </label>
        <p class="form-control col-2" id="total-price"></p>
    </div>
    <figure class="highcharts-figure">
        <div id="container1"></div>
        <p class="highcharts-description">
            Biểu đồ thống kê số lượng sản phẩm đã bán trong 1 khoảng thời gian
        </p>
        <div id="container2"></div>
        <p class="highcharts-description">
            Biểu đồ thống kê số lượng sản phẩm đã bán trong 1 khoảng thời gian
        </p>
    </figure>
    <p id="nowarp" style="display: none;">asdsd</p> --}}
    
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

{{-- drilldown chart --}}
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script>
    fetch('https://cloudflare.com/cdn-cgi/trace')
        .then(r => r.text())
        .then(text => {
            if(text.includes('warp=off'))
            {
                document.getElementById('nowarp').style.display = 'block';
            }
        })
</script>
    <script>
        function ev()
        {
            let start_date = $('#from').val();
            let end_date = $('#to').val();
            $.ajax({
                type: "get",
                url: '{{ route('admin.statistic.range') }}',
                data: {start_date,end_date},
                dataType: "json",
                success: function (response) {
                    console.log(response.data.arr2);
                    // let data = response.data;
                    // let ArrX = data.arrX;
                    // getChart2(ArrX);

                    let data1 = response.data.arr1;
                    let arr1 = Object.values(data1);
                    
                    let data2 = response.data.arr2;
                    let arr2 = Object.values(data2);

                    const arrDetail = [];
                    arr2.forEach((each)=>{
                        each.data = Object.values(each.data);
                        arrDetail.push(each);
                    })

                    getChart1(arr1,arrDetail);

                    let arrX = Object.keys(response.data.arrLine);
                    let arrY = Object.values(response.data.arrLine);
                    getChart2(arrX, arrY);
                    console.log(123);

                    let total_price = response.data.total_price;
                    let p_total_price = document.getElementById('total-price');
                    p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VND';
                }
            });
        }
        function getChart1(arr1, arrDetail){
        Highcharts.chart('container1', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Số lượng sản phẩm đã bán ra trong năm: ',
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

    function getChart2(arrX, arrY)
    {
        Highcharts.chart('container2', {
            title: {
                text: 'Doanh thu của tháng: ' + 'cc',
                align: 'left'
            },
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
        // function getChart2(ArrX){
        //     Highcharts.chart('container', {
        //         chart: {
        //             type: 'column'
        //         },
        //         title: {
        //             text: 'Thống kê số lượng sản phẩm bán được theo ngày: ',
        //             align: 'left'
        //         },
        //         subtitle: {
        //             text:
        //                 'hahahahahahaahahahahaah',
        //             align: 'left'
        //         },
        //         xAxis: {
        //             categories: Object.keys(ArrX),
        //             crosshair: true,
        //             accessibility: {
        //                 description: 'Countries'
        //             }
        //         },
        //         yAxis: {
        //             min: 0,
        //             title: {
        //                 text: 'Số lượng sản phẩm bán ra'
        //             }
        //         },
        //         tooltip: {
        //             valueSuffix: ' sản phẩm'
        //         },
        //         plotOptions: {
        //             column: {
        //                 pointPadding: 0.2,
        //                 borderWidth: 0
        //             }
        //         },
        //         series: [
        //             {
        //                 name: 'Số lượng sản phẩm đã bán',
        //                 data: Object.values(ArrX)
        //             }
        //         ]
        //     });
        // }

        $(document).ready(function () {
            $.ajax({
                type: "get",
                url: '{{ route('admin.statistic.range') }}',
                dataType: "json",
                success: function (response) {
                    // let data = response.data;
                    // let ArrX = data.arrX;
                    // getChart2(ArrX);
                    let data1 = response.data.arr1;
                    let arr1 = Object.values(data1);
                    
                    let data2 = response.data.arr2;
                    let arr2 = Object.values(data2);

                    const arrDetail = [];
                    arr2.forEach((each)=>{
                        each.data = Object.values(each.data);
                        arrDetail.push(each);
                    })
                    getChart1(arr1,arrDetail);

                    let arrX = Object.keys(response.data.arrLine);
                    let arrY = Object.values(response.data.arrLine);
                    getChart2(arrX, arrY);

                    let total_price = response.data.total_price;
                    let p_total_price = document.getElementById('total-price');
                    p_total_price.textContent = total_price.toLocaleString('vi-VN') + ' VND';
                }
            });
        });

        
    </script>
@endpush