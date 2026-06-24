@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">Account Settings</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8 pb-9">
            <h2 class="mb-7">Update Account Settings</h2>

            <div class="card card-info mb-7">
                <div class="card-body">
                    <form class="form-horizontal ajax-form mb-5 pb-5" method="post" action="{{route('core.client.account-settings')}}">
                        @csrf
                        <fieldset>
                            @foreach($settings as $setting)
                                @include('_components.setting-field')
                            @endforeach

                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
