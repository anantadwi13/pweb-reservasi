@extends('layouts.app')

@section('content')
    <div style="padding-top: 40px">
        <h1>{{$title}}</h1>
        <p>This is login page</p>
        <a href="/register" class="btn btn-default">Register</a>
    </div>   
@endsection
