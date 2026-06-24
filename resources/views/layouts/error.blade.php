@extends('layouts.private')

@section('styles')
    <style rel="stylesheet">
        body {
            background-color: #1c1b1f;
            color: #fff;
        }
    </style>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <img src="{{$headerLogoSetting->getImageUrl('value')}}" style="max-width:25%; margin-top: 25vh;" alt="{{config('app.name')}}">
                @yield('content')
            </div>
        </div>
    </div>

    <div class="text-center my-5">
        @include('_components.google-translate')
    </div>

@endsection