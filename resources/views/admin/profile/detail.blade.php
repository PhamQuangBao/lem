@section('header')
<!-- Tempusdominus Bootstrap 4 Date Time -->
<link rel="stylesheet" href="/template/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection
@extends('layouts.admin')
@section('header')
<style>
    fieldset {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    legend {
        width: 200px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid pl-5 border-top bg-white ">
    @include('admin.alert')
    <form action="/profile/{{ $profile->id }}/updateDetail" id="formprofileDetail" method="post" class="needs-validation" novalidate>
        @csrf
        <div class="row pt-5">
            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status<span class="text-danger"> *</span></label>
                            <select class="form-control not-empty1 @error('profile_status_id') is-invalid @enderror" name="profile_status_id" id="status_id">
                                <option value="">----Select Status----</option>
                                <optgroup label="New application">
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
                                <optgroup label="In progress">
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
                                <optgroup label="Unqualified">
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
                                <optgroup label="Onboarding">
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
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                            @error('profile_status_id')
                            <span class="error invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p for="onboard date">Onboard date:</p>
                                <p for="onboard date">Offer salary:</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-success">{{ $profile->onboard_date }}</h4>
                                <h4 class="text-danger">{{ isset($profile->salary_offer) ? $profile->salary_offer." Million VND" : ""}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <h1>{{ $profile->name }}</h1>
                    </div>
                    <div class="col-md-5">
                        @if (count($profile->files) > 0)
                            @for ($i = 0; $i < count($profile->files); $i++)
                                <div class="row">
                                    <a href={{ strpos(' .xlsx, .xls, .doc, .docx, .txt', substr(($profile->files)[$i]->file, strpos(($profile->files)[$i]->file, '.'))) ? "https://view.officeapps.live.com/op/embed.aspx?src=" . request()->getHost() . "/uploads/cv/" . ($profile->files)[$i]->file : "/uploads/cv/" . ($profile->files)[$i]->file}} target="_blank">{{ ($profile->files)[$i]->file }}</a>
                                </div>
                                @endfor
                        @else
                        no file
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <p>{{ $job->key }}</p>
                        <p>{{ $job->Branches->name }}</p>
                    </div>
                    <div class="col-md-3">
                        <p>{{ $profile->phone_number }}</p>
                        <p>{{ $profile->mail }}</p>
                        <p>{{ $profile->address }}</p>
                    </div>
                    <div class="col-md-3">
                        <p>{{ $profile->submit_date }}</p>
                    </div>
                    <div class="col-md-3">
                        <p>Year of experience</p>
                        <h2 class="text-danger ml-5">{{ $profile->year_of_experience }}</h2>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Note">Note</label>
                    <textarea class="form-control" disabled name="" id="" rows="3">{{ $profile->note }}</textarea>
                </div>
            </div>
            <div class="col-md-1">
                <input type="submit" id="submit1" class="btn btn-primary" value="save">
            </div>
        </div>
    </form>
</div>
@endsection
@section('footer')
<!-- Tempusdominus Bootstrap 4 Date Time-->
<script src="/template/admin//plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function() {
        // auto resize textarea
        $("textarea").each(function() {
            this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        }).on("input", function() {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
        
    });

    //button submit
    $('#submit1').attr('disabled', true);
    $('#formprofileDetail').change(function() {
        var check =  $(".not-empty1").filter(function () {
            return $.trim($(this).val()).length == 0
        }).length == 0;
        if(check){
            $('#submit1').attr('disabled', false);
        }else{
            $('#submit1').attr('disabled', true);
        }
    });

    $('#submit2').attr('disabled', true);
    $('#formInterview').change(function() {
        var check2 =  $(".not-empty").filter(function () {
            return $.trim($(this).val()).length == 0
        }).length == 0;
        if(check2){
            $('#submit2').attr('disabled', false);
        }else{
            $('#submit2').attr('disabled', true);
        }
    });

    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    //Date and time picker
    $('#reservationdatetime').datetimepicker({
        icons: {
            time: 'far fa-clock'
        }
    });
</script>
@endsection