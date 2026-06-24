@extends('layouts.error')

@section('title')
    <title>{{config('app.name')}} - Error 403!</title>
@endsection

@section('content')
    <h3>Error 403!</h3>
    <p><?=Lang::get('http-errors.403');?></p>
    <p>
        <a class="btn btn-primary" href="{{route('cms.home')}}">
            Back to Home
        </a>
    </p>
@endsection
