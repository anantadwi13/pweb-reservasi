@extends("layouts.dashboard")

@section('content')
    <div class="offset-4 offset-lg-4 col-lg-4 col-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$errors->first()}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA)
                        <h3>{{\App\Ruangan::whereIdUser(Auth::user()->id)->count()}}</h3>
                    @else
                        <h3>{{\App\Ruangan::count()}}</h3>
                    @endif

                    <p>Ruangan</p>
                </div>
                <div class="icon">
                    <i class="far fa-building"></i>
                </div>
                <a href="{{route('ruangan.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @if(Auth::check())
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA)
                        @php
                            $totalReservasi = 0;
                            foreach (\App\Ruangan::whereIdUser(Auth::user()->id)->get() as $ruangan)
                                foreach ($ruangan->reservasi as $reservasi)
                                    $totalReservasi++;
                        @endphp
                        <h3>{{$totalReservasi}}</h3>
                    @elseif(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PEMINJAM)
                        <h3>{{\App\Reservasi::whereIdUser(Auth::user()->id)->count()}}</h3>
                    @else
                        <h3>{{\App\Reservasi::count()}}</h3>
                    @endif

                    <p>Reservasi</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list"></i>
                </div>
                <a href="{{route('reservasi.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endif
        @if(!(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA))
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{\App\Kategori::count()}}</h3>

                    <p>Kategori</p>
                </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
                </div>
                <a href="{{route('kategori.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endif
        @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_ADMIN)
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{\App\User::count()}}</h3>

                        <p>User</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="{{route('user.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{\App\Report::count()}}</h3>

                        <p>Laporan</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-flag"></i>
                    </div>
                    <a href="{{route('report.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
    @endif
    <!-- ./col -->
    </div>
@endsection