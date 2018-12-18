@extends("layouts.dashboard")

@section('title','Reservasi')

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
                    <h3 class="card-title">Reservasi Baru</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{route('reservasi.store')}}">
                    {{csrf_field()}}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ruangan">Ruangan</label>
                            <select class="form-control select2{{ $errors->has('ruangan') ? ' is-invalid' : '' }}" name="ruangan" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option disabled selected>-</option>
                                @foreach($ruangan as $item)
                                    <option value="{{$item->id}}" @if($id_ruangan == $item->id) selected @endif>{{$item->nama}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('ruangan'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ruangan') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="namaAcara">Nama Acara</label>
                            <input type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" id="namaReservasi" name="nama" placeholder="Nama Acara" value="{{old('nama')}}">

                            @if ($errors->has('nama'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Acara</label>
                            <input type="text" class="form-control{{ $errors->has('deskripsi') ? ' is-invalid' : '' }}" id="deskripsi" name="deskripsi" placeholder="Opsional" value="{{old('deskripsi')}}">

                            @if ($errors->has('deskripsi'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('deskripsi') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Waktu Acara Mulai</label>

                            <div class="input-group">
                                <div class="input-group-prepend" data-toggle="datetimepicker" data-target="#time_start">
                                  <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </div>
                                <input type="text" autocomplete="off" class="form-control float-right datetimepicker datetimepicker-input" name="time_start" id="time_start" data-toggle="datetimepicker" data-target="#time_start">
                            </div>
                            @if ($errors->has('time_start'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time_start') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Waktu Acara Selesai</label>

                            <div class="input-group">
                                <div class="input-group-prepend" data-toggle="datetimepicker" data-target="#time_stop">
                                  <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </div>
                                <input type="text" autocomplete="off" class="form-control float-right datetimepicker datetimepicker-input" name="time_stop" id="time_stop" data-toggle="datetimepicker" data-target="#time_stop">
                            </div>
                            @if ($errors->has('time_start'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time_start') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{route('reservasi.index')}}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/tempusdominus-bootstrap-4.min.css')}}" />
@endsection

@section('js')
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/moment-with-locales.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/tempusdominus-bootstrap-4.min.js')}}"></script>

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
            $('.datetimepicker').datetimepicker({
                locale: 'id',
                format: 'YYYY-MM-DD HH:mm:ss',
                icons:{
                    date: 'far fa-calendar',
                    time: 'far fa-clock'
                }
            });
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