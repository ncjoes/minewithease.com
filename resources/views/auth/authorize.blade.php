@extends('layouts.core.auth')

@section('content')
    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-11 col-sm-10 col-xl-8">
            <div class="card border border-translucent auth-card mb-12">
                <div class="card-body pe-md-0">
                    <div class="row align-items-center gx-0 gy-7">
                        <div class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                            <div class="bg-holder" style="background-image:url({{asset('/assets/img/bg/38.png')}});"></div>
                            <!--/.bg-holder2-->
                            <div class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                                <h3 class="mb-3 text-body-emphasis fs-7">{{$org_name}} Authentication</h3>
                                <p class="text-body-tertiary">We have provided multiple hassle-free authentication options to access your account.</p>
                                <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                    <li class="d-flex align-items-center">
                                        <span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Fast</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Simple</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="uil uil-check-circle text-success me-2"></span><span class="text-body-tertiary fw-semibold">Responsive</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                                <img class="auth-title-box-img d-dark-none" src="{{asset('/assets/img/spot-illustrations/auth.png')}}" alt=""/>
                                <img class="auth-title-box-img d-light-none" src="{{asset('/assets/img/spot-illustrations/auth-dark.png')}}" alt=""/>
                            </div>
                        </div>
                        <div class="col mx-auto">

                            <div class="text-center my-5">
                                @include('_components.google-translate')
                            </div>


                            <div class="auth-form-box">
                                <div class="text-center mb-7">
                                    <a class="d-flex flex-center text-decoration-none mb-4" href="/">
                                        <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                            <img src="{{asset('favicon.ico')}}" alt="{{$org_name}}" width="58"/>  <small>{{ $org_name }}</small>
                                        </div>
                                    </a>
                                    <h3 class="text-body-highlight">Sign In</h3>
                                    <p class="text-body-tertiary">Get access to your account</p>
                                </div>


                                <div class="alert py-2 alert-warning">
                                    <p class="fs-9 mb-0">To protect your account from compromise, enter OTP code.</p>
                                </div>
                                <form role="form" class="ajax-form" onsubmit="return false;" method="POST" action="{{ route('auth.2fa.authorize') }}">
                                    @csrf

                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="auth_code">Authorization Code</label>
                                        <div class="form-icon-container">
                                            <input class="form-control form-icon-input" id="auth_code" type="text" name="authorization_code"/>
                                            <span class="fas fa-key text-body fs-9 form-icon"></span>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary w-100 mb-3">Authorize Login</button>
                                    <hr/>
                                    <p class="text-center">
                                        <a class="logout-button btn btn-danger" href="#"><i class="fa fa-sign-out"></i> Sign out</a>
                                    </p>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
