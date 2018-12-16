@extends("layouts.dashboard")

@php
    /** @var \App\Ruangan $ruangan */
@endphp

@section('title','Ruangan')

@section('content')
    <div class="row">
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
        <div class="offset-lg-2 offset-2 col-lg-8 col-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Ruangan</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{route('ruangan.update', $ruangan)}}">
                    {{csrf_field()}}
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="namaRuangan">Nama Ruangan</label>
                            <input type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" id="namaRuangan" name="nama" placeholder="Nama Ruangan" value="{{$ruangan->nama}}">

                            @if ($errors->has('nama'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kodeRuangan">Kode Ruangan</label>
                            <input type="text" class="form-control{{ $errors->has('kode') ? ' is-invalid' : '' }}" id="kodeRuangan" name="kode" placeholder="Opsional" value="{{$ruangan->kode}}">

                            @if ($errors->has('kode'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kode') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="statusRuangan">Status Ruangan</label>
                            <select class="form-control select2{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="1" @if($ruangan->status == \App\Ruangan::STATUS_AVAILABLE) selected @endif>Tersedia</option>
                                <option value="0" @if($ruangan->status == \App\Ruangan::STATUS_MAINTENANCE) selected @endif>Perbaikan</option>
                            </select>

                            @if ($errors->has('status'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kodeRuangan">Kategori/Jenis Ruangan</label>
                            <select class="form-control select2{{ $errors->has('kategori') ? ' is-invalid' : '' }}" name="kategori" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option disabled selected>-</option>
                                @foreach($kategori as $item)
                                    <option value="{{$item->id}}" @if($ruangan->id_kategori==$item->id) selected @endif>{{$item->nama}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('kategori'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kategori') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kodeRuangan">Alamat</label>
                            <input type="text" class="form-control{{ $errors->has('alamat') ? ' is-invalid' : '' }}" id="alamatRuangan" name="alamat" placeholder="Opsional" value="{{$ruangan->alamat_jalan}}">

                            @if ($errors->has('alamat'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('alamat') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="provinsi">Provinsi (Opsional)</label>
                            <select id="provinsi" class="form-control select2{{ $errors->has('provinsi') ? ' is-invalid' : '' }}" name="provinsi">
                                <option value="" selected>-</option>
                                @foreach($provinsi as $item)
                                    <option value="{{$item->id}}" @if($idprovinsi==$item->id) selected @endif>{{$item->nama}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('provinsi'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('provinsi') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota/Kabupaten (Opsional)</label>
                            <select id="kota" class="form-control select2{{ $errors->has('kota') ? ' is-invalid' : '' }}" name="kota">
                                <option value="" disabled selected>-</option>
                                @foreach($kota as $item)
                                    <option value="{{$item->id}}" @if($idkota==$item->id) selected @endif>{{$item->nama}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('kota'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kota') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan (Opsional)</label>
                            <select id="kecamatan" class="form-control select2{{ $errors->has('kecamatan') ? ' is-invalid' : '' }}" name="kecamatan">
                                <option value="" disabled selected>-</option>
                                @foreach($kecamatan as $item)
                                    <option value="{{$item->id}}" @if($ruangan->kecamatan->id==$item->id) selected @endif>{{$item->nama}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('kecamatan'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('kecamatan') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('ruangan.index')}}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
@endsection

@section('js')
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        var p = $('#provinsi');
        var k = $('#kota');
        var c = $('#kecamatan');

        p.change(function () {
            emptyOption(k);
            emptyOption(c);
            update(true,p,k,c);
        });
        k.change(function () {
            emptyOption(c);
            update(false,p,k,c);
        });
        function emptyOption(select) {
            select.empty();
            select.append($("<option></option>").attr('value', '').text('-'));
        }
        function update(isP,p,k,c) {
            $.ajax({
                method: 'POST',
                async: true,
                url: '{{route('api.getDistrict')}}',
                data: {a: p.val(), b:k.val()},
            }).done(function (data) {
                if (data['success']) {
                    if(data['kota'].length>0 && isP) {
                        emptyOption(k);
                        $.each(data['kota'], function (key, value) {
                            console.log(value);
                            k.append($("<option></option>").attr('value', value['id']).text(value['nama']));
                        });
                    }
                    if(data['kec'].length>0) {
                        emptyOption(c);
                        $.each(data['kec'], function (key, value) {
                            console.log(value);
                            c.append($("<option></option>").attr('value', value['id']).text(value['nama']));
                        });
                    }
                }
            });
        }
        $(document).ready(function() {
            $('.select2').select2();
        });
        $('form').submit(function()
        {
            $("button[type='submit']", this)
                .text("Please Wait...")
                .attr('disabled', 'disabled');

            return true;
        });
    </script>
@endsection