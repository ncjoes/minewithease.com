@extends('layouts.error')

@section('content')
                <h3>Coming Soon!</h3>
                <p>
                    This page is still under development.<br/>
                    Do call back soon.
                </p>
                <div class="mt-5">
                    <a href="{{route('cms.home')}}" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
                    <a href="{{route('auth.login')}}" class="btn btn-primary"><i class="fa fa-sign-in"></i> Login</a>
                    <hr/>
                    <button class="btn btn-default" onclick="window.history.back()">
                        <i class="fa fa-backward"></i> Take me back
                    </button>
                </div>
@endsection
