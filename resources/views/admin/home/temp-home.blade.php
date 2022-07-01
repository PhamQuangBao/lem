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

        <tr data-widget="expandable-table" aria-expanded="true">
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

        <tr data-widget="expandable-table" aria-expanded="true">
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

        <tr data-widget="expandable-table" aria-expanded="true">
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
<script>
    //Select option status profile
    $('.select_profile_status_id').on('change', function(){
        var profile_status_id = $(this).val();
        var profile_id = $(this.options[this.selectedIndex]).closest('optgroup').prop('id');
        
        var job_id = $('#job_id').find(":selected").val();
        // console.log("Home Id: ", profile_id, " value: ", profile_status_id, " val id job: ", job_id);
        var arr_id = [profile_id, profile_status_id, job_id];
        updateStatusCv(arr_id);
    });

    function updateStatusCv(arr_id){
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

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);
</script>