@extends('layouts.error')

@section('content')
                <h3 style="color: darkred"><i class="fa fa-warning"></i> Account Disabled</h3>
                <p>
                    You have been restricted from accessing your account.<br/>
                    kindly contact Support.
                </p>
                <div class="mt-5">
                    <a href="{{route('cms.home')}}" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
                    <a href="#" class="btn btn-primary logout-button"><i class="fa fa-sign-in"></i> Logout</a>
                </div>
@endsection
