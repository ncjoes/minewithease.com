@extends('layouts.error')

@section('content')
    <h3 style="color: darkorange;"><i class="fa fa-warning"></i> Account De-activated</h3>
    <p>
        You have been restricted from accessing certain features of your account.<br/>
        kindly contact your account officer.
    </p>
    <div class="mt-5">
        <a href="{{route('cms.home')}}" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
        <a href="{{route('core.client.dashboard')}}" class="btn btn-success"><i class="fa fa-dashboard"></i> My Dashboard</a>
        <a href="#" class="btn btn-danger logout-button"><i class="fa fa-power-off"></i> Logout</a>
    </div>
@endsection
