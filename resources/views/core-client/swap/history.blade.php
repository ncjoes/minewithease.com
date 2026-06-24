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
            <h2 class="my-0">My Coin Swaps History</h2>
            <a class="btn btn-phoenix-success btn-sm" href="{{route('core.client.swap.create')}}"><i class="fa fa-plus"></i> New Swap</a>
        </div>

        <div class="card card-default">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">##</th>
                            <th scope="col">Swap-ID</th>
                            <th scope="col">From...</th>
                            <th scope="col">To...</th>
                            <th class="money">Amount</th>
                            <th scope="col">Date</th>
                            <th scope="col">&nbsp; Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($swaps) @endphp
                        @forelse($swaps as $swap)
                            <tr class="@if($swap->isPending()) text-warning @endif">
                                <td class="text-center">{{$sn--}}</td>
                                <td><label for="i-{{$swap->id}}">{{$swap->uuid}}</label></td>
                                <td>{{$swap->sourceAccount->currency->name}}</td>
                                <td>{{$swap->destinationAccount->currency->name}}</td>
                                <td class="no-wrap money">{{$swap->amount()}} (<small>{{$swap->sourceLocalAmount()}}</small> -> <small>{{$swap->destinationLocalAmount()}}</small>)</td>
                                <td>{{date_time_for_humans($swap->created_at)}}</td>
                                <td>&nbsp; {{$swap->status()}}</td>
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
                {{$swaps->links()}}
            </div>

        </div>
    </div>

@endsection
