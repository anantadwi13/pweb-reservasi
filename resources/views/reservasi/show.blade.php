@extends("layouts.dashboard")

@php
    /** @var \App\Reservasi $reservasi */
@endphp

@section('title','Reservasi')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body alert-info">
                    <div class="form-group">
                        <label>Nama Acara</label>
                        <div>{{$reservasi->nama_acara}}</div>
                    </div>
                    @if($reservasi->deskripsi_acara)
                    <div class="form-group">
                        <label>Deskripsi Acara</label>
                        <div>{{$reservasi->deskripsi_acara}}</div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Ruangan</label>
                        <div><a href="{{route('ruangan.show',$reservasi->ruangan)}}">{{$reservasi->ruangan->nama}} ({{$reservasi->ruangan->user->nama}})</a></div>
                    </div>
                    <div class="form-group">
                        <label>Peminjam</label>
                        <div>{{$reservasi->user->nama}}</div>
                    </div>
                    <div class="form-group">
                        <label>Waktu Mulai</label>
                        <div>{{$reservasi->time_start}}</div>
                    </div>
                    <div class="form-group">
                        <label>Waktu Selesai</label>
                        <div>{{$reservasi->time_end}}</div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div>{{$reservasi->status==\App\Reservasi::STATUS_ACCEPTED?"Diterima":($reservasi->status==\App\Reservasi::STATUS_REJECTED?"Ditolak":"Menunggu")}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('action')
    <div class="float-sm-right">
        <a onclick="window.history.go(-1); return false;" href="#" class="btn btn-secondary">Back</a>
    </div>
@endsection

@section('css')
@endsection

@section('js')
@endsection