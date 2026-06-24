@extends('layouts.error')

@section('title')
    <title>{{config('app.name')}} - Error 400!</title>
@endsection

@section('content')
    <h3>Error 400!</h3>
    <p><?=Lang::get('http-errors.400');?></p>
    <p>
        <a class="btn btn-primary" href="{{route('cms.home')}}">
            Back to Home
        </a>
    </p>
@endsection
