@extends("layouts.dashboard")

@php
    /** @var \App\User $user */
@endphp

@section('title','Ganti Password')

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
                    <h3 class="card-title">Ganti Password</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="{{route('user.gantipass.act')}}">
                    {{csrf_field()}}
                    <div class="card-body">
                        @csrf
                        <div class="form-group row">
                            <label for="oldpassword" class="col-md-4 col-form-label text-md-right">Password Lama</label>

                            <div class="col-md-6">
                                <input id="oldpassword" type="password" class="form-control{{ $errors->has('oldpassword') ? ' is-invalid' : '' }}" name="oldpassword" placeholder="">

                                @if ($errors->has('oldpassword'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('oldpassword') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password Baru</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Konfirmasi Password Baru</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Ganti</button>
                                <a href="{{route('dashboard.index')}}" class="btn btn-secondary">Cancel</a>
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