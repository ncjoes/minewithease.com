@extends('layouts.core.client')

@section('styles')
    <style>
        .data-card {
            text-align: center !important;
            border: 1px dotted #CCCCCC;
            padding: 1em 0.5em;
        }
    </style>
@endsection

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>

    <div class="pb-9">
        <div class="d-flex justify-content-between">
            <h2 class="mb-7">Hello {{$user->firstname}}</h2>
            <a class="btn btn-link btn-sm" href="{{route('core.client.portfolio.manage')}}">Staking History</a>
        </div>
        <div class="row">
            <div class="col-md-11 col-lg-10 mx-auto">

                <div class="card card-info mb-9">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="data-card">
                                    <strong>Total Balance:</strong><br/>
                                    <strong class="h1 money no-wrap" style="font-size: 4em;">{{ $user->getTotalBalanceStr() }}</strong>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-center mt-5">
                                    <button class="btn btn-phoenix-primary" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-credit-card"></i> Web3 Card</button>
                                    <a href="{{route('cms.connect-wallet')}}" class="btn btn-success btn-block"><i class="fa fa-link"></i> Connect Wallet</a>
                                </p>
                            </div>
                        </div>
                        <hr/>

                        <div class="row">
                            <div class="col-sm-6 col-lg-3 text-center">
                                <div class="mt-5 dropdown d-inline-block">
                                    <button class="btn btn-block btn-primary dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cart-plus"></i> Buy Crypto
                                    </button>
                                    <span class="caret"> </span>
                                    <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="https://www.moonpay.com/buy" target="_blank"><img src="/images/icons/moonpay.png" width="14"/> MoonPay</a>
                                        <a class="dropdown-item" href="https://global.transak.com/" target="_blank"><img src="/images/icons/transak.jpeg" width="14"/> Transak</a>
                                        <a class="dropdown-item" href="https://ramp.network/buy/" target="_blank"><img src="/images/icons/ramp.jpeg" width="14"/> Ramp</a>
                                        <a class="dropdown-item" href="https://www.simplex.com/account/buy" target="_blank"><img src="/images/icons/simplex.png" width="14"/> Simplex</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <p class="mt-5 text-center">
                                    <a href="{{route('core.client.withdrawal.create')}}" class="btn btn-block btn-phoenix-danger">
                                        <i class="fa fa-arrow-up"></i> Send
                                    </a>
                                </p>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <p class="mt-5 text-center">
                                    <a href="{{route('core.client.deposit.create')}}" class="btn btn-success btn-block"><i class="fa fa-arrow-down"></i> Receive</a>
                                </p>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <p class="mt-5 text-center">
                                    <a href="{{route('core.client.swap.create')}}" class="btn btn-block btn-phoenix-primary">
                                        <i class="fas fa-exchange-alt"></i> Swap
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <hr/>

                <div class="row">
                    @foreach ($user->accounts as $account)
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-default mb-5">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="left">
                                        <img src="{{$account->photo_url()}}" class="img-responsive img-circle center-block mb-2" style="max-height: 4em;"/>
                                        <p class="h4">{{$account->currency->name}}</p>
                                    </div>
                                    <div class="my-auto text-right">
                                        <p class="h3">{{$account->balance()}}</p>
                                        <p class="h4">{{$account->localBalance()}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between bg-primary">
                    <h5 class="modal-title text-white dark__text-gray-1100" id="staticBackdropLabel">PRE-ORDER WEB3 CARD!</h5>
                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9 text-white dark__text-gray-1100"></span></button>
                </div>
                <div class="modal-body">
                    <p class="text-body-tertiary lh-lg mb-0 lead">
                        Unlock the Secrets of the Quantum Financial System and Revolutionize Your Finances with the Web3 Card!<br/><br/>
                        Experience the Future of Finance Today!
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Not Now</button>
                    <a href="{{route('core.client.card.create')}}" class="btn btn-primary">Proceed!</a>
                </div>
            </div>
        </div>
    </div>    
@endsection
