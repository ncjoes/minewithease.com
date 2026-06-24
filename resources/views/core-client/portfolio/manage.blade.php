@extends('layouts.core.client')

@section('content')

    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">History</li>
        </ol>
    </nav>

    <div class="pb-9">
        <div class="mb-7 d-flex justify-content-between">
            <h2 class="my-0">My Staking History</h2>
            <a class="btn btn-phoenix-success btn-sm" href="{{route('core.client.portfolio.create')}}"><i class="fa fa-plus"></i> New Stake</a>
        </div>

        <form class="ajax-form" method="post" action="{{route('core.client.portfolio.manage')}}">
            {{csrf_field()}}
            <div class="card card-default">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">##</th>
                                <th scope="col">Ref.ID</th>
                                <th scope="col">Investment Plan</th>
                                <th scope="col" class="money">Amount</th>
                                <th scope="col">Interest Rate (% / days)</th>
                                <th scope="col">Lifespan</th>
                                <th scope="col">Last Rewarded</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">&hellip;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sn = end_sn($portfolios) @endphp
                            @forelse($portfolios as $portfolio)
                                <tr class="@if($portfolio->isActive()) text-green @endif">
                                    <td class="text-center">{{$sn--}}</td>
                                    <td><label for="i-{{$portfolio->id}}">{{$portfolio->uuid}}</label></td>
                                    <td>{{$portfolio->package->name}}</td>
                                    <td class="money font-weight-bold">{{$portfolio->amount()}}</td>
                                    <td>{{$portfolio->interest_rate}}% / {{$portfolio->interest_interval}} days(s)</td>
                                    <td>{{date_time_for_humans($portfolio->created_at)}} to {{date_time_for_humans($portfolio->expires_at)}}</td>
                                    <td>{{date_time_for_humans($portfolio->last_rewarded_at)}}</td>
                                    <td>{{$portfolio->status()}}</td>
                                    <td class="text-center"> <a href="{{$portfolio->url}}"><i class="fa fa-eye"></i> Details</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <strong>
                                            You have no portfolios yet. Create your first investment portfolio to
                                            start earning interests from our trading activities.
                                        </strong>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-center text-center">
                    {{$portfolios->links()}}
                </div>
            </div>
        </form>
    </div>
@endsection
