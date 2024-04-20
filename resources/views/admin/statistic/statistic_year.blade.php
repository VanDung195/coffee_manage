@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
@endpush
@section('content')
<div class="form-group">
    {{-- <input class="form-control col-2" type="number" min="1900" max="2099" step="1" value="{{ date('Y') }}" /> --}}
    {{-- <input class="form-control col-2" id="year" type="year" name="year" value="{{ date('Y') }}"> --}}
    <div class="form-group">
        <label for="example-number">Năm</label>
        {{-- <input class="form-control col-1" id="year" type="number" placeholder="YYYY" min="2023" max="{{ date('Y') }}" value="{{ date('Y') }}">  --}}
        <select name="" id="year" class="form-control col-1">
            @for ($i = date('Y'); $i >= 2022; $i--)
                <option value="{{ $i }}">{{ $i }}</option>         
            @endfor
        </select>
    </div>
</div>
<button class="btn btn-primary" onclick="ev()">Choose</button>
<figure class="highcharts-figure">
    <div id="container"></div>
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

            // subtitle: {
            //     text: 'By Job Category. Source: <a href="https://irecusa.org/programs/solar-jobs-census/" target="_blank">IREC</a>.',
            //     align: 'left'
            // },

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
            }
        });
    });
</script>
@endpush