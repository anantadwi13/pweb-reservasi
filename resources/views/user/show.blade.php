@extends("layouts.dashboard")

@php
    /** @var \App\User $user */
@endphp

@section('title','User')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body alert-info">
                    <div class="form-group">
                        <label>Nama</label>
                        <div>{{$user->nama}}</div>
                    </div>
                    @if($user->tipe_akun == \App\User::TYPE_PEMINJAM)
                        <div class="form-group">
                            <label>No Identitas</label>
                            <div>{{$user->no_identitas}}</div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Email</label>
                        <div>{{$user->email}}</div>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <div>{{$user->username}}</div>
                    </div>
                    <div class="form-group">
                        <label>No HP/Telepon</label>
                        <div>{{$user->nohp}}</div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <div>{{$user->alamat_jalan?$user->alamat_jalan.", ".ucwords(strtolower($user->kecamatan->nama)).", ".ucwords(strtolower($user->kecamatan->kotakab->nama)).", ".ucwords(strtolower($user->kecamatan->provinsi->nama)) :""}}</div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div>{{$user->status==\App\User::STATUS_ACTIVE?"Aktif":($user->status==\App\User::STATUS_NONACTIVE?"Non Aktif":"Dihapus/Banned")}}</div>
                    </div>
                </div>
            </div>
        </div>
        @if($user->tipe_akun == \App\User::TYPE_PENYEDIA)
        <div class="col-lg-12 col-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h5>Ruangan</h5>
                </div>
                <div class="card-body table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->ruangan as $item)
                            <tr>
                                <td></td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->user->nama}}</td>
                                <td>{{$item->alamat_jalan?$item->alamat_jalan.", ".ucwords(strtolower($item->kecamatan->nama)).", ".ucwords(strtolower($item->kecamatan->kotakab->nama)).", ".ucwords(strtolower($item->kecamatan->provinsi->nama)) :""}}</td>
                                <td>{{$item->status?"Tersedia":"Perbaikan"}}</td>
                                <td>
                                    <a href="{{route('ruangan.show', $item)}}" class="btn btn-primary">Detail</a>
                                    @if(Auth::check() && Auth::user()->tipe_akun == \App\User::TYPE_PEMINJAM && $item->status == \App\Ruangan::STATUS_AVAILABLE)
                                        <a href="{{route('reservasi.create',['ruangan' => $item->id])}}" class="btn btn-success">Reservasi</a>
                                    @endif
                                    @if(Auth::check() && ((Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA && $user->id == Auth::user()->id) || Auth::user()->tipe_akun == \App\User::TYPE_ADMIN))
                                        <a href="{{route('ruangan.edit', $item)}}" class="btn btn-warning">Edit</a>
                                        <form method="post" class="delete" action="{{route('ruangan.destroy', $item)}}" style="display: inline-block;">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @elseif($user->tipe_akun == \App\User::TYPE_PEMINJAM)
        <div class="col-lg-12 col-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h5>Histori Peminjaman</h5>
                </div>
                <div class="card-body table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Ruangan</th>
                            <th>Nama Acara</th>
                            @if(Auth::check() && Auth::user()->tipe_akun >= \App\User::TYPE_PENYEDIA)
                                <th>Peminjam</th>
                            @elseif(Auth::check() && Auth::user()->tipe_akun >= \App\User::TYPE_PEMINJAM)
                                <th>Pemilik Ruangan</th>
                            @endif
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->reservasi as $item)
                            <tr>
                                <td></td>
                                <td>{{$item->ruangan->nama}}</td>
                                <td>{{$item->nama_acara}}</td>
                                @if(Auth::check() && Auth::user()->tipe_akun >= \App\User::TYPE_PENYEDIA)
                                    <td>{{$item->user->nama}}</td>
                                @elseif(Auth::check() && Auth::user()->tipe_akun >= \App\User::TYPE_PEMINJAM)
                                    <td>{{$item->ruangan->user->nama}}</td>
                                @endif
                                <td>{{$item->status==\App\Reservasi::STATUS_ACCEPTED?"Diterima":($item->status==\App\Reservasi::STATUS_REJECTED?"Ditolak":"Menunggu")}}</td>
                                <td>
                                    <a href="{{route('reservasi.show', $item)}}" class="btn btn-primary">Detail</a>
                                    @if(Auth::check() && ((Auth::user()->tipe_akun == \App\User::TYPE_PENYEDIA && $item->ruangan->user->id == Auth::user()->id) || Auth::user()->tipe_akun == \App\User::TYPE_ADMIN))
                                        <a href="{{route('reservasi.edit', $item)}}" class="btn btn-warning">Edit</a>
                                        @if($item->status == \App\Reservasi::STATUS_WAITING)
                                            <form method="post" action="{{route('reservasi.action', $item)}}" style="display: inline-block;">
                                                {{csrf_field()}}
                                                <input type="hidden" name="action" value="{{\App\Reservasi::STATUS_ACCEPTED}}">
                                                <button class="btn btn-success">Terima</button>
                                            </form>
                                            <form method="post" action="{{route('reservasi.action', $item)}}" style="display: inline-block;">
                                                {{csrf_field()}}
                                                <input type="hidden" name="action" value="{{\App\Reservasi::STATUS_REJECTED}}">
                                                <button class="btn btn-danger">Tolak</button>
                                            </form>
                                        @endif
                                    @elseif(Auth::check() && ((Auth::user()->tipe_akun == \App\User::TYPE_PEMINJAM && $user->id == Auth::user()->id) || Auth::user()->tipe_akun == \App\User::TYPE_ADMIN) && $item->status == \App\Reservasi::STATUS_WAITING)
                                        <form method="post" class="delete" action="{{route('reservasi.destroy', $item)}}" style="display: inline-block;">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger">Batalkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('action')
    <div class="float-sm-right">
        @if(Auth::check() && $user->id == Auth::user()->id)
            <a href="{{route('user.gantipass')}}" class="btn btn-primary mr-2">Ganti Password</a>
        @endif
        @if(Auth::check() && $user->id != Auth::user()->id)
            <a href="{{route('report.create',$user->username)}}" class="btn btn-danger mr-2">Report</a>
        @endif
        <a onclick="window.history.go(-1); return false;" href="#" class="btn btn-secondary">Back</a>
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
            });
            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });
        $('form.delete').submit(function (e) {
            e.preventDefault();

            if (confirm('Apakah Anda yakin ingin menghapus ruangan ini?'))
                this.submit();
        });
    </script>
@endsection