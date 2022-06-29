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
    <div>
        <div class="row d-fex">
            <select id="selectFilter" class="text-center" style="min-width: 70px; font-weight: bold">
                <option value="1">Year</option>
                <option value="2">Month</option>
            </select>
            <div class="row d-fex ml-4" id="divSelectDate">
                <label class="pt-1">From</label>
                <input class="ml-1 dateyearmonth" type="month" name="datefrom" id="textdatefrom" min="{{ $minDate }}" max="{{ $maxDate }}" value=""/>
                <label class="ml-3 pt-1">To</label>
                <input class="ml-1 dateyearmonth" type="month" name="dateto" id="textdateto" min="{{ $minDate }}" max="{{ $maxDate }}" value=""/>
            </div>
            <div class="row d-fex ml-4" id="divSelectYear">
                <label class="pt-1">From</label>
                <select class="ml-1 dateyear" name="select-year" id="select-year-from" style="width: 138px">
                    <option value="">--------- ----</option>
                    @for($i = 0; $i <= (intval($maxDate) - intval($minDate)); $i++)
                        <option value="{{intval($minDate) + $i}}"> {{intval($minDate) + $i}} </option>
                    @endfor
                </select>
                <label class="ml-3 pt-1">To</label>
                <select class="ml-1 dateyear" name="select-year" id="select-year-to" style="width: 138px">
                    <option value="">--------- ----</option>
                    @for($i = 0; $i <= (intval($maxDate) - intval($minDate)); $i++)
                        <option value="{{intval($minDate) + $i}}"> {{intval($minDate) + $i}} </option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <!-- BAR CHART -->
                <canvas id="chartTotalJobs"></canvas>
            </div>
        </div>
        
        <p></p>
    </div>
</div>
@endsection

