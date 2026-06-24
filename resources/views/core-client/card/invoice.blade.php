@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Card Invoice</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8">
            <div class="pb-9">
                <div class="mb-7 d-flex justify-content-between">
                    <h2 class="my-0">Pay for Web3 Card Order</h2>
                    <a class="btn btn-link fa-pull-right btn-sm" href="{{route('core.client.card.manage')}}">History</a>
                </div>
                <div class="card card-info">
                    <h4 class="card-header">Card Order Invoice</h4>
                    <div class="card-body">
                        <section class="invoice">
                            @if (is_array($message = session()->get('message')))
                                @php
                                    $class = 'alert-'.$message['status']
                                @endphp
                                <div class="alert {{$class}} text-center">
                                    {!! $message['message'] !!}
                                </div>
                            @endif

                                <div class="text-center">
                                <p>
                                    <small><i class="fa fa-file"></i> Amount:</small>
                                    <br/>
                                    <strong class="h2 mono-font">
                                        {{$card->amount()}}
                                        <br/>
                                        <small>{{$card->localAmount()}}</small>
                                    </strong>
                                </p>
                                <p>
                                    <small><i class="fa fa-microchip"></i> Invoice ID:</small>
                                    <br/>
                                    <strong class="h4 mono-font">{{$card->uuid}}</strong>
                                </p>
                                </div>

                                <hr/>
                                <div class="row">
                                    <div class="col-md-3 col-lg-2 text-center">
                                        <p class="px-5 px-sm-1 text-center">
                                            <img src="{{$channel->photoUrl}}" class="img-fluid center-block" alt="{{$channel->name}}">
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-lg-8 text-md-left">
                                        <h5 class="h5">Payment Currency: <strong>{{$channel->currency->name}}</strong></h5>
                                        <div class="p-2">{{$channel->description}}</div>
                                        <div class="p-2">Wallet Address: <code>{{$channel->payment_wallet}}</code></div>
                                        <div class="p-2">{{$channel->currency->name}} Amount: <code>{{$card->localAmount()}}</code></div>
                                        @if(strlen($card->payment_reference))
                                            <div class="p-2">Transaction Reference: <code>{{$card->payment_reference}}</code></div>
                                        @endif
                                        @if($card->verified_at)
                                            <div class="p-2">Payment verified on: <strong>{{date_time_for_humans($card->verified_at)}}</strong></div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-lg-2 text-center justify-content-center">
                                        <img id="coin_payment_image" class="img-fluid center-block" alt="--image-not-available--"
                                             src="https://quickchart.io/qr?text={{strtolower($channel->currency->alpha_code)}}:{{$channel->payment_wallet}}&amount={{$card->local_amount}}">
                                    </div>
                                </div>
                                <hr/>
                                <div class="mt-3 pb-3">
                                    <p class="text-center">
                                        <strong>
                                            <small><i class="fa fa-star"></i> Status</small>
                                            <br/>
                                            <span class="h4 {{$card->status()}}">{{$card->status()}}</span>
                                        </strong>
                                        @if($card->isPending())
                                            <br/>
                                            <small>You have till <strong>{{date_time_for_humans($card->expires_at)}}</strong> to complete the payment.</small>
                                        @endif
                                    </p>
                                    @if($card->isCancelable() or $card->isProcessing())
                                        <hr/>
                                        <h5>PAYMENT INSTRUCTIONS</h5>
                                        <p>
                                            Kindly make the payment using your preferred payment method.
                                            If you chose payment via Ethereum, please <span style="color: red;">do not send it using contracts / internal transactions</span>.<br/>
                                            Your {{$org_name}} prepaid card will be credited with the sum of {{ $card->amount() }} after at least 2 confirmations of your
                                            payment on the network.
                                        </p>
                                        <ul class="text-left">
                                            <li>
                                                For <strong>automatic payment confirmation</strong>, select [Open My Wallet App.] to complete the transaction
                                                on the default wallet app on your device.
                                            </li>
                                            <li>
                                                For <strong>manual payment processing</strong>, transfer the exact amount in {{$currency->name}} to the address
                                                provided (directly or by scanning the QR-code).
                                            </li>
                                            <li>
                                                On comleting the payment, submit the <em>Transaction Hash</em> and click [Submit Payment] button.
                                            </li>
                                        </ul>

                                        <hr/>
                                        <p class="py-5 text-center">
                                            <a href="{{$payment_link}}" class="btn btn-phoenix-primary mce-btn-small">Open My Wallet App.</a>
                                        </p>

                                        <form method="POST" class="ajax-form" action="{{route('core.client.card.manage')}}" onsubmit="return false;">
                                            {{csrf_field()}}
                                            <input type="hidden" name="ids[]" value="{{$card->id}}">
                                            <input type="hidden" name="action" value="" id="">
                                            <div class="text-center">
                                                <div class="form-group">
                                                    <label for="trans_hash" class="col-form-label">Transaction Hash/Reference</label>
                                                    <div class="col">
                                                        <input id="trans_hash" class="form-control text-center form-control-sm" name="trans_hash[{{$card->id}}]" value="{{$card->payment_reference}}"/>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="">
                                                    <button type="submit" data-action="claim_pay" class="btn btn-primary action-btn">
                                                        Submit Payment <i class="fa fa-file"></i>
                                                    </button>
                                                    <button type="submit" data-action="cancel" class="btn btn-danger action-btn">Cancel Invoice <i class="fa fa-close"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        $(function () {
            $('button[type=submit].action-btn').on('click', function () {
                $('input[name=action]').prop('value', $(this).data('action'));
            });
        });
    </script>
@endsection
