@extends('layouts.core.client')


@section('content')
    <nav class="mb-3 col-lg-6 col-xl-4 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Order Card</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 col-xl-4 mx-auto col-md-11">
            <div class="pb-9">
                <div class="d-flex justify-content-between">
                    <h2 class="mb-7">Order Web3 Prepaid Card</h2>
                    <!--<a class="btn btn-link btn-sm" href="{{route('core.client.deposit.manage')}}">History</a>-->
                </div>
                <!--@include('_components.balance_notice')-->
                <br/>
                <div class="card card-primary">
                    <div class="card-body">
                        <form class="form-horizontal ajax-form" method="post" id="money-form" action="{{route('core.client.card.create')}}">


                            <fieldset class="mt-4">
                                <p class="text-muted">Funding Details</p>
                                <div class="form-group row">
                                    <label for="channel" class="control-label pt-2 col-12 col-lg-4">Select funding currency</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <select name="channel" id="channel" required class="form-control">
                                            <option value="">-- Select Coin --</option>
                                            @foreach($channels as $channel)
                                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="amount" class="control-label pt-2 col-12 col-lg-4">Amount (USD)</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <input type="number" min="{{$min_amount}}" max="{{$max_amount}}" name="amount" id="amount" required readonly value="2300" class="form-control">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mt-4">
                                <p class="text-muted">Shipping Details</p>

                                <div class="form-group row">
                                    <label for="name" class="control-label pt-2 col-12 col-lg-4">Name</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <input type="text" name="name" id="name" required placeholder="Fullname" class="form-control" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="control-label pt-2 col-12 col-lg-4">Email Address</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <input type="email" name="email" id="email" required placeholder="email address" class="form-control" value="{{$user->email}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="control-label pt-2 col-12 col-lg-4">Phone Number</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <input type="tel" name="phone" id="phone" required placeholder="Phone number" class="form-control" value="{{$user->phone}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="control-label pt-2 col-12 col-lg-4">Full Address</label>
                                    <div class="pt-2 col-12 col-lg-8">
                                        <input type="text" name="address" id="address" required placeholder="Street address" class="form-control" value="{{$user->address}}">
                                    </div>
                                </div>
                            </fieldset>
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

@section('scripts')
    <script type="text/javascript">
        var amountField = $('input#amount');
        $('input[name=account]', '#money-form').on('change', function () {
            var target = $(this);
            /*$('.description').addClass('d-none');*/
            /*$('#' + target.data('toggle')).removeClass('d-none');*/
            var min_amount = target.data('min_amount');
            var max_amount = target.data('max_amount');
            var split_amount = target.data('split_amount');

            amountField.attr('min', min_amount);
            amountField.attr('max', max_amount);
            amountField.attr('step', split_amount);
        });
    </script>
@endsection
