<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.common.head')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> -->
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/" class="brand-link"><img src="" style="border-radius:50%"><span>&emsp;Logo </span></a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-2 pb-2 mb-2 d-flex">
                    <div class="image">
                        <img src="/uploads/users/avatar-default.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="/profile/edit" class="d-block">{{(Auth::user()->name)}}</a>
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
                    <a href="/logout" class="nav-link" style="width:30px;float:right;"><i class="fas fa-sign-out-alt"></i></a>

                </div>

                <!-- SidebarSearch Form <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
            </div> -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item {{ request()->is('/') ? 'menu-is-opening menu-open' : '' }}">
                            <a href="/" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('charts*') ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>
                                    Charts
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/charts/university" class="nav-link {{(request()->is('charts/university*')) ? 'active' : ''}}">
                                        &emsp;<i class="fas fa-chart-bar nav-icon"></i>
                                        <p>University</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/charts/total-profile" class="nav-link {{(request()->is('charts/total-profile*')) ? 'active' : ''}}">
                                        &emsp;<i class="fas fa-chart-bar nav-icon"></i>
                                        <p>Total Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/charts/jobs" class="nav-link {{(request()->is('charts/jobs*')) ? 'active' : ''}}">
                                        &emsp;<i class="fas fa-chart-bar nav-icon"></i>
                                        <p>Jobs</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('profile*') ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                Profile
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/profile/add" class="nav-link {{(request()->is('profile/add*')) ? 'active' : ''}}">
                                        &emsp;<i class="far fa-file nav-icon"></i>
                                        <p>Add New Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/profile/list" class="nav-link {{ (request()->is('profile/list*')) ? 'active' : '' }}">
                                        &emsp;<i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>List Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/profile/gmail" class="nav-link {{ (request()->is('profile/gmail*')) ? 'active' : '' }}">
                                        &emsp;<i class="fas fa-file-import nav-icon"></i>
                                        <p>Import Profile</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ request()->is('jobs*') ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>
                                    Jobs
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/jobs/add" class="nav-link {{ request()->is('jobs/add*') ? 'active' : '' }}">
                                        &emsp;<i class="nav-icon fas fa-address-book"></i>
                                        <p>Add new job</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/jobs/list" class="nav-link {{ request()->is('jobs/list*') ? 'active' : '' }}">
                                        &emsp;<i class="far fa-list-alt nav-icon"></i>
                                        <p>Jobs List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- User role Super admin or admin -->
                        @can('user.role', 1)
                        <li class="nav-item {{ request()->is('users*') ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>
                                    Users
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/users/add" class="nav-link {{ request()->is('users/add*') ? 'active' : '' }}">
                                        &emsp;<i class="nav-icon fas fa-user-plus"></i>
                                        <p>Add new User</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/users/list" class="nav-link {{ request()->is('users/list*') ? 'active' : '' }}">
                                        &emsp;<i class="nav-icon fas fa-users"></i>
                                        <p>Users List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <section class="content">
                        @if (isset($title) && $title != 'Home page')
                        <div class="content-header">
                            <div class="container-fluid">
                                <div class="row mb-2 d-flex">
                                    <div class="mr-4">
                                        <h1 class="m-0 text-uppercase">{{ $title }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @yield('content')
                    </section>
                </div>
            </section>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

</body>
@include('layouts.common.footer')

</html>