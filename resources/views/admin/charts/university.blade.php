@extends('layouts.admin')
@section('header')
<!-- This is a hot fix when you update your production from non-ssl to ssl, anyway you have to fix all the links one by one to https -->
<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
@endsection
@section('content')
<div class="container border p-3 bg-light">
    @if (Session::has('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    <div class="row d-fex" id="rangerDate">
        <select id="selectFilter" class="text-center" style="min-width: 70px; font-weight: bold">
            <option value="1">Year</option>
            <option value="2">Month</option>
        </select>
        <div class="row d-fex ml-4" id="divSelectDate">
            <label class="pt-1">From</label>
            <input class="ml-1 date-year-month" type="month" name="dateFrom" id="monthFrom" min="{{ $minDate }}" max="{{ $maxDate }}" value="" />
            <label class="ml-3 pt-1">To</label>
            <input class="ml-1 date-year-month" type="month" name="dateTo" id="monthTo" min="{{ $minDate }}" max="{{ $maxDate }}" value="" />
        </div>
        <div class="row d-fex ml-4" id="divSelectYear">
            <label class="pt-1">From</label>
            <select class="ml-1 date-year" name="selectYear" id="selectYearFrom" style="width: 138px">
                <option value="">--------- ----</option>
                @for($i = 0; $i <= (intval($maxDate) - intval($minDate)); $i++) <option value="{{intval($minDate) + $i}}"> {{intval($minDate) + $i}} </option>
                    @endfor
            </select>
            <label class="ml-3 pt-1">To</label>
            <select class="ml-1 date-year" name="selectYear" id="selectYearTo" style="width: 138px">
                <option value="">--------- ----</option>
                @for($i = 0; $i <= (intval($maxDate) - intval($minDate)); $i++) <option value="{{intval($minDate) + $i}}"> {{intval($minDate) + $i}} </option>
                    @endfor
            </select>
        </div>
    </div>
    <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col">
                <!-- BAR CHART -->
                <canvas id="chartTotalCVByUniversity"></canvas>
            </div>
        </div>
        <p></p>
    </form>
</div>
@endsection

@section('footer')
<!-- ChartJS -->
<script src="/template/admin/plugins/chart.js/Chart.js"></script>
<script>
    $(function() {
        
        var areaChartData = { 
        labels  : {!!$arrayUniversity!!},
        datasets: [
            {
            label               : 'CV',
            backgroundColor     : '#45818D',
            borderColor         : '#45818D',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#45818D',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#45818D',
            data                : {!!$arrayTotal!!},
            },
            {
            label               : 'Offer',
            backgroundColor     : '#E06666',
            borderColor         : '#E06666',
            pointRadius         : false,
            pointColor          : '#E06666',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#E06666',
            data                : {!!$arrayOffer!!},
            },
            {
            label               : 'Officical rate',
            backgroundColor     : '#FBBC05',
            borderColor         : '#FBBC05',
            pointRadius         : false,
            pointColor          : '#FBBC05',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#FBBC05',
            data                : {!!$arrayRate!!},
            },
        ]
        }
        var barChartCanvas = $('#chartTotalCVByUniversity').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        var temp2 = areaChartData.datasets[2]
        barChartData.datasets[0] = temp0
        barChartData.datasets[1] = temp1
        barChartData.datasets[2] = temp2

        var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : true,
        datasetFill             : false,
        scales: {
                yAxes: [{
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index){
                            return value + "%";
                        }
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        fontSize: 20,
                        fontColor: '#666',
                        labelString: 'University'
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index){
                            //cut name initials of the university ex Back khoa (DUT) -> DUT
                            return (value.lastIndexOf("(") != -1 ) ? value.substring(value.lastIndexOf("(") + 1, value.lastIndexOf(")")) : value ;
                        }
                    }
                }]
            },
        tooltips: {
            callbacks: {
                label: function(context) {
                    return context.value + "%";
                }
            }
        },
        }

        const myChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
        })

         //disabled input type month
         $('#divSelectDate').hide();
        // $('#divSelectDate').hide();

        $('#selectFilter').on('change', function() {
            var valSelect = $(this).val();
            //Year
            if (valSelect === '1') {
                // set value null
                $('#selectYearFrom').val('');
                $('#selectYearTo').val('');
                // select year hide div date year-month and show div date year
                $('#divSelectDate').hide();
                $('#divSelectYear').show();
            }
            // Year month
            else {
                //set value null
                $('#monthFrom').val('');
                $('#monthTo').val('');
                // select year hide div date year and show div date year-month
                $('#divSelectDate').show();
                $('#divSelectYear').hide();
            }
        });

        //Select month year from to and filter month year
        $('.date-year-month').on('change', function() {
            var monthFrom = $('#monthFrom').val();
            var monthTo = $('#monthTo').val();
            //dateFrom & dateTo not null
            if (monthFrom != "" && monthTo != "") {
                $.ajax({
                    method: 'GET',
                    url: '/charts/university',
                    data: {
                        monthFrom: monthFrom,
                        monthTo: monthTo
                    },
                    success: (data) => {
                        myChart.config.data.labels = data.arrayUniversity;
                        myChart.config.data.datasets[0].data = data.arrayTotal;
                        myChart.config.data.datasets[1].data = data.arrayOffer;
                        myChart.config.data.datasets[2].data = data.arrayRate;
                        myChart.options.scales.xAxes[0].scaleLabel.labelString = "Year month";
                        myChart.update();
                    },
                    error: (err) => {
                        console.log({
                            err
                        });
                    }
                });
            }
        });

        //Select year from to and filter year
        $('.date-year').on('change', function() {
            var dateFrom = $('#selectYearFrom').val();
            var dateTo = $('#selectYearTo').val();

            console.log(dateFrom);
            console.log(dateTo);
            //dateFrom & dateTo not null
            if (dateFrom != "" && dateTo != "") {
                // Only search ajax
                // Return call route /charts/university
                $.ajax({
                    method: 'GET',
                    url: '/charts/university',
                    data: {
                        dateFrom: dateFrom,
                        dateTo: dateTo,
                    },
                    success: (data) => {
                        myChart.config.data.labels = data.arrayUniversity;
                        myChart.config.data.datasets[0].data = data.arrayTotal;
                        myChart.config.data.datasets[1].data = data.arrayOffer;
                        myChart.config.data.datasets[2].data = data.arrayRate;
                        myChart.options.scales.xAxes[0].scaleLabel.labelString = "Year";
                        myChart.update();
                    },
                    error: (err) => {
                        console.log({
                            err
                        });
                    }
                });
            }
        });
    })
</script>
@endsection