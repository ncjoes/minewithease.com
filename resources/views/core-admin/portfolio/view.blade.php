@extends('layouts.core.admin')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    <h4 class="clearfix">
                        Portfolio UUID - {{$portfolio->uuid}}
                        <a class="btn btn-primary pull-right btn-sm" href="{{route('core.admin.portfolio.manage')}}">All Portfolios</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <section class="invoice">
                        <div class="grey lighten-4 border text-center">
                            <p>
                                <small><i class="fa fa-money"></i> Amount:</small>
                                <br/>
                                <strong class="h2 mono-font">
                                    {{$portfolio->amount()}}
                                </strong>
                            </p>
                            <p>
                                <small><i class="fa fa-star-o"></i> Status:</small>
                                <br/>
                                <strong class="h2 money">
                                    {{$portfolio->status()}}
                                </strong>
                            </p>
                            <p>
                                <small><i class="fa fa-star-o"></i> Lifespan:</small>
                                <br/>
                                <strong class="h4">
                                    {{date_time_for_humans($portfolio->created_at)}} to
                                    {{date_time_for_humans($portfolio->expires_at)}}
                                </strong>
                            </p>
                            <hr/>
                            <div class="">
                                <p>
                                    <strong>
                                        Portfolio Progress: {{$progress=round($portfolio->progress()*100)}}% completed
                                    </strong>
                                    <small class="text-muted">
                                        ({{$portfolio->age()}} out of {{$package->max_duration}} days)
                                    </small>
                                </p>
                                <div class="progress progress-xs progress-striped @if($portfolio->isActive()) active @endif">
                                    <div style="width: {{$progress}}%" aria-valuemax="100" aria-valuemin="0"
                                         aria-valuenow="{{$progress}}"
                                         role="progressbar"
                                         class="progress-bar @if($portfolio->isCompleted()) progress-bar-success @endif">
                                        <span class="sr-only">{{$progress}}% Complete</span>
                                    </div>
                                </div>
                                @if($progress>0)
                                    <p>
                                        <small><i class="fa fa-money"></i> Profits Earned:</small>
                                        <br/>
                                        <strong class="h2 mono-font">{{$portfolio->profit()}}</strong>
                                    </p>
                                @endif
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-3 col-lg-2">
                                    <p class="px-5 px-sm-1">
                                        <img src="{{$package->photoUrl}}" class="img-responsive"
                                             alt="{{$package->name}}">
                                    </p>
                                </div>
                                <div class="col-md-9 col-lg-9 text-md-left">
                                    <a href="{{$package->edit_url}}">
                                        <h4 class="h4">Package: {{$package->name}} <i class="fa fa-pencil"></i></h4>
                                    </a>
                                    <div class="p-2">
                                        <p class="font-weight-bold">Min-amount: {{($package->minAmountStr())}},
                                            Max-amount: {{($package->maxAmountStr())}} @
                                            {{round($package->interest_rate, 2)}}% interest
                                            every {{$package->interest_interval}} day(s).</p>
                                        <p class="font-weight-bold">
                                            Min-duration: {{$package->min_duration}} days,
                                            Max-duration: {{$package->max_duration}} days
                                        </p>
                                        {!! $package->description !!}
                                    </div>
                                </div>
                            </div>
                            <hr/>

                            <div class="table-responsive text-left p-2">
                                <table class="table table-bordered table-sm d-table">
                                    <tr>
                                        <th width="25%">Account Name</th>
                                        <td><a href="{{$_user->admin_url}}">{{$_user->name}}</a></td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Contact Details</th>
                                        <td>{{$_user->email}} | {{$_user->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Date/Time Joined</th>
                                        <td>{{$_user->created_at}}</td>
                                    </tr>
                                </table>
                            </div>
                            <hr/>

                            <div class="table-responsive text-left">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th colspan="8"> Transactions on Portfolio (Net Total: {{$transactions->count()}})</th>
                                    </tr>
                                    <tr>
                                        <th width="4%">##</th>
                                        <th>Description</th>
                                        <th class="money">Amount</th>
                                        <th class="money">New Acc. Bal.</th>
                                        <th>Date-Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $sn = 1 @endphp
                                    @forelse($transactions as $transaction)
                                        <tr class="{{$transaction->isCredit()? 'text-green' : 'text-danger'}}">
                                            <td>{{$sn++}}</td>
                                            <td>{{$transaction->description}}</td>
                                            <td class="money">{{$transaction->amount()}}</td>
                                            <td class="money">{{$transaction->newBalance()}}</td>
                                            <td class="no-wrap">{{date_time_for_humans($transaction->created_at)}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <strong>
                                                    You have no transactions yet.
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($portfolio->isCancellable())
                                <hr/>
                                <form method="POST" action="{{route('core.admin.portfolio.manage')}}"
                                      class="ajax-form">
                                    <input type="hidden" name="action" value="close">
                                    <input type="hidden" name="ids[]" value="{{$portfolio->id}}">
                                    <button type="submit" class="btn btn-sm btn-danger">Close Portfolio</button>
                                </form>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
