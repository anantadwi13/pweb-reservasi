@extends("layouts.dashboard")

@php
    /** @var \App\Reservasi[] $dataReservasi */
@endphp

@section('title','Report')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body alert-info">
                    <div class="form-group">
                        <label>User pelapor</label>
                        <div>{{$report->pelapor->nama}}</div>
                    </div>
                    <div class="form-group">
                        <label>User yang dilapor</label>
                        <div>{{$report->dilapor->nama}}</div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <div>{{$report->subject}}</div>
                    </div>
                    <div class="form-group">
                        <label>Isi</label>
                        <div>{{$report->isi}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('action')
    <div class="float-sm-right">
        <a href="{{route('report.index')}}" class="btn btn-secondary">Back</a>
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
    </script>
@endsection