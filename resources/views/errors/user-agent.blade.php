@extends('layouts.error')

@section('title')
    <title>Browser Not Supported</title>
@endsection

@section('content')
    <h3>Unsupported Web Browser.</h3>
    <p>
        We suggest you switch to any of the following compatible browsers for optimal experience on your
        device.
    </p>

    <div class="row white-text">
        <div class="col-12 col-md-6 mx-auto">
            <ul class="small" style="list-style-type: square">
                @foreach($compatibleBrowsers as $browser)
                    <li>{{$browser}}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <p>Some features may not work as intended if you continue on this web browser.</p>
            <p>
                <a href="{{route('cms.QuirkMode',['redirect'=>$redirect])}}" class="btn btn-sm btn-danger">
                    Continue on this browser
                </a>
            </p>
        </div>
    </div>
@endsection
