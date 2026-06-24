@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">Security Center</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8 pb-9">
            <h2 class="mb-7">Security Center</h2>

            <div class="card card-warning mb-7">
                <h4 class="card-header">Two Factor Authentication</h4>
                <div class="card-body">

                    @if(!$is2fa_enabled)
                        <form method="post" action="{{route('core.client.two-factor-auth')}}" class="ajax-form">
                            @csrf

                            <fieldset>
                                <p>
                                    Two-factor authentication is a method for extra protection of your account.<br/>
                                    When it is activated you need to enter not only your password, but also a special code received by your mobile app.
                                </p>

                                <div class="d-flex guttar-20px">
                                    <div><span>Current Status: &nbsp;</span></div>
                                    <div><span class="text-danger text-red">Disabled</span></div>
                                </div>

                                <div class="py-4">
                                    <h5>Steps to Setup 2FA</h5>

                                    <ul>
                                        <li>
                                            <h6>Step 1 : Install Google Authenticator</h6>
                                            <p>Download and Install Google Authenticator on your mobile device.</p>
                                            <ul class="ath-content-sublist">
                                                <li>
                                                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_GB" targe="_blank">
                                                        Google Authenticator for Android
                                                    </a>
                                                </li>
                                                <li><a href="https://apps.apple.com/gb/app/google-authenticator/id388497605" targe="_blank">Google Authenticator for IOS</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h6>Step 2 : Scan the QR code in Google Authenticator App</h6>
                                            <p>
                                                Your Secret Code is: <b>{{$challenge_secret}}</b>
                                                <input type="hidden" name="tfa_secret" value="{{$challenge_secret}}">
                                            </p>
                                            <p>
                                                <img alt="QR Code" src="{{$image_url}}" class="ath-content-qrimg">
                                            </p>
                                        </li>
                                        <li>
                                            <h6>Step 3 : Enter 2FA Code</h6>
                                            <p><label for="code">Enter the numeric code that is displayed in Google Authenticator</label></p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-lock"></i> 2FA Code</span>
                                            <input class="form-control" type="number" name="code" id="code" placeholder="2FA Code" required>
                                        </div>
                                    </div><!-- .input-item -->
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success">Enable 2FA</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    @else
                        <form class="form-horizontal ajax-form mb-5 pb-5" method="post" action="{{route('core.client.account-settings')}}">
                            @csrf
                            <fieldset>
                                <div class="row mb-3">
                                    <label for="enable_2fa" class="col-sm-3 col-form-label">2-Factor Authentication</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="enable_2fa" name="enable_2fa" required="">
                                            <option value="0">Disable</option>
                                            <option selected="" value="1">Enable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 fa-pull-right">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </fieldset>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card card-default">
                <h4 class="card-header">Change My Account Password</h4>
                <div class="card-body">

                    <form class="form-horizontal ajax-form" method="post" action="{{route('core.client.password-change')}}">
                        <fieldset>
                            <div class="row mb-3">
                                <label for="current_pswd" class="col-sm-3 col-form-label">Current Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="current_pswd" name="current_password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="new_pswd" class="col-sm-3 col-form-label">New Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="new_pswd" name="new_password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="pswd_conf" class="col-sm-3 col-form-label">Confirm Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="pswd_conf" name="new_password_confirmation" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary fa-pull-right">Change Password</button>
                            </div>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
