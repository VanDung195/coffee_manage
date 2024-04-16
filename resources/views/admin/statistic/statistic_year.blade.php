@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/hightcharts.css') }}">
@endpush
@section('content')
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
    function getChart1(arr1, arrDetail){
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Số lượng sản phẩm bán ra trong tháng: ',
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
    $(document).ready(function () {
        $.ajax({
            type: "get",
            url:  '{{ route('admin.statistic.year') }}',
            // data: "data",
            dataType: "json",
            success: function (response) {
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

                getChart1(arr1,arrDetail);
            }
        });
    });
</script>
@endpush