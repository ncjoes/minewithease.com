@extends('layouts.core.auth')

@section('content')
    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-10 col-sm-6 col-xl-4">
            <div class="card border border-translucent auth-card mb-12">
                <div class="card-body pe-md-0">
                    <div class="row align-items-center gx-0 gy-7">

                        <div class="col mx-auto">
                            <div class="auth-form-box">
                                <div class="text-center">
                                    <a class="d-flex flex-center text-decoration-none mb-4" href="/">
                                        <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                            <img src="{{asset('images/logo.png')}}" alt="{{$org_name}}" height="96"/>
                                        </div>
                                    </a>
                                    <h4 class="text-body-highlight">Forgot your password?</h4>
                                    <p class="text-body-tertiary mb-5">
                                        Enter your email below and we will <br class="d-md-none"/>send you <br class="d-none d-xxl-block"/>a reset link
                                    </p>
                                    <form role="form" class="ajax-form d-flex align-items-center mb-5" method="POST" action="{{ route('auth.password.email') }}">
                                        @csrf
                                        <input class="form-control flex-1" id="email" name="email" type="email" placeholder="Email"/>
                                        <button class="btn btn-primary ms-2" type="submit">Send Link <span class="fas fa-chevron-right ms-2"></span></button>
                                    </form>
                                    <a class="fs-9 fw-bold" href="{{ route('auth.login') }}">Return to login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
