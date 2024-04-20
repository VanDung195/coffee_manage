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
        <div id="container"></div>
        <p class="highcharts-description">
            A basic column chart comparing estimated corn and wheat production
            in some countries.
    
            The chart is making use of the axis crosshair feature, to highlight
            the hovered country.
        </p>
    </figure>
@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        function ev()
        {
            // let start_date = $('#from').val();
            // let end_date = $('#to').val();
            // $.ajax({
            //     type: "get",
            //     url: 'route('admin.statistic.range')',
            //     data: {start_date,end_date},
            //     dataType: "json",
            //     success: function (response) {
            //         console.log(response);
            //     }
            // });
        }
        function getChart(ArrX){
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Thống kê số lượng sản phẩm bán được theo ngày: ',
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

        $(document).ready(function () {
            $.ajax({
                type: "get",
                url: '{{ route('admin.statistic.range') }}',
                dataType: "json",
                success: function (response) {
                    console.log(response.data.arrX);
                    let data = response.data;
                    let ArrX = data.arrX;
                    getChart(ArrX);
                }
            });
        });
    </script>
@endpush