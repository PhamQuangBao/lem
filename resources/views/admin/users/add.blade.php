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
    <form action="/users/store" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>User Roles<span class="text-danger"> *</span></label>
                    <select class="form-control" style="width: 100%;" name="user_roles_id">
                        @foreach($userRoles as $userRole)
                        @if (old('user_roles_id') == $userRole->id)
                        <option value="{{$userRole->id}}" selected>{{$userRole->name}}</option>
                        @else
                        <option value="{{$userRole->id}}">{{$userRole->name}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" name="name" placeholder="Full name">
                    @error('name')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">  
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email address<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" placeholder="email@email.com">
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
                    <label>Password<span class="text-danger"> *</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{old('password')}}" placeholder="password" name="password">
                    @error('password')
                    <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div> <!-- /.row -->
        <p></p>
        <button type="submit" id="submit" class="btn btn-primary">Add</button>
        <a type="button" href="/users/list" class="btn btn-default">Cancel</a>
        <!-- /.row -->
    </form>
</div><!-- /.row -->
@endsection

@section('footer')
@endsection