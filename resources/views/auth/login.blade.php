@extends('layouts.core.auth')

@section('content')
    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-10 col-sm-6 col-xl-4">
            <div class="card border border-translucent auth-card mb-12">
                <div class="card-body pe-md-0">
                    <div class="row align-items-center gx-0 gy-7">

                        <div class="col mx-auto">
                            <div class="auth-form-box">
                                <div class="text-center mb-7">
                                    <a class="d-flex flex-center text-decoration-none mb-4" href="/">
                                        <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                            <img src="{{asset('images/logo.png')}}" alt="{{$org_name}}" height="96"/>
                                        </div>
                                    </a>
                                    <h3 class="text-body-highlight">Sign In</h3>
                                    <p class="text-body-tertiary">Get access to your account</p>
                                </div>
                                
                                <!--
                                <button class="btn btn-phoenix-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs-9"></span>Sign in with google</button>
                                <button class="btn btn-phoenix-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs-9"></span>Sign in with facebook</button>

                                <div class="position-relative">
                                    <hr class="bg-body-secondary mt-5 mb-4"/>
                                    <div class="divider-content-center bg-body-emphasis">or use email</div>
                                </div>
                                -->

                                <form role="form" class="ajax-form" onsubmit="return false;" method="POST" action="{{route('auth.login')}}">
                                    @csrf

                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="email">Email address</label>
                                        <div class="form-icon-container">
                                            <input class="form-control form-icon-input" id="email" type="email" name="email" placeholder="name@example.com" required/>
                                            <span class="fas fa-user text-body fs-9 form-icon"></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="form-icon-container input-group" data-password="data-password">
                                            <input class="form-control form-icon-input pe-6" id="password" type="password" name="password" placeholder="Password" required
                                                   data-password-input="data-password-input"/>
                                            <span class="fas fa-key text-body fs-9 form-icon"></span>
                                            <button class="input-group-text px-2 py-0 text-body-tertiary" data-password-toggle="data-password-toggle" type="button">
                                                <span class="uil uil-eye show"></span>
                                                <span class="uil uil-eye-slash hide"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row flex-between-center mb-7">
                                        <div class="col-auto">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" id="basic-checkbox" type="checkbox" checked="checked"/>
                                                <label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-auto"><a class="fs-9 fw-semibold" href="{{route('auth.password.request')}}">Forgot Password?</a></div>
                                    </div>
                                    <button class="btn btn-primary w-100 mb-3">Sign In</button>
                                    <div class="text-center"><a class="fs-9 fw-bold" href="{{route('auth.register')}}">Create an account</a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection