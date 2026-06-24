@extends('layouts.core.auth')

@section('content')
    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-11 col-sm-10 col-xl-6">
            <div class="card border border-translucent auth-card mb-12">
                <div class="card-body">
                    <p class="text-center">
                        <img src="{{$headerLogoSetting->getImageUrl('value')}}" class="img-fluid" style="max-height: 5em;" alt="{{config('app.name')}}">
                    </p>
                    <h1 class="text-center" style="margin-top:3vh; max-height: 10em;"><i class="fa fa-lg fa-check"></i></h1>
                    <h3 class="text-center">{{ __('Verify Your Email Address') }}</h3>
                    <hr class="clean">
                    <div class="text-center">
                        @if (session('resent'))
                            <div class="alert alert-success lead" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @elseif(session()->has('resent') and session('resent')==false)
                            <div class="alert alert-danger lead" role="alert">
                                {{ __(session('message')) }}
                            </div>
                        @endif

                        <p class="lead">
                            {{ __('Before proceeding, please check your email for a verification link.') }}<br/>
                            {{ __('If you did not receive the email click the button below to resend.') }},
                        </p>
                        <hr/>
                        <form class="form-horizontal" method="POST" action="{{ route('verification.resend') }}">
                            @csrf

                            <div class="row mb-3">
                                <label class="form-label col-4  text-end" for="email">Email address</label>
                                <div class="col-xl-4 col-8">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" value="{{$user->email}}" required>
                                </div>
                            </div>
                            <p class="text-muted"><abbr title="you may change your address if it's incorrect."><i class="fa fa-info"></i></abbr>
                                You may change your address if it's incorrect.
                            </p>

                            <button type="submit" class="btn btn-theme">
                                {{ __('Click Here to Request Another') }}
                            </button>
                            <a class="logout-button btn btn-danger" href="#"><i class="fa fa-sign-out"></i> Sign out</a>
                        </form>
                    </div>
                </div>

                <div class="text-center m-5">
                    @include('_components.google-translate')
                </div>

            </div>
        </div>
    </div>
@endsection
