@section('header')
<!-- daterange picker -->
<link rel="stylesheet" href="/template/admin/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/template/admin/plugins/bootstrap-slider/css/bootstrap-slider.min.css">
@endsection
@extends('layouts.admin')
@section('content')

<div class="container">
    @if (isset($error))
    <div class="alert alert-danger">
        {{$error}}
    </div>
    @endif

    @if (isset($success))
    <div class="alert alert-success">
        {{$success}}
    </div>
    @endif

    <form class="p-4 border bg-light" action="/profile/gmail/list-profile" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Status">Login with Gmail<span class="text-danger"> *</span></label>
                    <div class="input-group">
                        @if(LaravelGmail::check())
                        <h4>{{ LaravelGmail::user(); }}</h4>
                        &emsp;
                        <a href="{{ url('profile/gmail/oauth/gmail/logout') }}"><button type="button" class="btn btn-warning">logout</button></a>
                        @else
                        <a href="{{ url('profile/gmail/oauth/gmail') }}"><button type="button" class="btn btn-primary">login</button></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <!-- Date range -->
                    <div class="form-group">
                        <label>Date range:<span class="text-danger"> *</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" name="dateRange" class="form-control float-right" id="reservation" value="@if(isset($dateRange)){{{$dateRange}}}@endif">
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                </div>
            </div>
        </div>

        <div class="row margin">
            <div class="slider-red">
                <input type="text" value="-9,9" class="slider form-control" data-slider-min="-9" data-slider-max="9" data-slider-step="0.1" data-slider-value="[0,0]" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-value="-9,9" style="display: none;">
            </div>
        </div>

        <div class="row">
            <div class="col text-center">
                @if(LaravelGmail::check())
                <button id="submits" type="submit" class="btn btn-primary">Get Profile</button>
                @else
                <button id="submits" type="submit" class="btn btn-primary" disabled>Get Profile</button>
                @endif
                <a type="button" href="/profile/gmail" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </form>
</div>
<p></p>
@endsection
@section('footer')
<!-- date-range-picker -->
<script src="/template/admin/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/template/admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="/template/admin/plugins/bootstrap-slider/bootstrap-slider.min.js"></script>
<script>
    $(function() {
        /* BOOTSTRAP SLIDER */
        $('.slider').bootstrapSlider()
        //Date range picker
        $('#reservation').daterangepicker();
    });
</script>
@endsection