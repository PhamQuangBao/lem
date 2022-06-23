@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- Fix column Subject length -->
<style>
  #subjects {
    min-width: 250px;
    max-height: 35px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
</style>
@endsection
@extends('layouts.admin')
@section('content')
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

  <div class="row">
    <div class="col text-center">
      @if(LaravelGmail::check())
      <button id="submits" type="submit" class="btn btn-primary">Get Profile</button>
      @else
      <button id="submits" type="submit" class="btn btn-primary" disabled>Get Profile</button>
      @endif
      <a type="button" href="/profile/gmail" class="btn btn-default">Cancel</a>
    </div>
  </div><!-- /.row -->
</form>
<p></p>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">List Profile for email</h3>
    <div style="float:left; margin-left:40%">
      <a class="btn" style="background-color:antiquewhite">
        <i></i>
      </a> Duplicate Email
      <a class="btn" style="background-color:orange">
        <i></i>
      </a> Haven't Attachment
      <a class="btn" style="background-color:red">
        <i></i>
      </a> Email exits
    </div>
  </div>
  <!-- /.card-header -->
  <form action="./store" method="post" id="form2" onSubmit="submitAction();">
    @csrf
    <div class="row ">
      <div class="col-md-12">
        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" style="width:100%;">
          <thead class="thead-dark text-center">
            <tr>
              <th>#</th>
              <th>From Mail</th>
              <th>Form Name</th>
              <th>Phone</th>
              <th>Subject</th>
              <th>Jobs</th>
              <th>Attach</th>
              <th>Time Sends</th>
            </tr>
          </thead>
          <tbody>
            @foreach ( $mailIDs as $key => $mailID )
            @if($statuses[$key] == 2)
            <tr style="background-color:antiquewhite">
              @elseif ($statuses[$key] == 3)
            <tr style="background-color:orange">
              @elseif ($statuses[$key] == 0)
            <tr style="background-color:red">
              @else
            <tr>
              @endif

              <td class="text-center">{{ $key + 1 }} <input type="checkbox" class="array-select-save" name="selectSaves[]" value="{{ $mailIDs[$key] }}" @if ($statuses[$key]==1) checked @endif></td>
              <td>{{ $fromMails[$key] }}</td>
              <td>{{ $fromNames[$key] }}</td>
              <td class="text-center">{{ $phones[$key] }}</td>
              <td id="subjects">{{ $subjects[$key] }}</td>
              <td class="text-center">{{$jobKeys[$key]}}</td>
              <td class="text-center">{{ $numAttachments[$key] }}</td>
              <td class="text-center">{{ $timeSends[$key] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <!-- /.card-body -->


    <!-- Data request -->
    <input type="hidden" name="dateRange" value="@if(old('dateRange')){{old('dateRange')}}@else{{$dateRange}}@endif">
    <input type="hidden" value="{{ (serialize($statuses)) }}" name="statuses">
    <input type="hidden" value="{{ (serialize($jobIDs)) }}" name="jobIDs">
    <input type="hidden" value="{{ (serialize($mailIDs)) }}" name="mailIDs">
    <input type="hidden" value="{{ (serialize($fromMails)) }}" name="fromMails">
    <input type="hidden" value="{{ (serialize($fromNames)) }}" name="fromNames">
    <input type="hidden" value="{{ (serialize($subjects)) }}" name="subjects">
    <input type="hidden" value="{{ (serialize($phones)) }}" name="phones">
    <input type="hidden" value="{{ (serialize($timeSends)) }}" name="timeSends">
    <input type="hidden" value="{{ (serialize($numAttachments)) }}" name="numAttachments">

    <div class="col text-center">
      @if(LaravelGmail::check())
      <button id="submitSave" type="submit" class="btn btn-primary" onclick="return confirm('Do you want to save the data!')" disabled>Save</button>
      @else
      <button id="submits" type="submit" class="btn btn-primary" disabled>Save</button>
      @endif
      <a type="button" href="/profile/gmail/list-profile" class="btn btn-default">Cancel</a>
    </div>
  </form>
</div>
<!-- /.card -->

@endsection
@section('footer')
<!-- DataTables  & Plugins -->
<script src="/template/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/template/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/template/admin/plugins/jszip/jszip.min.js"></script>
<script src="/template/admin/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/template/admin/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/template/admin/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Page specific script -->
<!-- date-range-picker -->
<script src="/template/admin/plugins/daterangepicker/daterangepicker.js"></script>

<script>
  $(function() {
    // $("#example1").DataTable({
    //   "scrollX": true,
    // });
    //Date range picker
    $('#reservation').daterangepicker();

    // Check checkbox with selected 
    var countCheckbox = 0;
    
    $("#example1 input:checkbox:checked").each(function(){
      countCheckbox += 1;
    });
    
    if(countCheckbox !== 0){
      $('#submitSave').removeAttr('disabled');
    }

    $(".array-select-save").change(function() {
      if($(this).prop("checked")){
        countCheckbox += 1;
        $('#submitSave').removeAttr('disabled');
      }else{
        countCheckbox -= 1;
        if(countCheckbox === 0) {
          $('#submitSave').attr('disabled', 'disabled');
        }
      }
    });
  });
</script>
@endsection