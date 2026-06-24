@extends('layouts.core.client')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Web App</a></li>
            <li class="breadcrumb-item active">Sending History</li>
        </ol>
    </nav>

    <div class="pb-9">
        <h2 class="mb-7">
            My Withdrawal History
            <a class="btn btn-phoenix-success fa-pull-right btn-sm" href="{{route('core.client.withdrawal.create')}}"><i class="fa fa-minus"></i> New Withdrawal</a>
        </h2>
        <br/>

        <form class="ajax-form" method="post" action="{{route('core.client.withdrawal.manage')}}">
            {{csrf_field()}}
            <div class="card card-default">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">##</th>
                                <th scope="col">Request-ID</th>
                                <th scope="col">Sub-Account</th>
                                <th scope="col" class="money">Amount</th>
                                <th scope="col">Date Submitted</th>
                                <th scope="col">Date Processed</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">&hellip;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sn = end_sn($withdrawals) @endphp
                            @forelse($withdrawals as $withdrawal)
                                <tr class="@if($withdrawal->isPaid()) text-success @endif">
                                    <td class="text-center">{{$sn--}}</td>
                                    <td><label for="i-{{$withdrawal->id}}">{{$withdrawal->uuid}}</label></td>
                                    <td>{{$withdrawal->account->channel()->name}}</td>
                                    <td class="money">{{$withdrawal->amount()}} | {{$withdrawal->localAmount()}}</td>
                                    <td>{{date_time_for_humans($withdrawal->created_at)}}</td>
                                    <td>{{date_time_for_humans($withdrawal->processed_at)}}</td>
                                    <td>{{$withdrawal->userStatus()}}</td>
                                    <td class="text-center"><a href="{{$withdrawal->url}}"><i class="fa fa-file"></i> Details</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <strong>No withdrawals yet.</strong>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-center text-center">
                    {{$withdrawals->links()}}
                </div>
            </div>
        </form>
    </div>
@endsection
