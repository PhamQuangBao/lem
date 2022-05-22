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
    <form action="/profile/update" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Name<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="@if(old('name')){{old('name')}}@else{{$auth->name}}@endif" name="name">
                    @error('name')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>User Roles<span class="text-danger"> *</span></label>
                    <div class="info">
                        @can('user.role', 1)
                        <span class="badge badge-danger">
                            Super Admin
                        </span>
                        @endcan
                        @can('user.role', 2)
                        <span class="badge badge-warning">
                            Admin
                        </span>
                        @endcan
                        @can('user.role', 3)
                        <span class="badge badge-success">
                            Interviewer
                        </span>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email address<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$auth->email}}" disabled>
                    @error('email')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- /.form-group -->
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>New password <span class="text-danger"></span></label>&nbsp; <input type="checkbox" name="editPassword" onclick="newPassword()">&nbsp; (click change)
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" disabled>
                    <!-- An element to toggle between password visibility -->
                    <input type="checkbox" onclick="showPassword()"> Show Password
                    @error('password')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div> <!-- /.row -->
        <p></p>
        <button type="submit" id="submit" class="btn btn-primary">Save</button>
        <a type="button" href="/" class="btn btn-default">Cancel</a>
        <!-- /.row -->
    </form>
</div><!-- /.row -->
@endsection

@section('footer')
<script>
    function newPassword() {
        if(document.getElementById('password').disabled === true){
            document.getElementById('password').disabled = false;
        } else {
            document.getElementById('password').disabled = true;
        }
    }

    function showPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    
</script>
@endsection