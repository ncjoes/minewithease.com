@extends('layouts.error')

@section('title')
    <title>{{config('app.name')}} - Error 500!</title>
@endsection

@section('content')
                <h3>Error 500!</h3>
                <p><?=Lang::get('http-errors.500');?></p>
                <p>
                    <a class="btn btn-primary" href="{{route('cms.home')}}">
                        Back to Home
                    </a>
                </p>
@endsection
