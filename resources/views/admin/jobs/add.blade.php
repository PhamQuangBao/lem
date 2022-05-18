@extends('layouts.admin')
@section('content')
    <div class="container">
        @include('admin.alert')
        <form class="p-4 border bg-light" action="/jobs/store" method="POST">
            @csrf
            @if (Session::has('job_key'))
                <div class="job_key_created text-success">
                    Job ID has been created: {{ Session::get('job_key') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Status">Status<span class="text-danger"> *</span></label>
                        <select class="form-control @error('job_status_id') is-invalid @enderror" name="job_status_id" value="{{ old('job_status_id') }}" id="job_status_id">
                            <option value="">----Select Status----</option>
                            @foreach ($listStatus as $item)
                                @if (old('job_status_id') == $item->id)
                                    <option value="{{ $item->id }}" selected="selected">{{ $item->name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('job_status_id')
                            <span class="error invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="date">Request date<span class="text-danger"> *</span></label>
                    <input type="date" class="form-control  @error('request_date') is-invalid @enderror" value="{{ old('request_date') }}" name="request_date" id="request_date">
                    @error('request_date')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="hidden" name="key" id="final_Job_ID" value="">
                    <label for="Job Key">Job ID<span class="text-danger"> *</span></label>
                    <div class="row">
                        <div class="col-2">
                            <input class="form-control" type="text" value="" id="first_job_id" disabled>
                        </div>
                        <div class="col-1 text-center">
                            -
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="Enter Job ID..." class="form-control @error('key') is-invalid @enderror" value="{{ old('key') }}" id="last_job_id">
                        </div>
                    </div>
                    @error('key')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="Branch">Branch<span class="text-danger"> *</span></label>
                    <select class="form-control  @error('branch_id') is-invalid @enderror" value="{{ old('branch_id') }}" name="branch_id" id="branch_id">
                        <option value="">----Select Branch----</option>
                        @foreach ($listBranch as $item)
                            @if (old('branch_id') == $item->id)
                                <option value="{{ $item->id }}" selected="selected">{{ $item->name }}</option>
                            @else
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('branch_id')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><!-- /.row -->
            <div class="form-group">
                <label for="Description">Description</label>
                <textarea class="form-control" name="description" value="{{ old('description') }}" id="description" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="col text-center">
                <button id="submits" type="submit" class="btn btn-primary">Add</button>
                <a type="button" href="/jobs/list" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <script>
        $(function() {
            //button submit
            //set it default is disabled
            $('#submits').attr('disabled', true);
            $('#last_job_id').attr('disabled', true);
            //set max min can't input request date
            var year = new Date().getFullYear();
            day_max = (year + 1) + '-12-31'; 
            day_min = (year - 1) + '-01-01';
            document.getElementById("request_date").setAttribute("max", day_max);
            document.getElementById("request_date").setAttribute("min", day_min);
            
            $('.form-control').change(function() {
                //set button submit is eneble if all value is not null
                if ($('#branch_id').val() != '' && $('#request_date').val() != '' && $('#job_status_id').val() != '') {
                    $('#submits').attr('disabled', false);
                } else {
                    $('#submits').attr('disabled', true);
                }
                //Add Job ID
                if ($('#branch_id').val() != '' && $('#request_date').val() != '') {
                    document.getElementById("final_Job_ID").value = $('#first_job_id').val() + "-" + $('#last_job_id').val();
                    $('#last_job_id').attr('disabled', false);
                } else {
                    $('#last_job_id').attr('disabled', true);
                }
            });

            // set jobID with request_date
            $('#request_date').change(function() {
                if ($('#request_date').val() == '') {
                    document.getElementById("first_job_id").value = "";
                    document.getElementById("final_Job_ID").value = "";
                } else {
                    var year = new Date().getFullYear();
                    //set jobID number
                    var first_job_id = 0;
                    //get last jobID of request date
                    if ($('#request_date').val().slice(2, 4) == year % 2000) {
                        first_job_id = {!! json_encode($lastJobNow, JSON_HEX_TAG) !!};
                        var year1 = year % 2000;
                    }
                    if ($('#request_date').val().slice(2, 4) == year % 2000 - 1) {
                        first_job_id = {!! json_encode($lastJobBack, JSON_HEX_TAG) !!};
                        var year1 = year % 2000 - 1;
                    }
                    if ($('#request_date').val().slice(2, 4) == year % 2000 + 1) {
                        first_job_id = {!! json_encode($lastJobNext, JSON_HEX_TAG) !!};
                        var year1 = year % 2000 + 1;
                    }
                    first_job_id = first_job_id + 1;
                    if (first_job_id / 10 < 1 || first_job_id == 0) {
                        first_job_id = "0" + first_job_id;
                    }
                    document.getElementById("first_job_id").value = first_job_id + "-" + year1;
                    document.getElementById("final_Job_ID").value = $('#first_job_id').val() + "-" + $('#last_job_id').val();
                }
            });

            $('#branch_id').change(function() {
                if ($('#branch_id').val() == '') {
                    document.getElementById("last_job_id").value = "";
                    document.getElementById("final_Job_ID").value = "";
                } else {
                    //set jobID charater
                    $('#last_job_id').attr('disabled', false);
                    document.getElementById("last_job_id").value = "";
                    var skill = document.getElementById("branch_id");
                    var value_skill = skill.options[skill.selectedIndex].text;
                    document.getElementById("last_job_id").value = value_skill;
                    document.getElementById("final_Job_ID").value = $('#first_job_id').val() + "-" + $('#last_job_id').val();
                }
            });

            $('#last_job_id').change(function() {
                document.getElementById("final_Job_ID").value = document.getElementById("first_job_id").value + "-" + document.getElementById("last_job_id").value
            });

            //settime out for Job key has created
            window.setTimeout(function() {
                $(".job_key_created").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 10000);
            //auto resize for textarea
            textarea = document.querySelector("#description");
            textarea.addEventListener('input', autoResize, false);
        
            function autoResize() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            }
        });
    </script>
@endsection
