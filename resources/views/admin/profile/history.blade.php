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
                            <label for="status">Status</label>
                            @foreach ($profileStatuses as $item)
                                @if ($item->id == $profile->profile_status_id)
                                <div>{{ $item->name }}</div>
                                @endif
                            @endforeach
                            
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
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <h1>{{ $profile->name }}</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        @foreach ($jobs as $item)
                            @if ($item->id == $profile->job_id)
                            <p>{{ $item->key }}</p>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-md-3">
                        <p>{{ $profile->phone_number }}</p>
                        <p>{{ $profile->mail }}</p>
                        <p>{{ $profile->address }}</p>
                        @foreach ($universities as $item)
                            @if($item->id == $profile->university_id)
                            <p>{{ $item->name }}</p>
                            @endif
                        @endforeach
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
                    <p>{{ $profile->note }}</p>
                </div>
            </div>
        </div>
    </form>
    
</div>
@endsection
@section('footer')
<!-- Tempusdominus Bootstrap 4 Date Time-->
<script src="/template/admin//plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    
</script>
@endsection