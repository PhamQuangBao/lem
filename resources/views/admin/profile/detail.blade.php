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
                            <div class="col-md-4">
                                <p for="onboard date">Onboard date:</p>
                                <p for="onboard date">Offer salary:</p>
                            </div>
                            <div class="col-md-5">
                                <h4 class="text-success">{{ $profile->onboard_date }}</h4>
                                <h4 class="text-danger">{{ isset($profile->salary_offer) ? $profile->salary_offer." Million VND" : ""}}</h4>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-1">
                                @if ($profileHistory)
                                    <a href="/profile/{{$historyId}}/history" target="_blank">History</a>
                                @endif
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
                                    <a href={{ strpos(' .xlsx, .xls, .doc, .docx, .txt', substr(($profile->files)[$i]->file, strpos(($profile->files)[$i]->file, '.'))) ? "https://view.officeapps.live.com/op/embed.aspx?src=" . request()->getHost() . "/uploads/profile/" . ($profile->files)[$i]->file : "/uploads/profile/" . ($profile->files)[$i]->file}} target="_blank">{{ ($profile->files)[$i]->file }}</a>
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
                        @if($profile->university_id)
                        <p>{{ $profile->profileUniversities->name }}</p>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <p>{{ $profile->submit_date }}</p>
                        @if(isset($profile->link))
                        <a href="{{ $profile->link }}"  target="_blank">link</a>
                        @endif
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
    {{-- Form Interview result --}}
    <form action="/profile/storeInterviewResult" id="formInterview" method="post" class="needs-validation" novalidate>
        @csrf
        <div class="row py-5">
            <div class="col-md-11 pt-5 border-top border-dark ">
                <input type="hidden" name="profile_id" value="{{ $profile->id }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 pr-5">
                <fieldset class="border bg-light">
                    <legend class='text-danger'>Interview Result</legend>
                    <div class="row px-5">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Primary Skill">Primary Skill<span class="text-danger">
                                                        *</span></label>
                                                <select class="form-control not-empty" name="primary_skill_id" id="primary_skill_id" required>
                                                    <option value="">----Select Primary Skill----</option>
                                                    @if (isset($interview->primary_skill_id))
                                                    @foreach ($branches as $item)
                                                    @if ($interview->primary_skill_id == $item->id)
                                                    <option value="{{ $item->id }}" selected="selected">
                                                        {{ $item->name }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <div class="valid-feedback">Valid.</div>
                                                <div class="invalid-feedback">Please fill out this field.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Level">Level<span class="text-danger"> *</span></label>
                                                <select class="form-control not-empty @error('profile_status_id') is-invalid @enderror" name="prim_level_id" id="prim_level_id" required>
                                                    <option value="">--Level--</option>
                                                    @if (isset($interview->prim_level_id))
                                                    @foreach ($levels as $item)
                                                    @if ($interview->prim_level_id == $item->id)
                                                    <option value="{{ $item->id }}" selected="selected">
                                                        {{ $item->name }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    @foreach ($levels as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <div class="valid-feedback">Valid.</div>
                                                <div class="invalid-feedback">Please fill out this field.</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="Secondary Skill">Secondary Skill</label>
                                                    <select class="form-control" name="secondary_skill_id" id="">
                                                        <option value="">----Select Secondary Skill----</option>
                                                        @if (isset($interview->secondary_skill_id))
                                                        @foreach ($branches as $item)
                                                        @if ($interview->secondary_skill_id == $item->id)
                                                        <option value="{{ $item->id }}" selected="selected">
                                                            {{ $item->name }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        @foreach ($branches as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Level">Level</label>
                                                <select class="form-control @error('profile_status_id') is-invalid @enderror" name="second_level_id" id="second_level_id">
                                                    <option value="">--Level--</option>
                                                    @if (isset($interview->second_level_id))
                                                    @foreach ($levels as $item)
                                                    @if ($interview->second_level_id == $item->id)
                                                    <option value="{{ $item->id }}" selected="selected">
                                                        {{ $item->name }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    @foreach ($levels as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Overall level id">Overall band <span class="text-danger"> *</span></label>
                                                <select class="form-control not-empty @error('overall_band_id') is-invalid @enderror" name="overall_band_id" id="overall_band_id" required>
                                                    <option value="">--Level--</option>
                                                    @if (isset($interview->overall_band_id))
                                                    @foreach ($levels as $item)
                                                    @if ($interview->overall_band_id == $item->id)
                                                    <option value="{{ $item->id }}" selected="selected">{{ $item->name }}</option>
                                                    @else
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    @foreach ($levels as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <div class="valid-feedback">Valid.</div>
                                                <div class="invalid-feedback">Please fill out this field.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="English level">English level</label>
                                                <input type="text" class="form-control" name="english_level" id="" value="{{ isset($interview->english_level) ? $interview->english_level : '' }}" aria-describedby="helpId" placeholder="English level...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Expected Salary">Expected Salary @can('user.role', 1)<span class="text-danger"> *</span> @endcan</label>
                                                <div class="input-group mb-3">
                                                    @can('user.role', 1)
                                                    <input type="number" min="0" step="0.1" class="form-control not-empty" value="{{ isset($interview->expected_salary) ? $interview->expected_salary: '' }}" name="expected_salary" id="expected_salary" placeholder="Expected Salary..." aria-describedby="basic-addon2" required>
                                                    @else
                                                    <input type="number" min="0" step="0.1" class="form-control" value="" name="expected_salary" id="expected_salary" placeholder="" aria-describedby="basic-addon2" disabled>
                                                    @endcan
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">Million VND</span>
                                                    </div>
                                                </div>
                                                <div class="valid-feedback">Valid.</div>
                                                <div class="invalid-feedback">Please fill out this field.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Current salary">Current salary</label>
                                                <div class="input-group mb-3">
                                                    @can('user.role', 1)
                                                    <input type="number" min="0" step="0.1" class="form-control" value="{{ isset($interview->current_salary) ? $interview->current_salary : '' }}" name="current_salary" placeholder="Current salary..." aria-describedby="basic-addon2">
                                                    @else
                                                    <input type="number" min="0" step="0.1" class="form-control" value="" name="current_salary" placeholder="" aria-describedby="basic-addon2" disabled>
                                                    @endcan
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">Million VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Start Date">Onboard date<span class="text-danger">
                                                        *</span></label>
                                                <input type="date" class="form-control not-empty" name="onboard_date" value="{{ isset($interview->onboard_date) ? $interview->onboard_date : '' }}" id="start_date" aria-describedby="helpId" required>
                                                <div class="valid-feedback">Valid.</div>
                                                <div class="invalid-feedback">Please fill out this field.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="Offer salary">Offer salary</label>
                                                <div class="input-group mb-3">
                                                    @can('user.role', 1)
                                                    <input type="number" min="0" step="0.1" class="form-control" value="{{ isset($profile->salary_offer) ? $profile->salary_offer : '' }}" name="salary_offer" id="salary_offer" placeholder="Offer salary..." aria-describedby="basic-addon2">
                                                    @else
                                                    <input type="number" min="0" step="0.1" class="form-control" value="" name="salary_offer" id="salary_offer" placeholder="" aria-describedby="basic-addon2" disabled>
                                                    @endcan
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">Million VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="Technical still note">Technical skills note<span class="text-danger">
                                        *</span></label>
                                <textarea class="form-control not-empty" name="technical_skills_note" id="technical_skills_note" rows="3" required>{{ isset($interview->technical_skills_note) ? $interview->technical_skills_note : '' }}</textarea>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="Soft skills">Soft skills</label>
                                <textarea class="form-control" name="soft_skills" id="" rows="3">{{ isset($interview->soft_skills) ? $interview->soft_skills : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="Overall assesment">Overall assesment<span class="text-danger">
                                        *</span></label>
                                <textarea class="form-control not-empty" name="overall_assessment" id="overall_assessment" rows="3">{{ isset($interview->overall_assessment) ? $interview->overall_assessment : '' }}</textarea>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pb-5">
                        <input type="submit" class="btn btn-primary" id="submit2" value="save">
                        <a href="/cv/list" class="btn btn-default">cancel</a>
                    </div>
                </fieldset>
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