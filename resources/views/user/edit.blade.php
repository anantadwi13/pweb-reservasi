@extends("layouts.dashboard")

@php
    /** @var \App\User $user */
@endphp

@section('title','User')

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
                    <h3 class="card-title">Edit User</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{route('user.update',$user)}}">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="card-body">
                        @csrf
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $user->username }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->nama }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="no_identitas" class="col-md-4 col-form-label text-md-right">No Identitas</label>
                            <div class="col-md-6">
                                <input id="no_identitas" type="text" class="form-control{{ $errors->has('no_identitas') ? ' is-invalid' : '' }}" name="no_identitas" value="{{ $user->no_identitas }}" placeholder="Kosongkan bila tipe akun Penyedia Ruangan">

                                @if ($errors->has('no_identitas'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('no_identitas') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="no_hp" class="col-md-4 col-form-label text-md-right">No HP / Telepon</label>
                            <div class="col-md-6">
                                <input id="no_hp" type="number" class="form-control{{ $errors->has('no_hp') ? ' is-invalid' : '' }}" name="no_hp" value="{{ $user->nohp }}" required>

                                @if ($errors->has('no_hp'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('no_hp') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat" class="col-md-4 col-form-label text-md-right">Alamat</label>
                            <div class="col-md-6">
                                <input id="alamat" type="text" class="form-control{{ $errors->has('alamat') ? ' is-invalid' : '' }}" name="alamat" value="{{ $user->alamat_jalan }}" required>

                                @if ($errors->has('alamat'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('alamat') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="provinsi" class="col-md-4 col-form-label text-md-right">Provinsi</label>
                            <div class="col-md-6">
                                <select id="provinsi" class="form-control select2{{ $errors->has('provinsi') ? ' is-invalid' : '' }}" name="provinsi">
                                    <option value="" disabled selected>-</option>
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
                        </div>
                        <div class="form-group row">
                            <label for="kota" class="col-md-4 col-form-label text-md-right">Kota/Kabupaten</label>
                            <div class="col-md-6">
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
                        </div>
                        <div class="form-group row">
                            <label for="kecamatan" class="col-md-4 col-form-label text-md-right">Kecamatan</label>
                            <div class="col-md-6">
                                <select id="kecamatan" class="form-control select2{{ $errors->has('kecamatan') ? ' is-invalid' : '' }}" name="kecamatan">
                                    <option value="" disabled selected>-</option>
                                    @foreach($kecamatan as $item)
                                        <option value="{{$item->id}}" @if($user->kecamatan->id==$item->id) selected @endif>{{$item->nama}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('kecamatan'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('kecamatan') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipe_akun" class="col-md-4 col-form-label text-md-right">Tipe Akun</label>
                            <div class="col-md-6">
                                <select id="tipe_akun" class="form-control select2{{ $errors->has('tipe_akun') ? ' is-invalid' : '' }}" name="tipe_akun">
                                    <option value="{{\App\User::TYPE_PEMINJAM}}" @if($user->tipe_akun==\App\User::TYPE_PEMINJAM) selected @endif>Peminjam</option>
                                    <option value="{{\App\User::TYPE_PENYEDIA}}" @if($user->tipe_akun==\App\User::TYPE_PENYEDIA) selected @endif>Penyedia Ruangan</option>
                                    <option value="{{\App\User::TYPE_ADMIN}}" @if($user->tipe_akun==\App\User::TYPE_ADMIN) selected @endif>Admin</option>
                                </select>

                                @if ($errors->has('tipe_akun'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tipe_akun') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select id="status" class="form-control select2{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                                    <option value="{{\App\User::STATUS_BANNED}}" @if($user->status==\App\User::STATUS_BANNED) selected @endif>Dihapus/Baneed</option>
                                    <option value="{{\App\User::STATUS_NONACTIVE}}" @if($user->status==\App\User::STATUS_NONACTIVE) selected @endif>Non Aktif</option>
                                    <option value="{{\App\User::STATUS_ACTIVE}}" @if($user->status==\App\User::STATUS_ACTIVE) selected @endif>Aktif</option>
                                </select>

                                @if ($errors->has('status'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Kosongkan jika tidak mengganti password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{route('user.index')}}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
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
        $(document).ready(function () {
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