@section('footer')
<!-- ChartJS -->
<script src="/template/admin/plugins/chart.js/Chart.js"></script>
<script>
    $(function() {
        
        const labels_year = {!!$arrayJobsYear!!};
        const datasets_year = {!!$arrayJobsYearTotal!!};
        const labels_montth = {!!$arrayJobsYearMonth!!};
        
        var areaChartData = { 
        labels  : labels_year,
        datasets: [
            {
            label               : 'Total Job',
            backgroundColor     : '#45818D',
            borderColor         : '#45818D',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#45818D',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#45818D',
            barPercentage       : 0.5,
            data                : datasets_year,
            },
        ]
        }
        var barChartCanvas = $('#chartTotalJobs').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        barChartData.datasets[0] = temp0

        var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : true,
        datasetFill             : false,
        scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    
                    scaleLabel: {
                        display: true,
                        fontSize: 20,
                        fontColor: '#666',
                        labelString: 'Year',
                        
                    },
                    
                }]
            }
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
            var val_select = $(this).val();
            //Year
            if(val_select === '1'){
                // set value null
                $('#select-year-from').val('');
                $('#select-year-to').val('');
                // select year hide div date year-month and show div date year
                $('#divSelectDate').hide();
                $('#divSelectYear').show();
                // update labels and dataset for chart year
                myChart.config.data.labels = labels_year;
                myChart.config.data.datasets[0].data = datasets_year;
                myChart.options.scales.xAxes[0].scaleLabel.labelString = "Year";
                myChart.update();
            }
            // Year month
            else{
                //set value null
                $('#textdatefrom').val('');
                $('#textdateto').val('');
                // select year hide div date year and show div date year-month
                $('#divSelectDate').show();
                $('#divSelectYear').hide();
            }
        });

        //Select month year from to and filter month year
        $('.dateyearmonth').on('change', function(){
            var datefrom = $('#textdatefrom').val();
            var dateto = $('#textdateto').val();
            //datefrom & dateto not null
            if(datefrom != "" && dateto != ""){
                arr_date = [datefrom, dateto];
                var data = filterData(labels_montth, arr_date);
                var data_labels = data[0];
                var data_sets = data[1];
                myChart.config.data.labels = data_labels;
                myChart.config.data.datasets[0].data = data_sets;
                myChart.options.scales.xAxes[0].scaleLabel.labelString = "Year month";
                myChart.update();
            }
        });

        //Select year from to and filter year
        $('.dateyear').on('change', function(){
            var datefrom = $('#select-year-from').val();
            var dateto = $('#select-year-to').val();
            //datefrom & dateto not null
            if(datefrom != "" && dateto != ""){
                arr_date = [datefrom, dateto];
                var data = filterYear([labels_year, datasets_year], arr_date);
                var data_labels = data[0];
                var data_sets = data[1];
                myChart.config.data.labels = data_labels;
                myChart.config.data.datasets[0].data = data_sets;
                myChart.options.scales.xAxes[0].scaleLabel.labelString = "Year";
                myChart.update();
            }
        });

        function filterYear(data, date){
            var data_labels = [...data[0]];
            var data_sets = [...data[1]];
            //index start
            var in_start = data_labels.indexOf(date[0]);
            //index end
            var in_end = data_labels.indexOf(date[1]);

            var labels = data_labels.slice(in_start, in_end + 1);
            var datasets = data_sets.slice(in_start, in_end + 1);
            
            return [labels, datasets];
        }

        function filterData(data, date){
            var data_labels = [...data];

            //create arr date(year month) missing
            //ex date = arr ['2021-01', '2022-08']
            //=> arr_temp_year_month = ['2021-01', '2021-02', ..., '2021-12', '2022-01', ..., '2022-12']
            var arr_temp_year_month = [];
            for(let i = 0; i < (parseInt(date[1].slice(0,4)) - parseInt(date[0].slice(0,4)) + 1 ); i++ ){
                for(let j = 1; j <= 12; j++){
                    var y_m =  (parseInt(date[0].slice(0,4)) + i).toString() + "-" + ((j<10) ? "0" + j : j);
                    arr_temp_year_month.push(y_m);
                }
            }

            // get index number in array
            var indexstartdate = -1;
            for(let i = 0; i < data_labels.length; i++){
                if(data_labels[i].slice(0,4) === date[0].slice(0,4)){
                    indexstartdate = i;
                    break;
                }
            }
            //get index end 
            var indexenddate = -1;
            for(let i = data_labels.length - 1; i >= 0 ; i--){
                if(data_labels[i].slice(0,4) === date[1].slice(0,4)){
                    indexenddate = i;
                    break;
                }
            }

            // slice the array datasets and array labels
            var filterDateLabels = data_labels.slice((indexstartdate == -1) ? 0 : indexstartdate, (indexenddate == -1) ? data_labels.length : indexenddate + 1);
            filterDateLabels = [...filterDateLabels, ...arr_temp_year_month]

            //count the duplicate elements ex: arr ['2021-01', '2021-02', '2021-02','2021-03', '2021-03', '2021-04']
            //=> obj {'2021-01': 1, '2021-02': 2, '2021-03': 2, '2021-04': 1}
            var count_dupli_arr = {}
            filterDateLabels.forEach(function (x) { count_dupli_arr[x] = (count_dupli_arr[x] || 0) + 1; });

            //sort obj by keys Ex {'2021-02': 2, '2021-01': 1, '2021-03': 2}
            // => {'2021-01': 2, '2021-02': 1, '2021-03': 2}
            var sort_arr_by_keys = Object.keys(count_dupli_arr).sort().reduce(
                (obj, key) => { 
                    obj[key] = count_dupli_arr[key]; 
                    return obj;
                }, 
                {}
            );
            // console.log(ordered)
            var keys_year_month = Object.keys(sort_arr_by_keys);
            var values_year_month = Object.values(sort_arr_by_keys).map(i => i - 1);
            
            // slice the array datasets and array labels
            var labels = keys_year_month.slice(keys_year_month.indexOf(date[0]), keys_year_month.indexOf(date[1]) + 1);
            var datasets = values_year_month.slice(keys_year_month.indexOf(date[0]), keys_year_month.indexOf(date[1]) + 1);
            
            return [labels, datasets];
        }

    })
</script>
@endsection