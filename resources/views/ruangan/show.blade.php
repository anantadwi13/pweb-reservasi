@extends("layouts.dashboard")

@php
    /** @var \App\Ruangan $ruangan */
@endphp

@section('title','Ruangan')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body alert-info">
                    <div class="form-group">
                        <label>Nama Ruangan</label>
                        <div>{{$ruangan->nama}}</div>
                    </div>
                    @if($ruangan->kode)
                        <div class="form-group">
                            <label>Kode Ruangan</label>
                            <div>{{$ruangan->kode}}</div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Kategori</label>
                        <div>{{$ruangan->kategori->nama}}</div>
                    </div>
                    <div class="form-group">
                        <label>Pengelola</label>
                        <div><a href="{{route('user.show',$ruangan->user->username)}}">{{$ruangan->user->nama}}</a></div>
                    </div>
                    @if($ruangan->alamat_jalan)
                    <div class="form-group">
                        <label>Alamat</label>
                        <div>{{$ruangan->alamat_jalan?$ruangan->alamat_jalan.", ".ucwords(strtolower($ruangan->kecamatan->nama)).", ".ucwords(strtolower($ruangan->kecamatan->kotakab->nama)).", ".ucwords(strtolower($ruangan->kecamatan->provinsi->nama)) :""}}</div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Status</label>
                        <div>{{$ruangan->status==\App\Ruangan::STATUS_AVAILABLE?"Tersedia":"Maintenance"}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h5>Jadwal Reservasi {{$ruangan->nama}}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
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
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('css/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fullcalendar.print.min.css')}}" media='print' >
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
@endsection

@section('js')
    <script src="{{asset('js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/fullcalendar.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#calendar').fullCalendar({
                themeSystem: 'bootstrap4',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                timeFormat: 'H:mm',
                navLinks: true, // can click day/week names to navigate views
                eventLimit: true, // allow "more" link when too many events
                events: {
                    url: '{{route('api.getReservasi',['ruangan'=>$ruangan])}}',
                    error: function() {
                        //$('#script-warning').show();
                    }
                },
                loading: function(bool) {
                    //$('#loading').toggle(bool);
                }
            });

        });
    </script>
@endsection