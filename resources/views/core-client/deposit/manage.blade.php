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
            <h2 class="my-0">My Deposits History</h2>
            <a class="btn btn-phoenix-success btn-sm" href="{{route('core.client.deposit.create')}}"><i class="fa fa-plus"></i> New Deposit</a>
        </div>

        <form class="ajax-form" method="post" action="{{route('core.client.deposit.manage')}}">
            {{csrf_field()}}
            <div class="card card-default">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">##</th>
                                <th scope="col">Invoice-ID</th>
                                <th scope="col">Sub-Account</th>
                                <th class="money">Amount</th>
                                <th scope="col">Date on Invoice</th>
                                <th scope="col">Date Verified</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">&hellip;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sn = end_sn($deposits) @endphp
                            @forelse($deposits as $deposit)
                                <tr class="@if($deposit->isPending()) text-warning @endif">
                                    <td class="text-center">{{$sn--}}</td>
                                    <td><label for="i-{{$deposit->id}}">{{$deposit->uuid}}</label></td>
                                    <td>{{$deposit->account->currency->name}}</td>
                                    <td class="no-wrap money">{{$deposit->amount()}} (<small>{{$deposit->localAmount()}}</small>)</td>
                                    <td>{{date_time_for_humans($deposit->created_at)}}</td>
                                    <td>{{date_time_for_humans($deposit->verified_at)}}</td>
                                    <td>{{$deposit->status()}}</td>
                                    <td class="text-center"><a href="{{$deposit->url}}"><i class="fa fa-eye"></i> Details</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <strong>No records yet.</strong>
                                        <p>
                                            <a href="{{route('core.client.deposit.create')}}">
                                                Add funds to your account now <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-center text-center">
                    {{$deposits->links()}}
                </div>

            </div>
        </form>
    </div>

@endsection
