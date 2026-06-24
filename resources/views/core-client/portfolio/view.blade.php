@extends('layouts.core.client')

@section('styles')
    <style>
        .text-green {
            color: green !important;
        }

        .text-red {
            color: darkred !important;
        }
    </style>
@endsection

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">Staking Slip</li>
        </ol>
    </nav>


    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8">
            <div class="mb-7 d-flex justify-content-between">
                <h2 class="my-0">Staking Slip </h2>
                <a class="btn btn-link fa-pull-right btn-sm" href="{{route('core.client.portfolio.manage')}}">Staking History</a>
            </div>
            <div class="card card-success">
                <h4 class="card-header">Stake-UUID: {{$portfolio->uuid}}</h4>
                <div class="card-body">
                    <div class="text-center">
                        <p>
                            <small>
                                <bold class="fw-bolder">{{$DCS}}</bold>
                                Amount:</small>
                            <br/>
                            <strong class="h2 mono-font">
                                {{$portfolio->amount()}}
                            </strong>
                        </p>
                        <p>
                            <small><i class="fa fa-adjust"></i> Status:</small>
                            <br/>
                            <strong class="h2 money">
                                {{$portfolio->status()}}
                            </strong>
                        </p>
                        <p>
                            <small><i class="fa fa-clock"></i> Lifespan:</small>
                            <br/>
                            <strong class="h4">
                                {{date_time_for_humans($portfolio->created_at)}} to {{date_time_for_humans($portfolio->expires_at)}}
                            </strong>
                            <br/>
                        </p>
                    </div>
                    <hr/>
                    <div class="text-center">
                        <p>
                            <strong>Portfolio Progress: {{$progress=round($portfolio->progress() * 100)}}% completed</strong>
                            <small class="text-muted">
                                ({{$portfolio->age()}} out of {{$package->max_duration}} days)
                            </small>
                        </p>
                        <div class="progress progress-xs progress-striped @if($portfolio->isActive()) active @endif">
                            <div style="width: {{$progress}}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{$progress}}" role="progressbar"
                                 class="progress-bar @if($portfolio->isCompleted()) progress-bar-success @endif">
                                <span class="sr-only">{{$progress}}% Complete</span>
                            </div>
                        </div>
                        @if($progress>0)
                            <p class="my-5">
                                <small><span class="fw-bolder">{{$DCS}}</span> Profits Earned:</small>
                                <br/>
                                <strong class="h2 mono-font">{{$portfolio->profit()}}</strong>
                            </p>
                        @endif
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3 col-lg-2">
                            <p class="px-5 px-sm-1 text-center">
                                <img src="{{$package->photoUrl}}" class="img-fluid" alt="{{$package->name}}">
                            </p>
                        </div>
                        <div class="col-md-9 col-lg-10 py-5 text-left">
                            <h4 class="h4">Package: {{$package->name}}</h4>
                            <div class="p-2">
                                <p class="font-weight-bold">
                                    Min: {{($package->minAmountStr())}}, Max: {{($package->maxAmountStr())}}
                                    @ {{round($package->interest_rate, 2)}}% interest every {{$package->interest_interval}} day(s).
                                </p>
                                <p class="font-weight-bold">
                                    Min-duration: {{$package->min_duration}} days,
                                    Max-duration: {{$package->max_duration}} days
                                </p>
                                {!! $package->description !!}
                            </div>
                        </div>
                    </div>
                    <hr/>

                    <div class="table-responsive text-left">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col" colspan="8" class="text-center"> Transactions on Stake (Net Total: {{$transactions->count()}})</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">##</th>
                                <th scope="col">Description</th>
                                <th scope="col" class="money">Amount</th>
                                <th scope="col" class="money">New Acc. Bal.</th>
                                <th scope="col">&nbsp; Date-Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sn = 1 @endphp
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="text-center">{{$sn++}}</td>
                                    <td>{{$transaction->description}}</td>
                                    <td class="money {{$transaction->isCredit()? 'text-green' : 'text-red'}}">{{$transaction->amount()}}</td>
                                    <td class="money">{{$transaction->newBalance()}}</td>
                                    <td class="no-wrap">&nbsp; {{date_time_for_humans($transaction->created_at)}}</td>
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
                        <form method="POST" action="{{route('core.client.portfolio.manage')}}"
                              class="ajax-form">
                            <input type="hidden" name="action" value="close">
                            <input type="hidden" name="ids[]" value="{{$portfolio->id}}">
                            <button type="submit" class="btn btn-sm btn-danger">Close Portfolio</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
