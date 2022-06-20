@extends('layouts.admin')
@section('header')
<!-- Multiple Select -->
<link rel="stylesheet" href="/template/admin/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/template/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- This is a hot fix when you update your production from non-ssl to ssl, anyway you have to fix all the links one by one to https -->
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<style>
    .select2-container--default .select2-selection--multiple{
        border: 1px solid #ced4da; 
    }
</style>
@endsection

@section('content')
@include('admin.alert')
<div class="card">
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="changefilter_div">
                <div class="border-bottom border-top">
                    <div class="row my-3">
                        <div class="col-6 row ml-1">
                            <label class="col-2 col-form-label">Search:</label>
                            <input type="search" id="search" class="col-6 form-control" name="search" value="{{ request('search') }}" placeholder="     Search">
                            <div class="col-4"></div>
                        </div>
                        
                    </div>
                </div>
                <div class="border-bottom border-top">
                    <div class="row my-3">
                        <div class="col-6 row ml-1">
                            <label class="col-2 col-form-label">Status</label>
                            <select class="col-6 form-control" style="width: 100%;" name="status_id" id="status_id">
                                <option value="">-- Select status --</option>
                                @foreach($profile_statuses as $profile_status)
                                <option value="{{$profile_status->id}}">{{$profile_status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 row">
                            <label class="col-2 col-form-label">Job</label>
                            <select class="col-6 form-control" style="width: 100%;" name="job_id" id="job_id">
                                <option value="">-- Select job --</option>
                                @foreach($jobs as $job)
                                <!-- If you select total profile from the job list page, there is a Session variable from the route profile/list/{id} which is the job_id sent back and Selected it. -->
                                @if(request()->has('jobIdCallBack') && request()->input('jobIdCallBack') == $job->id)
                                    <option value="{{$job->id}}" selected>{{$job->key}}</option>
                                @else
                                    <option value="{{$job->id}}">{{$job->key}}</option>
                                @endif
                                @endforeach
                            </select>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom border-top">
                    <div class="row my-3">
                        <div class="col-1 col-form-label">
                            <label style="margin-left: 12px;">Branches</label>
                        </div>
                        <div class="col-9">
                            <div class="select2-purple" style="margin-left: 1px; margin-right: 6px;">
                                <select class="form-control" multiple="multiple" id="multipleSelectBranch" data-dropdown-css-class="select2-purple" data-placeholder="  -- Select skill --" style="width: 100%;">
                                    @foreach($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>
                </div>
                <div class="border-bottom border-top">
                    <div class="row my-3">
                        <div class="col-6 row ml-1">
                            <label class="col-2 col-form-label">From</label>
                            <input type="date" id="min" class="col-6 form-control inputDateFromTo" name="">
                            <div class="col-4"></div>
                        </div>
                        <div class="col-6 row">
                            <label class="col-2 col-form-label">To</label>
                            <input type="date" id="max" class="col-6 form-control inputDateFromTo" name="">
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
                <div id="tablelistprofile">
                    <div class="row ">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Job</th>
                                        <th>Status</th>
                                        <th>Profile Link</th>
                                        <th>Submit date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tblData" class="text-center">
                                    @foreach($profiles as $key => $profile)
                                    <tr @if ($profile->profileJobs->key === "00-00") style="background-color: orange" @endif>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $profile->name }}</td>
                                        <td>{{ $profile->profileJobs->key }}</td>
                                        <td>{{ $profile->profileStatus->name }}</td>
                                        @if(count($profile->files) >= 1)
                                            <td><p>link 1/{{count($profile->files)}}</p></td>
                                        @else
                                            <td>No file</td>
                                        @endif
                                        <td>{{ $profile->submit_date }}</td>

                                        <td class="align-middle">
                                            <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                            <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                                            <a href="/profile/{{$profile->id}}/delete" onclick="return confirm('Are you sure delete profile: {{$profile->name}}?')" class="fa fa-trash text-danger" aria-hidden="true"></i>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row justify-content-center" id="pagination">
                        {{ $profiles->links('admin.profile.paginate') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<!-- Multiple Select -->
<script src="/template/admin/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript">
    // Multiple Select
    //Initialize Select2 Elements
    $('#multipleSelectBranch').select2()

    //Initialize Select2 Elements
    $('#select2').select2({
      theme: 'bootstrap4'
    })

    //Select option and get value option job_id, skill_id, status_id, channel_id save in val_select
    //create array with 6 elements
    var id_select = ['', '', '', '', '', ''];

    $('#job_id').on('change', function() {
        if($(this).val() !== ""){
            id_select[1] = 'job_id=' + $(this).val();
        }
        else{
            id_select[1] = "";
        }
        filterSelectOption(id_select);
    });
    $('#status_id').on('change', function() {
        if($(this).val() !== ""){
            id_select[2] = 'profile_status_id=' + $(this).val();
        }
        else{
            id_select[2] = "";
        }
        filterSelectOption(id_select);
    });
    $('#multipleSelectBranch').on('change', function() {
        if(($(this).val()).length !== 0){
            id_select[4] = 'branch_id=' + $(this).val();
        }
        else{
            id_select[4] = "";
        }
        filterSelectOption(id_select);
    });
    //Date range picker
    //set value datefrom to arr id_select
    $('.inputDateFromTo').change(function() {
        var date_from = $('#min').val();
        var date_to = $('#max').val();
        if(date_from !== "" && date_to !== ""){
            id_select[5] = 'date_from_to=' + date_from + ',' + date_to;
        }
        else{
            id_select[5] = "";
        }
        filterSelectOption(id_select);
    });
    //On click pagination
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).attr('href').split('/profile/list?')[1];

        var objId = {}
        id_select.forEach(element => {
            if(element !== ''){
                arrSplit = element.split("=");
                objId[arrSplit[0]] = (arrSplit[1]);
            }
        });
        arrPage = page.split("=");
        objId[arrPage[0]] = (arrPage[1]);
        $.ajax({
            type: 'GET',
            url: '/profile/list',
            data: objId,
            success: (data) => {
                // console.log(data);
                // $('#tablelistprofile').html(data);
                $('#tblData').html(data.result);
                $('#pagination').html(data.pagination);
            },
            error: (err) => {
                console.log({
                    err
                });
            }
        });
    });

    //Search ajax
    $(document).on('input', '#search', function() {
        const searchProfile = $(this).val().trim();
        if(searchProfile !== ""){
            id_select[6] = 'search=' + searchProfile;
        }
        else{
            id_select[6] = "";
        }
        filterSelectOption(id_select);
    });

    function filterSelectOption(id) {
        var objId = {}
        id.forEach(element => {
            if(element !== ''){
                arrSplit = element.split("=");
                objId[arrSplit[0]] = arrSplit[1];
            }
        });
        $.ajax({
            type: 'GET',
            url: '/profile/list',
            data: objId,
            success: (data) => {
                $('#tblData').html(data.result);
                $('#pagination').html(data.pagination);
            },
            error: (err) => {
                console.log({
                    err
                });
            }
        });
    }

    //First page load event and Get the value from the session variable in Job Id
    window.addEventListener('load', function () {
        var job_id = $('#job_id').val();

        if (job_id !== "") {
            id_select[1] = 'job_id=' + job_id;
        }
    });

</script>

@endsection