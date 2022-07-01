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
    .profile-ordinal{
        max-width: 50px;
    }
    .div-action{
        max-width: 100px;
    }
    .div-phone{
        max-width: 180px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid bg-white">
    <!-- /.content-header -->
    @include('admin.alert')
    <div class="row border-bottom">
        <div class="col-12 card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-warning">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center">Total open jobs</span>
                                    <h3 class="info-box-number text-center mb-0">{{ $count_job_open }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-success">
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark text-center">Total Profiles of open jobs</span>
                                    <h3 class="info-box-number text-dark text-center mb-0">{{ $totalProfile }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <a href="/profile/add">
                                <div class="info-box bg-primary">
                                    <div class="info-box-content">
                                        <h3 class="info-box-text text-dark text-center mb-0">Add New Profile</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
        <div class="col-12 row pt-4 pb-2 border-top">
            <div class="form-group pl-4 col-sm-6 row">
                <span class="col-sm-4 col-form-label">Filter by job</span>
                <div class="col-sm-8">
                    <select class="form-control" name="" id="job_id">
                        <option value="All">
                            -- All --
                        </option>
                        @if(isset($selected_job))
                            @foreach ($jobs as $item)
                                @if ($selected_job == $item->id)
                                    <option value="{{ $item->id }}"
                                        selected="selected">
                                        {{ $item->key }}
                                    </option>
                                @else
                                    <option value="{{ $item->id }}">
                                        {{ $item->key }}
                                    </option>
                                @endif
                            @endforeach
                        @else
                            @foreach ($jobs as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->key }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div id="tableListProfileHome">
        <h5 class="pl-3 py-3 text-danger font-weight-bold">
            {{ $totalProfile }} Profile
        </h5>
        @if(isset($success))
        <div class="alert alert-success">
            <button type="button" class="close pl-1" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong>   {{ $success }}
        </div>
        @endif
        <!--  -->
        <table class="table table-hover pl-5">
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="true">
                    <td class="text-primary h5">
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        New application
                    </td>
                </tr>
                <tr class="expandable-body" id="1">
                    <td id="td_new_application">
                        @foreach($profilesNew as $key => $profile)
                        <div class="row my-1 ml-4">
                            <div class="col-1 pt-2 border-left border-top border-bottom text-center font-weight-bold bg-light profile-ordinal">{{(($key + 1) < 10) ? "0" . ($key + 1): ($key + 1)}}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom text-center bg-light job-key">{{ $profile->profileJobs->key }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light">{{ $profile->name }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light div-phone">{{ $profile->phone_number }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 py-2 border-top border-bottom bg-light">
                                <select class="form-control form-control-sm select_profile_status_id" name="profile_status_id" id="status_id">
                                    <optgroup label="New application" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 1)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="In progress" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 2)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Unqualified" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 3)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Onboarding" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 4)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom border-right text-center bg-light div-action">
                                <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                            </div>
                        </div>
                        @endforeach
                    </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                    <td class="text-warning h5">
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        In progress
                    </td>
                </tr>
                <tr class="expandable-body">
                    <td id="td_in_progress">
                        @foreach($profilesInp as $key => $profile)
                        <div class="row my-1 ml-4">
                            <div class="col-1 pt-2 border-left border-top border-bottom text-center font-weight-bold bg-light profile-ordinal">{{(($key + 1) < 10) ? "0" . ($key + 1): ($key + 1)}}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom text-center bg-light job-key">{{ $profile->profileJobs->key }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light">{{ $profile->name }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light div-phone">{{ $profile->phone_number }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 py-2 border-top border-bottom bg-light">
                                <select class="form-control form-control-sm select_profile_status_id" name="profile_status_id" id="status_id">
                                    <optgroup label="New application" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 1)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="In progress" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 2)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Unqualified" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 3)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Onboarding" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 4)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom border-right text-center bg-light div-action">
                                <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                            </div>
                        </div>
                        @endforeach
                    </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                    <td class="text-danger h5">
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        Unqualified
                    </td>
                </tr>
                <tr class="expandable-body">
                    <td id="td_unqualified">
                        @foreach($profilesUnqualified as $key => $profile)
                        <div class="row my-1 ml-4">
                            <div class="col-1 pt-2 border-left border-top border-bottom text-center font-weight-bold bg-light profile-ordinal">{{(($key + 1) < 10) ? "0" . ($key + 1): ($key + 1)}}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom text-center bg-light job-key">{{ $profile->profileJobs->key }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light">{{ $profile->name }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light div-phone">{{ $profile->phone_number }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 py-2 border-top border-bottom bg-light">
                                <select class="form-control form-control-sm select_profile_status_id" name="profile_status_id" id="status_id">
                                    <optgroup label="New application" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 1)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="In progress" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 2)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Unqualified" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 3)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Onboarding" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 4)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom border-right text-center bg-light div-action">
                                <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                            </div>
                        </div>
                        @endforeach
                    </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                    <td class="text-success h5">
                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                        Qualified
                    </td>
                </tr>
                <tr class="expandable-body">
                    <td id="td_onboarding">
                        @foreach($profilesQualified as $key => $profile)
                        <div class="row my-1 ml-4">
                            <div class="col-1 pt-2 border-left border-top border-bottom text-center font-weight-bold bg-light profile-ordinal">{{(($key + 1) < 10) ? "0" . ($key + 1): ($key + 1)}}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom text-center bg-light job-key">{{ $profile->profileJobs->key }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light">{{ $profile->name }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 pt-2 border-top border-bottom text-center font-weight-bold bg-light div-phone">{{ $profile->phone_number }}</div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-3 py-2 border-top border-bottom bg-light">
                                <select class="form-control form-control-sm select_profile_status_id" name="profile_status_id" id="status_id">
                                    <optgroup label="New application" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 1)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="In progress" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 2)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Unqualified" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 3)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Onboarding" id="{{ $profile->id }}">
                                        @foreach ($profileStatuses as $item)
                                            @if($item->profile_status_group_id == 4)
                                                @if ($item->id === $profile->profile_status_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="border-top border-bottom pt-2 bg-light">|</div>
                            <div class="col-2 pt-2 border-top border-bottom border-right text-center bg-light div-action">
                                <a href="/profile/{{$profile->id}}/detail" class="fa fa-eye text-primary" aria-hidden="true"></a>
                                <a href="/profile/{{$profile->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                            </div>
                        </div>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
        <!--  -->
    </div>
</div>
@endsection
@section('footer')
<script >
    //Select option job
    $('#job_id').on('change', function() {
        id_select = $(this).val();
        
        filterSelectOption(id_select);
    });

    //Select option status profile
    $('.select_profile_status_id').on('change', function(){
        var profile_status_id = $(this).val();
        var profile_id = $(this.options[this.selectedIndex]).closest('optgroup').prop('id');
        
        var job_id = $('#job_id').find(":selected").val();
        // console.log("Home Id: ", profile_id, " value: ", profile_status_id, " val id job: ", job_id);
        var arr_id = [profile_id, profile_status_id, job_id];
        updateStatusProfile(arr_id);
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
                $('#tableListProfileHome').fadeIn();
                $('#tableListProfileHome').html(response);
            },
            error: (err) => {
                console.log({
                    err
                });
            }
        });
    }

    function updateStatusProfile(arr_id){
        $.ajax({
            type: "GET",
            url: '/home/update/' + arr_id,
            success: function(response) {
                $('#tableListProfileHome').html(response);
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