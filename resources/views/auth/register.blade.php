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
                                    <h3 class="text-body-highlight">Sign Up</h3>
                                    <p class="text-body-tertiary">Create your account today</p>
                                </div>

                                <!--
                                <button class="btn btn-phoenix-secondary w-100 mb-3">
                                    <span class="fab fa-google text-danger me-2 fs-9"></span>Sign up with google
                                </button>
                                <button class="btn btn-phoenix-secondary w-100">
                                    <span class="fab fa-facebook text-primary me-2 fs-9"></span>Sign up with facebook
                                </button>
                                <div class="position-relative mt-4">
                                    <hr class="bg-body-secondary" />
                                    <div class="divider-content-center bg-body-emphasis">or use email</div>
                                </div>
                                -->

                                <form role="form" class="ajax-form" method="POST" action="{{route('auth.register')}}">
                                    @csrf
                                    <div class="row mb-3 text-start">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="first_name">Firstname</label>
                                            <input class="form-control" id="first_name" name="first_name" type="text" required placeholder="Firstname"/>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="last_name">Lastname</label>
                                            <input class="form-control" id="last_name" name="last_name" type="text" required placeholder="Lastname"/>
                                        </div>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="country">Country</label>
                                        <select class="form-control" name="country">
                                            <option></option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input class="form-control" id="phone" name="phone" type="tel" placeholder="+1xxxxxxxxxx"/>
                                    </div>
                                    @if($referrals_allowed)
                                        <div class="mb-3 text-start">
                                            @if(is_object($referrer))
                                                <label class="form-label" for="referrer">Referrer: {{$referrer->name}}</label>
                                                <input id="referrer" type="text" class="form-control" name="referrer" placeholder="(optional)" value="{{$referrer->uuid}}" readonly>
                                            @else
                                                <label for="referrer" class="form-label">Referrer's Account ID</label>
                                                <input id="referrer" type="text" class="form-control" name="referrer" placeholder="(optional)">
                                            @endif
                                        </div>
                                    @else
                                        <input name="referrer" value="{{$referrerAID}}" type="hidden">
                                    @endif
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="email">Email address</label>
                                        <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com"/>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group" data-password="data-password">
                                                <input class="form-control" id="password" name="password" type="password" placeholder="Password"
                                                       data-password-input="data-password-input"/>
                                                <button class="input-group-text px-2 py-0 text-body-tertiary" type="button" data-password-toggle="data-password-toggle">
                                                    <span class="uil uil-eye show"></span>
                                                    <span class="uil uil-eye-slash hide"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="confirmPassword">Confirm Password</label>
                                            <div class="input-group" data-password="data-password">
                                                <input class="form-control" id="confirmPassword" name="password_confirmation" type="password" placeholder="Confirm Password"
                                                       data-password-input="data-password-input"/>
                                                <button class="input-group-text px-2 py-0 text-body-tertiary" type="button" data-password-toggle="data-password-toggle">
                                                    <span class="uil uil-eye show"></span>
                                                    <span class="uil uil-eye-slash hide"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" id="termsService" name="terms_and_conditions" value="1" type="checkbox"/>
                                        <label class="form-label fs-9 text-transform-none" for="termsService">
                                            I accept the <a href="#!">terms </a>and <a href="#!">privacy policy</a>
                                        </label>
                                    </div>
                                    <button class="btn btn-primary w-100 mb-3" type="submit">Sign up</button>
                                    <div class="text-center"><a class="fs-9 fw-bold" href="{{route('auth.login')}}">Sign in to an existing account</a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
