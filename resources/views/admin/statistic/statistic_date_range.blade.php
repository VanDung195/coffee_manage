@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
@endpush
@section('content')
    <div class="form-row">
        <div class="form-group col-2">
            <label for="">Từ</label>
            <input type="date" id="from" class="form-control" value="2024-03-01">
        </div>
        <div class="form-group col-2">
            <label>Đến</label>
            <input type="date" id="to" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
    </div>
    <button onclick="ev()">Choose</button>
    <figure class="highcharts-figure">
        {{-- <div id="container"></div>
        <p class="highcharts-description">
            A basic column chart comparing estimated corn and wheat production
            in some countries.
    
            The chart is making use of the axis crosshair feature, to highlight
            the hovered country.
        </p> --}}
        <div id="container2"></div>
        <p class="highcharts-description">
            Biểu đồ thống kê số lượng sản phẩm đã bán trong 1 khoảng thời gian
        </p>
    </figure>
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
                }
            });
        }
        function getChart1(arr1, arrDetail){
        Highcharts.chart('container2', {
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
                }
            });
        });
    </script>
@endpush