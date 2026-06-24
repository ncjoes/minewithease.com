@extends('layouts.core.client')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Stakes</li>
        </ol>
    </nav>
    <div class="pb-9">
        <div class="d-flex justify-content-between">
            <h2 class="mb-7">My Stakes</h2>
            <a class="btn btn-link btn-sm" href="{{route('core.client.portfolio.manage')}}">History</a>
        </div>
        <div class="row">
            <div class="col-xl-12 col-xxl-9 mb-1">
                <form class="form-horizontal ajax-form" method="post" id="staking-form" action="{{route('core.client.portfolio.create')}}">

                    <div class="mb-7">
                        <div class="tab-pane fade show active" id="pills-month" role="tabpanel" aria-labelledby="pills-month-tab">
                            <div class="row g-3">

                                @foreach($packages as $package)
                                    <div class="col-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="h-100">
                                            <input class="card-form-check-input d-none" type="radio" name="package" value="{{$package->id}}" id="{{'package-'.$package->id}}"
                                                   data-min_amount="{{$package->min_amount}}" data-max_amount="{{$package->max_amount}}"/>
                                            <div class="position-relative h-100">
                                                <label class="stretched-link" for="{{'package-'.$package->id}}"></label>
                                                <div class="card h-100 overflow-hidden cursor-pointer">
                                                    <div class="bg-holder d-dark-none"
                                                         style="background-image:url(/assets/img/bg/9.png);background-position:left bottom;background-size:auto;bottom:-1px;"></div>
                                                    <!--/.bg-holder-->
                                                    <div class="bg-holder d-light-none"
                                                         style="background-image:url(/assets/img/bg/9-dark.png);background-position:left bottom;background-size:auto;bottom:-1px;"></div>
                                                    <!--/.bg-holder-->
                                                    <div class="card-body d-flex flex-column justify-content-between position-relative">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="mb-5 mb-md-0 mb-lg-5 me-3">
                                                                <div class="d-sm-flex align-items-center mb-3">
                                                                    <h3 class="mb-0">{{$package->name}}</h3>
                                                                </div>
                                                                <p class="fs-9 text-body-tertiary">{!! nl2br($package->description) !!}</p>
                                                                <div class="d-flex align-items-end mb-md-5 mb-lg-0">
                                                                    <h4 class="fw-bolder me-1">
                                                                        @if($package->min_interest_rate==$package->max_interest_rate)
                                                                            {{$package->min_interest_rate.'%'}}
                                                                        @else
                                                                            {{$package->min_interest_rate.'% - '.$package->max_interest_rate.'%'}}
                                                                        @endif
                                                                    </h4>
                                                                    <h5 class="fs-9 fw-normal text-body-tertiary ms-1">
                                                                        {{($package->interest_interval==1)?'daily interest':'interest every '.$package->interest_interval.' days'}}
                                                                    </h5>
                                                                </div>
                                                                <div class="fs-8">
                                                                    <p class="my-2">
                                                                        @if($package->min_duration==$package->max_duration)
                                                                            {{$package->min_duration.' days duration'}}
                                                                        @else
                                                                            {{$package->min_duration.' - '.$package->max_duration.' days duration'}}
                                                                        @endif
                                                                    </p>
                                                                    <p class="my-2">
                                                                        @if($package->min_amount==$package->max_amount)
                                                                            {{$package->minAmount().' staking amount'}}
                                                                        @else
                                                                            {{$package->minAmount().' - '.$package->maxAmount().' staking amount'}}
                                                                        @endif
                                                                    </p>
                                                                    <p>Principal available after {{$package->min_duration}} days</p>
                                                                </div>
                                                            </div>
                                                            <img class="d-dark-none" src="/assets/img/spot-illustrations/bag-2.png" width="54" height="54" alt=""/>
                                                            <img class="d-light-none" src="/assets/img/spot-illustrations/bag-2-dark.png" width="54" height="54" alt=""/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!--
                                <div class="col-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="h-100">
                                        <input class="card-form-check-input d-none pricing-plan-recommended" disabled type="radio" name="pricingMonthly" id="businessPlus"/>
                                        <div class="position-relative h-100">
                                            <label class="stretched-link" for="businessPlus"></label>
                                            <div class="card h-100 overflow-hidden cursor-pointer bg-warning-subtle border-warning warning-boxshadow pricing-business-plus">
                                                <div class="bg-holder d-dark-none"
                                                     style="background-image:url(/assets/img/bg/bg-11.png);background-position:left bottom;background-size:auto;"></div>
                                                <!--/.bg-holder-->
                                                <!--
                                                <div class="bg-holder d-light-none"
                                                     style="background-image:url(/assets/img/bg/bg-11-dark.png);background-position:left bottom;background-size:auto;"></div>
                                                <!--/.bg-holder-->
                                                <!--
                                                <div class="card-body d-flex flex-column justify-content-between position-relative">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="mb-5 mb-md-0 mb-lg-5 me-3">
                                                            <div class="d-sm-flex d-md-block d-lg-flex align-items-center mb-3">
                                                                <h3 class="mb-0">MEV Bot</h3>
                                                                <span class="badge ms-sm-3 ms-md-0 ms-lg-3 text-uppercase fs-10 text-bg-warning">coming soon</span>
                                                            </div>
                                                            <p class="fs-9 text-body-tertiary">Stay tuned for this very interesting offer</p>
                                                        </div>
                                                        <img class="d-dark-none" src="/assets/img/spot-illustrations/star.png" width="54" height="54" alt=""/>
                                                        <img class="d-light-none" src="/assets/img/spot-illustrations/star-dark.png" width="54" height="54" alt=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
    
                            </div>
                        </div>
                    </div>
                    <div>
                        <!--
                        <p class="mb-0">
                            Business Starter, Business Standard, and Business Plus plans can be purchased for a maximum of 300 users. There is no
                            <br class="d-none d-xl-block d-xxl-none"/>
                            maximum user limit for Enterprise plans.
                        </p>
                        -->
                        <div class="form-group row mb-3">
                            <label for="account" class="control-label pt-2 col-12 col-lg-2">Select funding source (sub-account)</label>
                            <div class="pt-2 col-12 col-lg-10">
                                <select name="account" id="account" required class="form-control">
                                    <option value="">-- Select Account --</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}">{{$account->currency->name}} | Balance: {{$account->balance()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="amount" class="control-label pt-2 col-12 col-lg-2">How much do you wish to stake?</label>
                            <div class=" col-12 col-lg-10">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="amount-addon">{{$DCS}}</span>
                                    <input class="form-control" type="number" name="amount" id="amount" required placeholder="Staking Amount" aria-label="Staking Amount" aria-describedby="amount-addon"/>
                                </div>
                            </div>
                        </div>
                        <p class="fw-semibold small" id="amount-notice"></p>

                        <div class="d-grid d-sm-flex">
                            <button class="btn btn-lg btn-primary d-sm-flex align-items-center mb-3 mb-sm-0 me-sm-3 px-sm-8">
                                Stake Now<span class="fas fa-angle-right ms-1"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col col-xxl-3 mt-8">
                <h3 class="fw-semibold mb-3">Included in our Bots</h3>
                <div class="row">
                    <div class="col-md-6 col-xxl-12">
                        <div class="rounded-3 py-2 px-3 bg-body-emphasis d-flex align-items-center mb-3"><span class="fas fa-check text-primary me-3 fs-9"></span>
                            <p class="mb-0 text-body-secondary">Zero Trading Fees</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xxl-12">
                        <div class="rounded-3 py-2 px-3 bg-body-emphasis d-flex align-items-center mb-3"><span class="fas fa-check text-primary me-3 fs-9"></span>
                            <p class="mb-0 text-body-secondary">Automated Prompt Payments</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xxl-12">
                        <div class="rounded-3 py-2 px-3 bg-body-emphasis d-flex align-items-center mb-3"><span class="fas fa-check text-primary me-3 fs-9"></span>
                            <p class="mb-0 text-body-secondary">100% of Principal available after staking period</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xxl-12">
                        <div class="rounded-3 py-2 px-3 bg-body-emphasis d-flex align-items-center mb-3"><span class="fas fa-check text-primary me-3 fs-9"></span>
                            <p class="mb-0 text-body-secondary">Multiple Concurrent Stake across Bots</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xxl-12">
                        <div class="rounded-3 py-2 px-3 bg-body-emphasis d-flex align-items-center mb-3"><span class="fas fa-check text-primary me-3 fs-9"></span>
                            <p class="mb-0 text-body-secondary">Dedicated Support Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var amountField = $('input#amount');
        $('input[name=package]', '#staking-form').on('change', function () {
            var target = $(this);

            var min_amount = target.data('min_amount');
            var max_amount = target.data('max_amount');
            var NF = window.numberFormat;
            amountField.attr('min', min_amount);
            amountField.attr('max', max_amount);
            $('#amount-notice').html('(Min: ' + NF.format(min_amount) + ', Max: ' + NF.format(max_amount) + ')');
        });
    </script>
@endsection
