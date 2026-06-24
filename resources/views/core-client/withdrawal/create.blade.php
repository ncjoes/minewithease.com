@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Send Funds</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8">
            <div class="pb-9">
                <div class="d-flex justify-content-between">
                    <h2 class="mb-7">Send Funds</h2>
                    <a class="btn btn-link fa-pull-right btn-sm" href="{{route('core.client.withdrawal.manage')}}">History</a>
                </div>
                <br/>
                @if($withdrawals_allowed)
                    @if($user->getTotalBalance() > 0)
                        <div class="alert alert-phoenix-info text-center mb-5">
                            <p>
                                Hello {{$user->name}}, your current account status qualifies you to send up to 
                                {{$withdrawal_limit}} {{$withdrawal_interval == 1 ? 'daily' : ' every '.$withdrawal_interval . ' days'}}.
                            </p>
                            @if(is_object($next_withdrawal_date) and $next_withdrawal_date->greaterThan(now()))
                                <strong>You can submit your next withdrawal request after {{date_time_for_humans($next_withdrawal_date)}} </strong>
                                <p>Completeing your KYC requirements and obtaining a Web3-Card can significantly improve your account status.</p>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="alert alert-phoenix-warning text-center mb-5">
                        <strong style="text-decoration: blink;">Your Account is Currently Restricted from Submitting New Withdrawal Requests.</strong>
                        <p>Hello {{$user->name}}, your account has been temporarily restricted from making further withdrawals at this time. We have notified you on the
                            specifics.</p>
                        <p>Kindly contact your Account Officer/Manager for resolution quickly, if applicable. Thank You.</p>
                    </div>
                @endif

                <div class="card card-default">
                    <div class="card-body">
                        <form class="form-horizontal ajax-form" method="post" id="money-form" action="{{route('core.client.withdrawal.create')}}">

                            <fieldset>
                                <legend class="small font-weight-bold">Select sending source</legend>
                                <div class="mb-4">
                                    @forelse($accounts as $account)
                                        <div class="white p-2">
                                            <div class="row">
                                                <div class="col-4 col-sm-3 col-lg-1">
                                                    <label for="pm-{{$account->id}}" class="px-lg-3">
                                                        <img src="{{$account->photo_url()}}" class="img-fluid" alt="{{$account->currency->name}}" style="max-height: 40px;">
                                                    </label>
                                                </div>
                                                <div class="col-8 col-sm-9 col-lg-11">
                                                    <div class="form-group mb-0">
                                                        <label class="font-bold" for="pm-{{$account->id}}">
                                                            <input name="account_id" type="radio" value="{{$account->id}}"
                                                                   id="pm-{{$account->id}}" class="with-gap description-trigger"
                                                                   @if($loop->first) checked @endif
                                                                   data-toggle="desc-{{$account->id}}"
                                                                   data-min_amount="{{$account->channel()->min_amount}}"
                                                                   data-split_amount="{{$account->channel()->split_amount}}"
                                                                   data-max_amount="{{$account->channel()->max_amount}}">
                                                            {{$account->currency->name}}
                                                        </label>
                                                    </div>
                                                    <div id="desc-{{$account->id}}" class="description small">
                                                        <p class="font-weight-bold">
                                                            {{ $account->balance ? $account->balance().' ('.$account->localBalance() . ')' : '--No Available Balance--' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!$loop->last)
                                                <hr/>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="white p-2">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <p class="lead font-weight-bold">
                                                        No supported payment method for your account. Please contact support.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </fieldset>

                            <div class="form-group row mb-4">
                                <label for="wallet_address" class="control-label pt-2 col-lg-4">Receiving Address</label>
                                <div class="pt-2 col-lg-8">
                                    <input type="text" name="wallet_address" id="wallet_address" required class="form-control" @if(!$can_withdraw_atm) disabled @endif>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount" class="control-label pt-2 col-lg-4">How much do you wish to Send?</label>
                                <div class="pt-2 col-lg-8">
                                    <input type="number" min="{{$min_amount}}" max="{{$max_amount}}" name="amount" id="amount" required pattern="[0-9]*" class="form-control"
                                           @if(!$can_withdraw_atm) disabled @endif placeholder="amount in USD">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="pt-4 col text-center">
                                    <button type="submit" class="btn btn-block btn-primary" @if(!$can_withdraw_atm) disabled @endif>Send Funds</button>
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
