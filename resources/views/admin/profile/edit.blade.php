@section('header')
<!-- This is a hot fix when you update your production from non-ssl to ssl, anyway you have to fix all the links one by one to https -->
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- Tempusdominus Bootstrap 4 Date Time -->
<link rel="stylesheet" href="/template/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection
@extends('layouts.admin')
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
    <form action="/profile/{{$profile->id}}/update" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status<span class="text-danger"> *</span></label>
                    <select class="form-control" style="width: 100%;" name="profile_status_id">
                        <optgroup label="New application">
                            @foreach ($profileStatuses as $item)
                            @if($item->profile_status_group_id == 1)
                            @if ($profile->profile_status_id == $item->id || old('profile_status_id') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="In progress">
                            @foreach ($profileStatuses as $item)
                            @if($item->profile_status_group_id == 2)
                            @if ($profile->profile_status_id == $item->id || old('profile_status_id') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Unqualified">
                            @foreach ($profileStatuses as $item)
                            @if($item->profile_status_group_id == 3)
                            @if ($profile->profile_status_id == $item->id || old('profile_status_id') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Onboarding">
                            @foreach ($profileStatuses as $item)
                            @if($item->profile_status_group_id == 4)
                            @if ($profile->profile_status_id == $item->id || old('profile_status_id') == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @else
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                            @endif
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="@if(old('name')){{old('name')}}@else{{$profile->name}}@endif" name="name" placeholder="Full name">
                    @error('name')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control @error('mail') is-invalid @enderror" id="exampleInputEmail1" name="mail" value="@if(old('mail')){{old('mail')}}@else{{$profile->mail}}@endif" placeholder="email@email.com">
                    @error('mail')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- /.form-group -->
            </div>
        </div> <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone Number<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" value="@if(old('phone_number')){{old('phone_number')}}@else{{$profile->phone_number}}@endif" placeholder="Phone number" name="phone_number">
                    @error('phone_number')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div><!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Job<span class="text-danger"> *</span></label>
                    <select class="form-control" name="job_id" id="jobId">
                        @foreach($jobs as $job)
                        @if (old('job_id') == $job->id || $profile->job_id == $job->id)
                        <option value="{{ $job->id }}" id="{{$job->Branches->name}}" selected>{{ $job->key }}</option>
                        @else
                        <option value="{{$job->id}}" id="{{$job->Branches->name}}">{{$job->key}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Branches</label>
                    <?php $checkOldJob = 0; ?>
                    @foreach($jobs as $job)
                    @if (old('job_id') == $job->id || $profile->job_id == $job->id)
                    <?php $checkOldJob = 1; ?>
                    <input type="text" class="form-control" value="{{$job->Branches->name}}" disabled="true" id="txtNameBranch">
                    @break
                    @endif
                    @endforeach
                    @if($checkOldJob == 0)
                    <input type="text" class="form-control" disabled="true" id="txtNameBranch">
                    @endif
                </div>
            </div>
        </div><!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Year of experience<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control @error('year_of_experience') is-invalid @enderror" value="@if(old('year_of_experience')){{old('year_of_experience')}}@else{{$profile->year_of_experience}}@endif" name="year_of_experience" placeholder="0" min="0">
                    @error('year_of_experience')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Submit Date:<span class="text-danger"> *</span></label>
                    <input type="date" class="form-control @error('submit_date') is-invalid @enderror" value="@if(old('submit_date')){{old('submit_date')}}@else{{$profile->submit_date}}@endif" name="submit_date">
                    @error('submit_date')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- /.form-group -->
            </div>
            <!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Note</label>
                    <textarea id="note" class="form-control" rows="3" name="note">@if(old('note')){{old('note')}}@else{{$profile->note}}@endif</textarea>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="row">
            <div class="col-md-12">
                <div style="float:left; margin:0 20px 0 0; padding-top: 10px">
                    <label>Attachment File<span class="text-danger"> *</span></label>
                </div>
                <div class="col-md-4" style="float:left">
                    <label>@if($profile->file)<a href="{{asset('/uploads/profile/'.$profile->file)}}"> {{$profile->file}} </a>@endif</label>
                    <input type="file" class="form-control col-xs-9" name="fileUpload[]" id="fileUpload" placeholder="import file(.doc, .docx, .pdf, .ipeg, .png)" multiple>
                </div>
                <div style="float:left; margin:0 20px 0 0; padding-top: 10px">
                    (.doc, .docx, .pdf, .xlsx, .ipeg, .jpg, .png)
                </div>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <button type="submit" id="submit" class="btn btn-primary">Save</button>
                <a type="button" href="/" class="btn btn-default">Cancel</a>
            </div>
            <div class="col-md-3">
                <a type="button" href="./detail" class="btn btn-outline-light">Interview</a>
            </div>
        </div>
        <!-- /.row -->
    </form>
</div><!-- /.row -->
@endsection

@section('footer')
<!-- Tempusdominus Bootstrap 4 Date Time-->
<script src="/template/admin//plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript">
    //Select key job and auto fill select Branch
    $('#jobId').on('change', function() {
        //get id in option in Select job
        //var val_selec_jobkeys = this.value;
        var val_selec_jobkeys = this.options[this.selectedIndex].id;

        //id Select branches -> option has value above -> on change 
        //$('#selectBranchesId').val(val_selec_jobkeys).trigger('change')
        $('#txtNameBranch').val(val_selec_jobkeys);
    });
    //Submit disable, enabled when submit file
    //$('#submit').attr('disabled', 'disabled');
    $('#fileUpload').change(function() {
        let allowedFiles = [".xlsx", ".xls", ".pdf", ".png", ".jpeg", ".jpg", ".doc", ".docx"];
        let fileName = $('#fileUpload').val();
        let endFileName = fileName.substring(fileName.lastIndexOf('.'));
        if (allowedFiles.indexOf(endFileName) > -1) {
            $('#submit').removeAttr('disabled');
        } else {
            $('#submit').attr('disabled', 'disabled');
        }
    });
    //auto resize for textarea
    textarea = document.querySelector("#note");
    textarea.addEventListener('input', autoResize, false);

    function autoResize() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    }

    //Date and time picker
    $('#reservationdatetime').datetimepicker({
        icons: {
            time: 'far fa-clock'
        }
    });
</script>
@endsection