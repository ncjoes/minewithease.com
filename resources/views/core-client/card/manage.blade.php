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
            <h2 class="my-0">Manage My Web3 Prepaid Cards</h2>
            <a class="btn btn-phoenix-success btn-sm" href="{{route('core.client.card.create')}}"><i class="fa fa-plus"></i> New Card</a>
        </div>

        <form class="ajax-form" method="post" action="{{route('core.client.card.manage')}}">
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
                            @php $sn = end_sn($cards) @endphp
                            @forelse($cards as $card)
                                <tr class="@if($card->isPending()) text-warning @endif">
                                    <td class="text-center">{{$sn--}}</td>
                                    <td><label for="i-{{$card->id}}">{{$card->uuid}}</label></td>
                                    <td>{{$card->channel->currency->name}}</td>
                                    <td class="no-wrap money">{{$card->amount()}} (<small>{{$card->localAmount()}}</small>)</td>
                                    <td>{{date_time_for_humans($card->created_at)}}</td>
                                    <td>{{date_time_for_humans($card->verified_at)}}</td>
                                    <td>{{$card->status()}}</td>
                                    <td class="text-center"><a href="{{$card->url}}"><i class="fa fa-eye"></i> Details</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <strong>No records yet.</strong>
                                        <p>
                                            <a href="{{route('core.client.card.create')}}">
                                                Order a new card now <i class="fa fa-arrow-right"></i>
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
                    {{$cards->links()}}
                </div>

            </div>
        </form>
    </div>

@endsection
