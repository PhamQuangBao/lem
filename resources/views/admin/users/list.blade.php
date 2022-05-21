@extends('layouts.admin')
@section('header')
<!-- Data table -->
<link rel="stylesheet" href="/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@section('content')
<div class="container-fluid">
  @include('admin.alert')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead class="text-center">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="text-center">
            @foreach($users as $key => $user)
              <tr>
                <td>{{ $key }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge @if($user->roles->role_id == 1)badge-danger @elseif($user->roles->role_id == 2)badge-warning @else badge-success @endif">{{ $user->roles->userRoles->name }}</span></td>
                <td><span class="badge @if($user->isActive)badge-success @else badge-danger @endif">@if($user->isActive) Active @else Inactive @endif</span></td>
                <td class="align-middle">
                  <a href="/users/{{$user->id}}/edit" class="fas fa-edit text-warning px-2" aria-hidden="true"></a>
                  <a href="/users/{{$user->id}}/delete" onclick="return confirm('Are you sure delete user name: {{$user->name}}?')" class="fa fa-trash text-danger" aria-hidden="true"></i>
              </td>
              </tr>
            @endforeach
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</div>

@endsection

@section('footer')
<!-- DataTables & Plugins -->
<script src="/template/admin/plugins/jquery/jquery.min.js"></script>
<script src="/template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script src="/template/admin/dist/js/adminlte.min.js?v=3.2.0"></script>
<script>
  $(function () {
    $('#example2').DataTable({
    });
  });
</script>
@endsection