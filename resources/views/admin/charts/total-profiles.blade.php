@extends('layouts.admin')
@section('header')
<!-- This is a hot fix when you update your production from non-ssl to ssl, anyway you have to fix all the links one by one to https -->
<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
@endsection
@section('content')
<div class="container border p-3 bg-light">
    @include('admin.alert')
    <div class="pb-3 row justify-content-md-center">
        <div class="form-group col-md-2">
            <label for="inputState">Filter</label>
            <select id="selectFilter" class="form-control">
                <option value="1">Year</option>
                <option value="2">Month</option>
            </select>
        </div>
        <div class="form-group col-md-4" id="divSelectYear">
            <label for="Select year">Select year</label>
            <select name="select-year" id="select-year" class="form-control">
                @foreach ($listYear as $item)
                    <option value="{{ $item[1] }}">{{ $item[0] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row col-md-4" id="divSelectMonth">
            <div class="form-group col-md-6">
                <label for="inputEmail4">From</label>
                <input class="form-control selectMonth" type="month" name="datefrom" id="datefrom" min="{{ count($listYearMonth) != 0 ? end($listYearMonth)[0] : ""}}" max="{{ count($listYearMonth) != 0 ? $listYearMonth[0][0] : ""}}">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">To</label>
                <input class="form-control selectMonth" type="month" name="dateto" id="dateto" min="{{ count($listYearMonth) != 0 ? end($listYearMonth)[0] : "" }}" max="{{ count($listYearMonth) != 0 ? $listYearMonth[0][0] : "" }}">
            </div>
        </div>
        <div class="col-md-10 pt-3">
            <!-- BAR CHART -->
            <canvas id="chartTotalProfileByChannels"></canvas>
        </div>
    </div>
    <p></p>
    <!-- /.row -->
</div><!-- /.row -->
@endsection

@section('footer')
<!-- ChartJS -->
<script src="/template/admin/plugins/chart.js/Chart.js"></script>
<script>
    //built chart
    $(function() {
        const labels =  {!!$arrayX!!};
        //built chart defalt data
        const data = {
            labels: labels,
            datasets: [{
                label: 'Profile',
                backgroundColor: '#28a745',
                borderColor: '#20c997',
                data: $('#select-year').val().split(','),
                barPercentage: 0.5,
            }]
        };
        //config chart
        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                        }
                    }]
                },
            }
        };
        //create chart
        const myChart = new Chart(
            document.getElementById('chartTotalProfileByChannels'),
            config
        );
        //update chart by date range
        $('#select-year').change(function() {
            myChart.data.datasets[0].data = $('#select-year').val().split(',');
            myChart.update();             
        });

        //disabled input type month
        $('#divSelectMonth').hide();
        $('#selectFilter').on('change', function() {
            var val_select = $(this).val();
            //Year
            if(val_select === '1'){
                // select year hide div date year-month and show div date year
                $('#divSelectMonth').hide();
                $('#divSelectYear').show();
            }
            // Year month
            else{
                // select year hide div date year and show div date year-month
                $('#divSelectMonth').show();
                $('#divSelectYear').hide();
            }
        });

        //update chart by date range
        $('.selectMonth').change(function() {
            if($('#dateto').val() != "" && $('#datefrom').val() != ""){
                var from_date = $('#datefrom').val();
                var to_date = $('#dateto').val();
                var yearMonth = monthsBetween(from_date, to_date);
                var array = @json($listYearMonth);
                var totalProfile = 0;
                var totalInteview = 0;
                var totalOffered = 0;
                yearMonth.forEach(async (month) => {
                    for( var i = 0; i < array.length; i++ ) {
                        if( array[i][0] === month ) {
                            result = array[i][1].split(',');
                            totalProfile = totalProfile + parseInt(result[0]);
                            totalInteview = totalInteview + parseInt(result[1]);
                            totalOffered = totalOffered + parseInt(result[2]);
                        }
                    }
                });
                myChart.data.datasets[0].data = [totalProfile,totalInteview,totalOffered];
                myChart.update();
                console.log("totalProfile: ",totalProfile,"totalInteview: ",totalInteview,"totalOffered: ",totalOffered);
            }
        });

        function monthsBetween(...args) {
            let [a, b] = args.map(arg => arg.split("-").slice(0, 2).reduce((y, m) => m - 1 + y * 12));
            return Array.from({length: b - a + 1}, _ => a++).map(m => ~~(m / 12) + "-" + ("0" + (m % 12 + 1)).slice(-2));
        }
    })
</script>
@endsection
