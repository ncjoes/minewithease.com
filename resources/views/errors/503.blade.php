@extends('layouts.error')

@section('title')
    <title>{{config('app.name')}} - Be Right Back...</title>
@endsection

@section('content')
    <h3>Site Maintenance<br/>(Error 503)</h3>
    <div><?=Lang::get('http-errors.503');?></div>
@endsection
