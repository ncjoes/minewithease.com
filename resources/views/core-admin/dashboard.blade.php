@extends('layouts.core.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col col-xs-6 col-sm-3 text-center">
                    <a href="{{route('core.admin.user.manage')}}">
                        <i class="fa fa-users fa-5x py-5"></i><br/>
                        <h3 class="small my-0">User Accounts</h3>
                    </a>
                </div>
                <div class="col col-xs-6 col-sm-3 text-center">
                    <a href="{{route('core.admin.user.manage')}}">
                        <h3 class="h3">
                            <span class="small">Grand Total</span><br/>
                            <span class="money">{{$stats['user']['grand_total']}}</span>
                        </h3>
                        <h2 class="h2">
                            <span class="small">Active Users</span><br/>
                            <span class="money">{{$stats['user']['active_total']}}</span>
                        </h2>
                    </a>
                </div>
                <div class="col col-xs-12 col-sm-6 text-center">
                    <a href="{{route('core.admin.user.manage')}}">
                        <h1 class="h1">
                            <i class="fa fa-money"></i><br/>
                            <span class="small">Total Account Balance</span><br/>
                            <span class="money">{{to_currency($stats['user']['total_balance'], $DCS)}}</span>
                        </h1>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <a href="{{route('core.admin.deposit.manage')}}">
                        <i class="fa fa-cloud-upload fa-5x small"></i>
                        <p class="h3">Deposits</p>
                        <h2 class="h2">
                            <span class="small">All Time Verified ({{$stats['deposit']['verified_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['deposit']['verified_amount'],$DCS)}}</span>
                        </h2>
                        <h3 class="h3">
                            <span class="small">Currently Pending ({{$stats['deposit']['pending_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['deposit']['pending_amount'],$DCS)}}</span>
                        </h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <a href="{{route('core.admin.portfolio.manage')}}">
                        <i class="fa fa-briefcase fa-5x"></i>
                        <p class="h3">Portfolios</p>
                        <h3 class="h3">
                            <span class="small">All Time ({{$stats['portfolio']['net_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['portfolio']['net_amount'],$DCS)}}</span>
                        </h3>
                        <h2 class="h2">
                            <span class="small">Currently Active ({{$stats['portfolio']['active_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['portfolio']['active_amount'],$DCS)}}</span>
                        </h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <a href="{{route('core.admin.bonus.manage')}}">
                        <i class="fa fa-trophy fa-5x"></i>
                        <p class="h3">Bonuses</p>
                        <h3 class="h3">
                            <span class="small">All Time ({{$stats['bonus']['net_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['bonus']['net_amount'],$DCS)}}</span>
                        </h3>
                        <h2 class="h2">
                            <span class="small">Released ({{$stats['bonus']['released_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['bonus']['released_amount'],$DCS)}}</span>
                        </h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <a href="{{route('core.admin.withdrawal.manage')}}">
                        <i class="fa fa-cloud-download fa-5x"></i>
                        <p class="h3">Withdrawals</p>
                        <h2 class="h2">
                            <span class="small">All Time Paid ({{$stats['withdrawal']['paid_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['withdrawal']['paid_amount'],$DCS)}}</span>
                        </h2>
                        <h3 class="h3">
                            <span class="small">Currently Pending ({{$stats['withdrawal']['pending_count']}})</span><br/>
                            <span class="money">{{to_currency($stats['withdrawal']['pending_amount'],$DCS)}}</span>
                        </h3>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row text-center">
                <div class="col col-xs-6 col-sm-3">
                    <a href="{{route('core.admin.package.manage')}}">
                        <h3>
                            <i class="fa fa-briefcase"></i><br/>
                            <span class="small">Investment Packages</span><br/>
                            {{$stats['packages']}}
                        </h3>
                    </a>
                </div>
                <div class="col col-xs-6 col-sm-3">
                    <a href="{{route('core.admin.country.manage')}}">
                        <h3>
                            <i class="fa fa-map-marker"></i><br/>
                            <span class="small">Supported Countries</span><br/>
                            {{$stats['countries']}}
                        </h3>
                    </a>
                </div>
                <div class="col col-xs-6 col-sm-3">
                    <a href="{{route('core.admin.currency.manage')}}">
                        <h3>
                            <i class="fa fa-money"></i><br/>
                            <span class="small">Supported Currencies</span><br/>
                            {{$stats['currencies']}}
                        </h3>
                    </a>
                </div>
                <div class="col col-xs-6 col-sm-3">
                    <a href="{{route('core.admin.channel.manage')}}">
                        <h3>
                            <i class="fa fa-bank"></i><br/>
                            <span class="small">Payment Channels</span><br/>
                            {{$stats['channels']}}
                        </h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
