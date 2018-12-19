<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title',env('APP_NAME'))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('fonts/fontawesome/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    @yield('css')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @if(Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                    <i class="far fa-user-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item">
                        Halo, {{Auth::user()->nama}}
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="{{route('user.show',Auth::user()->username)}}" class="dropdown-item">
                        <i class="fa fa-user-circle mr-2"></i> Lihat Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{route('logout')}}">
                        {{csrf_field()}}
                        <a class="dropdown-item" data-slide="true" href="#" onclick="$(this).closest('form').submit()"><i class="fa fa-power-off mr-2" title="Log Out"></i> Keluar</a>
                    </form>
                </div>
            </li>
            @else
            <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}">Login</a>
            </li>
            @endif
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow-x: hidden;">
        <!-- Brand Logo -->
        <a href="{{route('dashboard.index')}}" class="brand-link">
            <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{route('dashboard.index')}}" class="nav-link">
                            <i class="nav-icon fa fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('ruangan.index')}}" class="nav-link">
                            <i class="nav-icon far fa-building"></i>
                            <p>Ruangan</p>
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item">
                        <a href="{{route('reservasi.index')}}" class="nav-link">
                            <i class="nav-icon fa fa-list"></i>
                            <p>Reservasi</p>
                        </a>
                    </li>
                    @endif
                    @if(!(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA))
                    <li class="nav-item">
                        <a href="{{route('kategori.index')}}" class="nav-link">
                            <i class="nav-icon fa fa-star"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_ADMIN)
                    <li class="nav-item">
                        <a href="{{route('user.index')}}" class="nav-link">
                            <i class="nav-icon fa fa-user"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('report.index')}}" class="nav-link">
                            <i class="nav-icon fa fa-flag"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@yield('title',env('APP_NAME'))</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @yield('action')
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        &nbsp;
        <div class="float-right d-none d-sm-inline-block">
            <strong><a href="{{route('dashboard.index')}}">ReservasiKu</a></strong>
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
@yield('js')
<!-- Bootstrap 4 -->
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
</body>
</html>
