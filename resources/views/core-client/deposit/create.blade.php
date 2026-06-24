@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Receive Funds</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-11">
            <div class="pb-9">
                <div class="d-flex justify-content-between">
                    <h2 class="mb-7">Add Funds to My Account</h2>
                    <a class="btn btn-link btn-sm" href="{{route('core.client.deposit.manage')}}">History</a>
                </div>
                <!--@include('_components.balance_notice')-->
                <br/>
                <div class="card card-primary">
                    <div class="card-body">
                        <form class="form-horizontal ajax-form" method="post" id="money-form" action="{{route('core.client.deposit.create')}}">

                            <div class="form-group row">
                                <label for="account" class="control-label pt-2 col-12 col-lg-4">Select deposit currency (sub-account)</label>
                                <div class="pt-2 col-12 col-lg-8">
                                    <select name="account_id" id="account" required class="form-control">
                                        <option value="">-- Select Account --</option>
                                        @foreach($user->accounts as $account)
                                            <option value="{{$account->id}}">{{$account->currency->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="amount" class="control-label pt-2 col-12 col-lg-4">How much do you wish to deposit?</label>
                                <div class="pt-2 col-12 col-lg-8">
                                    <input type="number" min="{{$min_amount}}" max="{{$max_amount}}" name="amount" id="amount" required pattern="[0-9]*" placeholder="amount in USD" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="pt-4 col-12 text-center">
                                    <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Generate Payment Details</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
