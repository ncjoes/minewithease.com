@extends('layouts.core.client')

@section('scripts')
    <script src="{{asset('js/photo-cropper.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('#modal-large').modal({backdrop: 'static', keyboard: false});
        });
    </script>
@endsection

@section('content')
    @if(session()->has('message'))
        @if (is_array($message = session()->get('message')))
            @php
                $class = 'alert-'.$message['status']
            @endphp
            <div class="alert {{$class}} text-center">
                {!! $message['message'] !!}
            </div>
        @endif
    @endif

    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Account</a></li>
            <li class="breadcrumb-item active">Profile Information</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8 pb-9">
            <h2 class="mb-7">Update Profile Information</h2>
            <div class="card card-info mb-7">
                <h4 class="card-header">Identity Verification</h4>
                <div class="card-body">
                    <fieldset>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="pic-container center-block mb-3 py-5">
                                    <div class="padd-xs">
                                        <img class="img-fluid mx-auto cropper-destination photo"
                                             src="{{$user->photo_url}}" alt="{{$user->photo_url}}" style="width:98%;"/>
                                    </div>
                                    <div class="after text-center" style="padding-top: 2em;">
                                        <label class="btn btn-primary" for="thumb_photo">
                                            <span class="fa fa-camera"></span>
                                            <strong>
                                                @if(empty($user->photo))
                                                    Upload Photograph
                                                @else
                                                    Change Photo
                                                @endif
                                            </strong>
                                        </label>
                                        <input class="d-none cropper-source" id="thumb_photo" type="file"
                                               data-handler="{{route('core.client.profile.photo')}}"
                                               data-width="320" data-height="420" data-attribute="photo"
                                               data-preview="{{$user->photo_url}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="pic-container text-center mb-3 pb-5 py-5">
                                    <div class="padd-xs">
                                        <img class="img-fluid mx-auto cropper-destination identification"
                                             src="{{$user->identification_url}}" alt="{{$user->identification_url}}" style="max-width: 98%;"/>
                                    </div>
                                    <div class="after text-center" style="padding-top: 2em;">
                                        <label class="btn btn-primary" for="identification_photo">
                                            <span class="fa fa-camera"></span>
                                            <strong>
                                                @if(empty($user->identification))
                                                    Upload Identification Document
                                                @else
                                                    Change Identification Document
                                                @endif
                                            </strong>
                                        </label>
                                        <input class="d-none cropper-source" id="identification_photo" type="file"
                                               data-handler="{{route('core.client.profile.photo')}}"
                                               data-width="1200" data-height="680" data-attribute="identification"
                                               data-preview="{{$user->identification_url}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="card card-info mb-7">
                <h4 class="card-header">Personal Information</h4>
                <div class="card-body">
                    <form class="form-horizontal ajax-form mb-5" method="post" action="{{route('core.client.profile-update')}}">
                        <fieldset class="mb-5">
                            <div class="row mb-3">
                                <label for="country_id" class="col-sm-3 col-form-label">Country</label>
                                <div class="col-sm-9">
                                    <select name="country_id" id="country_id" class="form-control" required>
                                        <option></option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" @if($country->id == $user->country_id) selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="first_name" class="col-sm-3 col-form-label">First name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="first_name" name="first_name" required value="{{$user->first_name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="last_name" class="col-sm-3 col-form-label">Last name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="last_name" name="last_name" required value="{{$user->last_name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="name@domain" value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" id="phone" name="phone" required value="{{$user->phone}}">
                                </div>
                            </div>
                        </fieldset>
                        <div class="mb-3 fa-pull-right">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card card-info">
                <h4 class="card-header">Wallet Addresses</h4>
                <div class="card-body">
                    <form class="form-horizontal ajax-form mb-5" method="post" action="{{route('core.client.wallets-update')}}">
                        <fieldset class="mb-5">

                            @foreach($user->accounts as $account)
                                <div class="row mb-3">
                                    <label for="account-{{$account->id}}" class="col-form-label col-sm-3">
                                        {{$account->currency->name}}
                                        <img src="{{$account->photo_url()}}" style="width: 20px; height: 20px;" alt="{{$account->currency->name}}" class="mr-1">
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="account-{{$account->id}}" name="account[{{$account->id}}]"
                                               value="{{$account->wallet_address}}" placeholder="Enter your {{$account->currency->name}} wallet address">
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>
                        <div class="mb-3 fa-pull-right">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @include('_components.image-previewer')

    @if($is_under_review)
        <div class="modal fade" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="myModalLabel">Account Under Review</h3>
                    </div>
                    <div class="modal-body">
                        <p class="lead">Your account information is under review in line with our KYC policy.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Continue Editing</button>
                        <button type="button" onclick="window.location.reload()" class="btn btn-primary">Reload Page</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
