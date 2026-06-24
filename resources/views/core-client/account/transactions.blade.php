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
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">History</li>
        </ol>
    </nav>

    <div class="pb-9">
        <h2 class="mb-7">My Account History</h2>

        @include('_components.balance_notice')
        <br/>
        <div class="card card-default">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th colspan="9" class="text-center">Transaction History - Net Total: {{$transactions->total()}}</th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center">##</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Description</th>
                            <th scope="col">Sub-Account/Currency</th>
                            <th scope="col">Type</th>
                            <th scope="col" class="money">Amount</th>
                            <th scope="col" class="money">New Acc. Bal.</th>
                            <th scope="col">&nbsp; Date-Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($transactions) @endphp
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="text-center">{{$sn--}}</td>
                                <td class="no-wrap">{{$transaction->itemDesc()}}</td>
                                <td class="no-wrap">{{$transaction->description}}</td>
                                <td class="no-wrap">{{$transaction->account->currency->name}}</td>
                                <td class="no-wrap">{{$transaction->effect()}}</td>
                                <td class="money no-wrap {{$transaction->isCredit()? 'text-green' : 'text-red'}}">{{$transaction->amount()}} (<small>{{$transaction->localAmount()}}</small>)</td>
                                <td class="money no-wrap">{{$transaction->newBalance()}} (<small>{{$transaction->newLocalBalance()}}</small>)</td>
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
                        @if($transactions->total()>$transactions->count())
                            <tfoot>
                            <tr>
                                <td colspan="9" class="text-center">
                                    {{$transactions->links()}}
                                </td>
                            </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
