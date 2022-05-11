@extends('layouts.admin')
@section('header')
<style>
    .job-key{
        max-width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .table-hover td{
        border: 0;
    }
    .bg-fake-light{
        background-color: #F8F9FA;
    }
    .cv-ordinal{
        max-width: 50px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid bg-white">
    <!-- /.content-header -->
    @include('admin.alert')
    <h1>HOME</h1>
</div>
@endsection
@section('footer')
<script >
    //Select option job
    $('#job_id').on('change', function() {
        id_select = $(this).val();
        
        filterSelectOption(id_select);
    });

    //Select option status cv
    $('.select_cv_status_id').on('change', function(){
        var cv_status_id = $(this).val();
        var cv_id = $(this.options[this.selectedIndex]).closest('optgroup').prop('id');
        
        var job_id = $('#job_id').find(":selected").val();
        // console.log("Home Id: ", cv_id, " value: ", cv_status_id, " val id job: ", job_id);
        var arr_id = [cv_id, cv_status_id, job_id];
        updateStatusCv(arr_id);
    });


    function filterSelectOption(id) {
        // param array
        //
        // return view(temp_home.blade.php)
        $.ajax({
            type: 'GET',
            url: '/home/filter/' + id,
            success: function(response) {
                // console.log(response); 
                $('#tablelistcvhome').fadeIn();
                $('#tablelistcvhome').html(response);
            },
            error: (err) => {
                console.log({
                    err
                });
            }
        });
    }

    function updateStatusCv(arr_id){
        $.ajax({
            type: "GET",
            url: '/home/update/' + arr_id,
            success: function(response) {
                $('#tablelistcvhome').html(response);
                // console.log("response ", response);
            },
            error: (err) => {
                console.log({
                    err
                });
            }
        });
    }

    // window.setTimeout(function() {
    //     $(".alert").fadeTo(500, 0).slideUp(500, function() {
    //         $(this).remove();
    //     });
    // }, 10000);
</script>
@endsection