@extends("layouts.dashboard")

@php
    /** @var \App\Reservasi $reservasi */
@endphp

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
                    <h3 class="card-title">Edit Reservasi</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{route('reservasi.update',$reservasi)}}">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ruangan">Ruangan</label>
                            <select class="form-control select2{{ $errors->has('ruangan') ? ' is-invalid' : '' }}" name="ruangan" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option disabled selected>-</option>
                                @foreach($ruangan as $item)
                                    <option value="{{$item->id}}" @if($reservasi->ruangan->id == $item->id) selected @endif>{{$item->nama}}</option>
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
                            <input type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" id="namaReservasi" name="nama" placeholder="Nama Acara" value="{{$reservasi->nama_acara}}">

                            @if ($errors->has('nama'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Acara</label>
                            <input type="text" class="form-control{{ $errors->has('deskripsi') ? ' is-invalid' : '' }}" id="deskripsi" name="deskripsi" placeholder="Opsional" value="{{$reservasi->deskripsi_acara}}">

                            @if ($errors->has('deskripsi'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('deskripsi') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control select2{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="{{\App\Reservasi::STATUS_WAITING}}" disabled @if($reservasi->status==\App\Reservasi::STATUS_WAITING) selected @endif>Menunggu</option>
                                <option value="{{\App\Reservasi::STATUS_ACCEPTED}}" @if($reservasi->status==\App\Reservasi::STATUS_ACCEPTED) selected @endif>Terima</option>
                                <option value="{{\App\Reservasi::STATUS_REJECTED}}" @if($reservasi->status==\App\Reservasi::STATUS_REJECTED) selected @endif>Tolak</option>
                            </select>

                            @if ($errors->has('ruangan'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ruangan') }}</strong>
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
        $(document).ready(function() {
            $('.select2').select2();
            $('#time_start').datetimepicker({
                date: '{{$reservasi->time_start}}',
                locale: 'id',
                format: 'YYYY-MM-DD HH:mm:ss',
                icons:{
                    date: 'far fa-calendar',
                    time: 'far fa-clock'
                }
            });
            $('#time_stop').datetimepicker({
                date: '{{$reservasi->time_end}}',
                locale: 'id',
                format: 'YYYY-MM-DD HH:mm:ss',
                icons:{
                    date: 'far fa-calendar',
                    time: 'far fa-clock'
                }
            });
        });
    </script>
    <script>
        $('form').submit(function()
        {
            $("button[type='submit']", this)
                .text("Please Wait...")
                .attr('disabled', 'disabled');

            return true;
        });
    </script>
@endsection