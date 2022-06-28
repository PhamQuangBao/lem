@extends('layouts.admin')
@section('header')
<link rel="stylesheet" href="/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<style>
    fieldset {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    legend {
        width: auto;
    }
    /* auto resize table */
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="d-flex">
    <div class="mt-2 h5">
        @if($job->job_status_id == 1)
        <span class="badge bg-success">
            {{ $job->JobStatuses->name }}
        </span>
        @elseif($job->job_status_id == 2)
        <span class="badge bg-danger">
            {{ $job->JobStatuses->name }}
        </span>
        @else
        <span class="badge bg-warning">
            {{ $job->JobStatuses->name }}
        </span>
        @endif
    </div>
    <div class="mt-2 ml-2 h5">
        <span class="badge bg-primary">
            {{ count($job->Profile) }} Profile
        </span>
    </div>
    <div class="mt-2 ml-2 h5">
        <span class="badge bg-warning">
            {{ count($arrProfileInterview) }} Interviewed
        </span>
    </div>
    <div class="mt-2 ml-2 h5">
        <span class="badge bg-danger">
            {{ count($arrProfileOffer) }} Offer
        </span>
    </div>
    <div class="mt-2 ml-2 h5">
        <span class="badge bg-dark">
            {{ count($arrProfileUnqualified) }} Unqualified
        </span>
    </div>
</div>
<div class="container-fluid border p-3 bg-light">
    <!-- show message -->
    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-warning">
        {{ session()->get('error') }}
    </div>
    @endif
    <!-- /.end show message -->

    <form action="/jobs/{{ $job->id }}/updateDetail" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" style="max-width: 75%;" name="job_status_id" id="job_status_id">
                        @foreach ($job_statuses as $status)
                        <option @if ($status->id == $job->job_status_id) selected @endif value="{{$status->id}}">
                            {{$status->name}}
                        </option>
                        @endforeach
                    </select>
                    <div class="input-group-append" id="div-submits-job-status">
                    </div>
                </div>
            </div>
            <div class="col-md-6 row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Request date</label>
                        <p>{{ $job->request_date }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Close Date</label>
                        <p class="text-danger">{{ empty($job->close_date)? '' : $job->close_date }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Job ID</label>
                    <p>{{ $job->key }}</p>
                </div>
            </div>
            <div class="col-md-6 row">
                <div class="form-group col-md-6">
                    <label>Skill</label>
                    <p>{{ $job->Branches->name }}</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" name="description" rows="3" readonly>{{ $job->description }}</textarea>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" id="submits" class="btn btn-primary">Save</button>
                <a type="button" href="/jobs/list" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </form>
    <fieldset class="border bg-light px-5">
        <legend class='text-danger'>Responses</legend>
        <form action="/jobs/importResonse" class="pb-5" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="jobId" value="{{ $job->id }}">
                    <div style="float:left; margin:0 20px 0 0; padding-top: 10px">
                        <label>Attachment File<span class="text-danger"> *</span></label>
                    </div>
                    <div class="col-md-4" style="float:left">
                        <input type="file" class="form-control col-xs-9" name="fileUpload" id="fileUpload" placeholder="import file(.xlsx)">
                    </div>
                    <div style="float:left; margin:0 20px 0 0; padding-top: 10px">
                        (.xlsx)
                    </div>
                    <div style="float:left; margin:0 20px 0 0;padding-left: 40px">
                        <button type="submit" id="submit-import" class="btn btn-primary">Import</button>
                        <a type="button" href="/jobs/list" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
        @isset($arrQuestion)
            <div style="float:left; margin-left:40%">
                <a class="btn" style="background-color:#ff9999">
                <i></i>
                </a> Email exits
            </div>
            <form action="/jobs/check-profiles" target="_blank" method="post">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <table id="example" class="table table-bordered table-striped dataTable dtr-inline">
                    <thead>
                        <tr>
                            @if (request()->is('jobs/'. $job->id .'/detail*'))
                                <th class="no-sort"><input type="checkbox" name="select-all" id="select-all" />  Select all</th>
                            @endif
                            @foreach ($arrQuestion as $item)
                                <th>{{ $item }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($arrAnswer) && !empty($arrAnswer))
                            @for ($y = 0; $y < count($arrAnswer); $y++)
                                <tr @if (isset($arrAnswer[$y]['check']) && $arrAnswer[$y]['check'] === true ) style='background-color:#ff9999' @endif>
                                    @if (request()->is('jobs/'. $job->id .'/detail*'))
                                        <div >
                                            <td class="text-center"><input type="checkbox" name="selectSave[]" id="" value="{{ json_encode($arrAnswer[$y]) }}"></td>
                                        </div>
                                    @endif
                                    @for ($i = 0; $i < count($arrQuestion); $i++)
                                        <td>{{ isset($arrAnswer[$y]['answer_'.$i]) ? $arrAnswer[$y]['answer_'.$i] : '' }}</td>
                                    @endfor
                                </tr>
                            @endfor
                        @else
                            <tr>
                                <td class="text-center" colspan="{{ count($arrQuestion) }}"> answer not found! </td>
                                @for ($i = 0; $i < count($arrQuestion)-1; $i++)
                                    <td style="display: none;"></td>
                                @endfor
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (request()->is('jobs/'. $job->id .'/detail*'))
                    <div class="text-center py-3">
                        <button type="submit" id="check_profile" class="btn btn-primary">Check Profile</button> 
                    </div>
                @endif
            </form>
            @if (request()->is('jobs/importResonse*'))
                <form action="/jobs/storeResonse" method="post" onSubmit="submitAction();">
                    @csrf
                    @foreach ($arrAnswer as $item)
                        <input type="hidden" value="{{ json_encode($item) }}" name="answer[]">
                    @endforeach
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <input type="hidden" value="{{ json_encode($arrQuestion) }}" name="question">
                    <div class="text-center py-3">
                        <button type="submit" id="submits" class="btn btn-primary">Save responses</button>
                    </div>
                </form>
            @endif
        @endisset
    </fieldset>
</div><!-- /.container -->
@endsection

@section('footer')
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
<script>
    $('#submits').attr('disabled', true);
    $('.form-control').change(function() {
        //set button submit is eneble if all value is not null
        if ($('#job_status_id').val() != '') {
            $('#submits').attr('disabled', false);
        } else {
            $('#submits').attr('disabled', true);
        }
    });

    //auto resize textarea
    $("textarea").each(function() {
        this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
    }).on("input", function() {
        this.style.height = "auto";
        this.style.height = (this.scrollHeight) + "px";
    });

    //Submit disable, enabled when submit file
    $('#submit-import').attr('disabled', 'disabled');
    $('#fileUpload').change(function() {
        let allowedFiles = [".xlsx"];
        let fileName = $('#fileUpload').val();
        let endFileName = fileName.substring(fileName.lastIndexOf('.'));
        if (allowedFiles.indexOf(endFileName) > -1) {
            $('#submit-import').removeAttr('disabled');
        } else {
            $('#submit-import').attr('disabled', 'disabled');
        }
    });

    $('#job_status_id').change(function() {
        $('#div-submits-job-status').empty();
        if(parseInt({{ $job->job_status_id }}) != parseInt($('#job_status_id').val())){
            $('#div-submits-job-status').append("<button type='submit' id='submits-job-status' class='btn btn-primary'>Save</button>");
        }else{
            $('#div-submits-job-status').empty();
        }
    });

    $('#example').DataTable( {
        "order": [[ 1, "desc" ]],
        "scrollX": true,
        scrollCollapse: true,
        "paging":   false,
        columnDefs: [
            { 
                'targets': 0, /* column index */
                'orderable': false, /* true or false */
             }
        ],
        fixedColumns: true,
    });

    $('#check_profile').attr('disabled', true);
    var countChecked = function() {
        var n = $( "input:checked" ).length;
        return parseInt(n);
    };
    countChecked();
 
    $( "input[type=checkbox]" ).change(function() {
        console.log(countChecked());
        if (countChecked() > 0 ) {
            $('#check_profile').attr('disabled', false);
        } else {
            $('#check_profile').attr('disabled', true);
        }
    });
    // Listen for click on toggle checkbox
    $('#select-all').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;                       
            });
        }
    });
    $(function() {
        var check = {!!isset($addResponses) ? "true" : "false" !!}
        if (check) {
            alert('Successfully imported responses preview!');
        }
    });

    function copytoClipboard() {
        /* Get the text field */
        var copyText = document.getElementById("url_google_form");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        //Change icon copy -> icon check oke
        navigator.clipboard.writeText(copyText.value);
        $('#iconCopy').empty();
        $('#iconCopy').append("<i class='far fa-check-circle fa-lg text-success'></i>");

        //delay 1,5s => change to icon copy
        setTimeout(() => {
            $('#iconCopy').empty();
            $('#iconCopy').append("<i class='far fa-copy fa-lg text-dark'></i>");
        }, 1500);
    }
</script>
@endsection