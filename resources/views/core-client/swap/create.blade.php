@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Swap Coins</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-11">
            <div class="pb-9">
                <div class="d-flex justify-content-between">
                    <h2 class="mb-7">Swap Coins</h2>
                    <a class="btn btn-link btn-sm" href="{{route('core.client.swap.history')}}">History</a>
                </div>
                <!--@include('_components.balance_notice')-->
                <br/>
                <div class="card card-primary">
                    <div class="card-body">
                        <form class="form-horizontal ajax-form" method="post" id="money-form" action="{{route('core.client.swap.create')}}">

                            <div class="form-group row">
                                <label for="account1" class="control-label pt-2 col-12 col-lg-4">From</label>
                                <div class="pt-2 col-12 col-lg-8">
                                    <select name="source_account" id="account1" required class="form-control">
                                        <option value="">-- Select Coin --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}" class="">{{Illuminate\Support\Str::padRight($account->currency->name, 30, '-')}} : {{$account->balance()}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="account2" class="control-label pt-2 col-12 col-lg-4">To</label>
                                <div class="pt-2 col-12 col-lg-8">
                                    <select name="destination_account" id="account2" required class="form-control">
                                        <option value="">-- Select Coin --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}">{{$account->currency->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="amount" class="control-label pt-2 col-12 col-lg-4">Amount (USD)</label>
                                <div class="pt-2 col-12 col-lg-8">
                                    <input type="number" name="amount" id="amount" required pattern="[0-9]*" placeholder="amount in USD" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="pt-4 col-12 text-center">
                                    <button type="reset" class="btn btn-block btn-secondary"><i class="fa fa-recycle"></i> Reset</button>
                                    <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-exchange"></i> Swap Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
