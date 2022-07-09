@section('header')
<!-- DataTables -->
<link rel="stylesheet" href="/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<style>
  /* auto resize table */
  th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
</style>
@endsection
@extends('layouts.admin')
@section('content')
<div class="card py-3">
  <div class="card-header">
    <div style="float:left; margin-left:40%">
      <a class="btn" style="background-color:#F0E68C">
        <i></i>
      </a> Duplicate Email
      <a class="btn" style="background-color:#ff9999">
        <i></i>
      </a> Email exits
      <a class="btn" style="background-color:whitesmoke">
        <i></i>
      </a> Valid Profile
      <a class="btn" style="background-color:#6c757d">
        <i></i>
      </a> Email exits Unqualified
    </div>
  </div>
  <!-- /.card-header -->
    <div class="row justify-content-md-center">
      <div class="col-md-10">
        <form action="/jobs/save-profiles" method="post">
            @csrf
            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" style="width:100%;">
                <thead class="thead-dark text-center">
                    <tr>
                    <th><input type="checkbox" name="select-all" id="select-all" />  Select all</th>
                    <th>Mail</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <!-- <th>university</th>
                    <th>channel</th> -->
                    <th>Attach</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $listProfile as $item )
                        @if($item['status'] == 'duplicate')
                            <tr style="background-color:#F0E68C">
                        @elseif ($item['status'] == 'exits')
                            <tr style="background-color:#ff9999">
                        @elseif ($item['status'] == 'unqualified')
                            <tr style="background-color:#6c757d">
                        @else
                            <tr style="background-color:whitesmoke">
                        @endif
                                <td class="text-center"><input type="checkbox" name="selectSave[]" id="" value="{{ json_encode($item) }}" @if (($item['status'] == 'saved')) checked @endif ></td>
                                <td>{{ $item['mail']}}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['phone_number'] }}</td>
                                <!-- <td>{{ empty($item['university_name']) ? "Others" : $item['university_name'] }}</td>
                                <td>{{ empty($item['channel_name']) ? "Other" : $item['channel_name'] }}</td> -->
                                <td>{{$item['link']}}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
            <input type="hidden" name="job_id" value="{{ $listProfile[0]['job_id'] }}">
            <div class="text-center py-3">
                <button type="submit" id="submits" class="btn btn-primary">Save Profiles</button>   
            </div>
        </form>
      </div>
    </div>
    <!-- /.card-body -->
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

<script>
  $(function() {
    $("#example1").DataTable({
      "scrollX": true,
        scrollCollapse: true,
        "ordering": false,
        "paging":   false,
        columnDefs: [
            { width: '20%', targets: 0 }
        ],
        fixedColumns: true,
    });

  });
    $('#submits').attr('disabled', false);
    var countChecked = function() {
        var n = $( "input:checked" ).length;
        return parseInt(n);
    };
    countChecked();
 
    $("input[type=checkbox]").change(function() {
        console.log(countChecked());
        if (countChecked() > 0 ) {
            $('#submits').attr('disabled', false);
        } else {
            $('#submits').attr('disabled', true);
        }
    });
  // Listen for click on toggle checkbox
  $('#select-all').click(function(event) {   
      if(this.checked) {
          // Iterate each checkbox
          $(':checkbox').each(function() {
              this.checked = true;                        
          });
      } else {
          $(':checkbox').each(function() {
              this.checked = false;                       
          });
      }
  }); 
</script>
@endsection