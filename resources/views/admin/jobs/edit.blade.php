@extends('layouts.admin')
@section('content')
    <div class="container">
        @include('admin.alert')
        <form class="border p-3 bg-light" action="/jobs/{{$job->id}}/update" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="Status">Status<span class="text-danger"> *</span></label>
                        <select id="input_status" class="form-control add_key @error('job_status_id') is-invalid @enderror" name="job_status_id" value="{{ $job->job_status_id }}">
                            <option value="">----Select Status----</option>
                            @foreach ($listStatus as $item)
                                @if ($job->job_status_id == $item->id || old('job_status_id') == $item->id)
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
                <div class="form-group col">
                    <label for="date">Request date<span class="text-danger"> *</span></label>
                    <input type="date" id="input_request_date" class="form-control add_key @error('request_date') is-invalid @enderror" value="@if(old('request_date')){{old('request_date')}}@else{{ $job->request_date }}@endif"
                        name="request_date">
                    @error('request_date')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="form-group col">
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
                            <input type="text" placeholder="Enter Job ID..." class="form-control add_key @error('key') is-invalid @enderror" value="{{ old('key') }}" id="last_job_id">
                        </div>
                    </div>
                    @error('key')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group col">
                    <label for="Branches">Branches<span class="text-danger"> *</span></label>
                    <select id="input_branch" class="form-control add_key @error('branch_id') is-invalid @enderror" value="{{ $item->id }}" name="branch_id">
                        <option value="">----Select branches----</option>
                        @foreach ($listBranch as $item)
                            @if ($job->branch_id == $item->id || old('branch_id') == $item->id)
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
                <textarea class="form-control" name="description" value="{{ $job->description }}" id="description" rows="3">@if (old('description')){{ old('description') }}@else{{ $job->description }}@endif</textarea>
            </div>
            <div class="col text-center">
                <button id="submits" type="submit" class="btn btn-primary">Update</button>
                <a type="button" href="/jobs/list" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div><!-- /.container -->
@endsection
@section('footer')
<script>
    $(function() {
        // resize in load page for textarea
        $("textarea").each(function () {
            this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        }).on("input", function () {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
        // resize in input for textarea
        textarea = document.querySelector("#description");
        textarea.addEventListener('input', autoResize, false);
        function autoResize() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        }
        // Add JobID
        $('#submits').attr('disabled', true);
        // get JobID
        document.getElementById("final_Job_ID").value = {!! json_encode($job->key, JSON_HEX_TAG) !!};
        var job_ID = document.getElementById("final_Job_ID").value.split(/-/);
        document.getElementById("first_job_id").value = job_ID[0] + "-" + job_ID[1];
        var value_job_id = "";
        for (let index = 0; index < job_ID.length; index++) {
            if(index != 0 && index != 1){
                value_job_id = value_job_id + '-' + job_ID[index];
            }
        }
        document.getElementById("last_job_id").value = value_job_id.substr(1)
        $('.form-control').change(function() {
            if ($('#input_branch').val() != '' && $('#last_job_id').val() != '' && $('#input_request_date').val() != '' && $('#input_status').val() != "" && $('#last_job_id').val() != "") {
                $('#submits').attr('disabled', false);
            } else {
                $('#submits').attr('disabled', true);
            }
            //Add Job ID
            if ($('#input_branch').val() != '' && $('#input_request_date').val() != '') {
                document.getElementById("final_Job_ID").value = $('#first_job_id').val() + "-" + $('#last_job_id').val();
                $('#last_job_id').attr('disabled', false);
            } else {
                $('#last_job_id').attr('disabled', true);
            }
        });
        $('.add_key').change(function() {
            if ($('#input_branch').val() != '' && $('#last_job_id').val() != '' && $('#input_request_date').val() != '' && $('#input_status').val() != "") {
                document.getElementById("final_Job_ID").value = document.getElementById("first_job_id").value + "-" + document.getElementById("last_job_id").value;
            }else{
                $('#last_job_id').attr('disabled', true);
            }
        });
        $('#last_job_id').change(function() {
            document.getElementById("final_Job_ID").value = document.getElementById("first_job_id").value + "-" + document.getElementById("last_job_id").value
        });
    });
</script>
@endsection
