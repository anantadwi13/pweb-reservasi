@extends("layouts.dashboard")

@section('title','Registrasi')


@section('action')
    <div class="float-sm-right">
        <a href="{{route('login')}}" class="btn btn-secondary">Back</a>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body mx-auto">
                        <a href="{{route('register.peminjam')}}" class="btn btn-success my-2 mr-4">Daftar Sebagai Peminjam</a>
                        <a href="{{route('register.penyedia')}}" class="btn btn-primary my-2">Daftar Sebagai Penyedia</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection