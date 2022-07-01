@extends('layouts.admin')
@section('header')
<!-- Data table -->
<link rel="stylesheet" href="/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Multiple Select -->
<link rel="stylesheet" href="/template/admin/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/template/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<style>
    #description{
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .th-quanlity-profile{
        max-width: 100px;
    }
</style>
@endsection
@section('content')
<div class="card">
<div class="container-fluid border-bottom">
        <div class="row">
            <div class="col-5 row">
                @foreach($jobStatuses as $item)
                <div class="form-check form-check-inline font-weight-bold mx-4 @if($item->id == 1) text-success @else @if($item->id == 2) text-danger @else text-warning @endif @endif">
                    <input class="form-check-input job_checkbox" id="{{$item->name}}" name="checkbox_id[]" type="checkbox" id="inlineCheckbox1" value="{{ $item->id }}">
                    <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                </div>
                @endforeach
            </div>
            <div class="col-7 row mt-3">
                <div class="col-6 row form-group">
                    <label class="col-3 col-form-label text-right" for="From">From</label>
                    <div class="col-9 input-group date" id="reservationdate1" data-target-input="nearest">
                        <input type="date" id="min" class="form-control input_date" name="">
                    </div>
                </div>
                <div class="col-6 row form-group">
                    <label class="col-2 col-form-label text-right" for="To">To</label>
                    <div class="col-9 input-group date" id="reservationdate2" data-target-input="nearest">
                        <input type="date" id="max" class="form-control input_date" name="">
                    </div>
                </div>
            </div>
        </div>
        <div class="border-top">
            <div class="row my-3 mx-2">
                <div class="col row ">
                    <div class="col-1">
                        <label class="col-form-label">Branches</label>
                    </div>
                    <div class="col-11">
                        <div class="select2-purple">
                            <select class="form-control" multiple="multiple" data-dropdown-css-class="select2-purple" id="multipleSelectSkill" data-placeholder="-- Select skill --" style="width: 100%;">
                                @foreach($branches as $branch)
                                <option value="{{$branch->name}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        @include('admin.alert')
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="dataTables_length" id="example_length"></div>
            <div class="changefilter_div">
                <div class="row ">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                            <thead class="text-center thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Job ID</th>
                                    <th>Job Description</th>
                                    <th>Status</th>
                                    <th>Skill</th>
                                    <th class="th-quanlity-profile">Total Profiles</th>
                                    <th>Request at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($jobs as $key => $job)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>{{ $job->key }}</td>
                                    <td id="description" class="text-left">{{ $job->description }}</td>
                                    <td class="@if($job->JobStatuses->id == 1) text-success @else @if($job->JobStatuses->id == 2) text-danger @else text-warning @endif @endif">{{ $job->JobStatuses->name }}</td>
                                    <td>{{ $job->Branches->name }}</td>
                                    <td><a href="/profile/list/{{$job->id}}" target="_blank">{{ count($job->Profile) }}</a></td>
                                    <td>{{ $job->request_date }}</td>
                                    <td class="align-middle">
                                        <a href="/jobs/{{$job->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                        <a href="/jobs/{{$job->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                                        <a href="/jobs/{{$job->id}}/delete" onclick="return confirm('are you sure delete job key: {{$job->key}}?')" class="fa fa-trash text-danger" aria-hidden="true"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">

                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('footer')
<!-- DataTables & Plugins -->
<script src="/template/admin/plugins/jquery/jquery.min.js"></script>
<script src="/template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/template/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/template/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/jszip/jszip.min.js"></script>
<script src="/template/admin/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/template/admin/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="/template/admin/dist/js/adminlte.min.js?v=3.2.0"></script>
<!-- Multiple Select -->
<script src="/template/admin/plugins/select2/js/select2.full.min.js"></script>
<script>
    // Multiple Select
    //Initialize Select2 Elements
    $('#multipleSelectSkill').select2()

    //Initialize Select2 Elements
    $('#select2').select2({
      theme: 'bootstrap4'
    })
    

    // Custom filtering function which will search data in column four between two values
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var filterstart = $('#min').val();
            var filterend = $('#max').val();
            var iStartDateCol = 6; //using column 5 in this Request at
            var iEndDateCol = 6;

            var tabledatestart = data[iStartDateCol]; //Value of each row in column Request at
            var tabledateend = data[iEndDateCol];

            if (filterstart === "" && filterend === "") { //Show data when no choose date picker
                return true;
            }
            //filterstart = data table Or (filterstart before data table and filterend = null)
            else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && filterend === "") {
                return true;
            }
            //filterend = data Or (filterend after data table and filterstart == null )
            else if ((moment(filterend).isSame(tabledateend) || moment(filterend).isAfter(tabledateend)) && filterstart === "") {
                return true;
            }
            //filterstart = data Or ( filterstart before date table and filterend date table ) Or filterend after data table
            else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && (moment(filterend).isSame(tabledateend) || moment(filterend).isAfter(tabledateend))) {
                return true;
            }
            return false;
        }
    );

    $(document).ready(function() {
        var table = $("#example1").DataTable();
        $("#example1").DataTable().column(4).visible(false);
        $(document).on('click', '.job_checkbox', function() {

            var val_checkbox = [];
            var counter = 0;
            $('.job_checkbox').each(function() {
                if ($(this).is(":checked")) {
                    val_checkbox.push($(this).attr('id'));
                    counter++;
                }
            });
            //Datatable
            var reset = ''
            //check box not checked
            if (counter == 0) {
                table.column(3).search(reset).draw();
            } else {
                table.column(3).search(reset).draw();
                let search = ''
                for (let i = 0; i < counter; i++) {

                    search = search.concat(val_checkbox[i]);
                    if (i < counter - 1) {
                        search = search.concat('|');
                    }
                }
                table.column(3).search(search, true).draw();
            }
        });


        $('.input_date').change(function() {
            //DataTables to update the display to reflect these changes.
            table.draw();
        });

        //
        $('#multipleSelectSkill').on('change', function() {
            if(($(this).val()).length !== 0){
                var arr_skill_id = $(this).val();
                table.column(4).search(arr_skill_id.join('|'), true, false, true).draw();
            }
            else{
                table.column(4).search("").draw();
            }
        });

    });

    
</script>
@endsection