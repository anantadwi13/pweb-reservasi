@extends("layouts.dashboard")

@php
    /** @var \App\Kategori $kategori */
@endphp

@section('title','Kategori')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Ruangan dengan kategori {{$kategori->nama}}</h5>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Ruangan</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($kategori->ruangan as $item)
                            <tr>
                                <td></td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->user->nama}}</td>
                                <td>{{$item->alamat_jalan?$item->alamat_jalan.", ".ucwords(strtolower($item->kecamatan->nama)).", ".ucwords(strtolower($item->kecamatan->kotakab->nama)).", ".ucwords(strtolower($item->kecamatan->provinsi->nama)) :""}}</td>
                                <td>
                                    <a href="{{route('ruangan.show', $item)}}" class="btn btn-primary">Lihat</a>
                                    @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PEMINJAM && $item->status == \App\Ruangan::STATUS_AVAILABLE)
                                        <a href="{{route('reservasi.create',['ruangan' => $item->id])}}" class="btn btn-success">Reservasi</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('action')
    <div class="float-sm-right">
        <a href="{{route('kategori.index')}}" class="btn btn-secondary">Back</a>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.css')}}">
@endsection

@section('js')
    <script src="{{asset('js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            var t = $('#table').DataTable({
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0,
                } ],
                "order": [[ 1, 'asc' ]],
                "lengthChange": false,
            });
            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });
    </script>
@endsection