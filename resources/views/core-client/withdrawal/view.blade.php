@extends('layouts.core.client')

@section('content')
    <nav class="mb-3 col-lg-6 mx-auto col-md-8" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('core.client.dashboard')}}">Wallet</a></li>
            <li class="breadcrumb-item active">Send Funds</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mx-auto col-md-8">
            <div class="pb-9">
                <h2 class="mb-7">Withdrawal Information</h2>
                <div class="card card-info">
                    <h4 class="card-header clearfix">
                        Withdrawal Request - {{$withdrawal->uuid}}
                        <a class="btn btn-link fa-pull-right btn-sm" href="{{route('core.client.withdrawal.manage')}}"> Withdrawal History</a>
                    </h4>
                    <div class="card-body">
                        <section class="py-3">
                            @if (is_array($message = session()->get('message')))
                                @php
                                    $class = 'alert-'.$message['status']
                                @endphp
                                <div class="alert {{$class}} text-center">
                                    {!! $message['message'] !!}
                                </div>
                            @endif

                            <div class="grey lighten-4 border">
                                <p class=" text-center">
                                    <small><i class="fa fa-money"></i> Amount:</small>
                                    <br/>
                                    <strong class="h2 money">
                                        {{$withdrawal->amount()}}
                                        @if($show_local)
                                            |
                                            <small>{{$withdrawal->localAmount()}}</small>
                                        @endif
                                    </strong>
                                </p>
                                <div class="table-responsive text-left p-2">
                                    <table class="table table-bordered table-sm d-table">
                                        <tr>
                                            <th width="25%">Account Name</th>
                                            <td>{{$withdrawal->account->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Sub-Account</th>
                                            <td>{{$withdrawal->account->currency->name}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Wallet Address</th>
                                            <td>{{$withdrawal->payment_wallet}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Request Date</th>
                                            <td>{{date_time_for_humans($withdrawal->created_at)}}</td>
                                        </tr>
                                        @if($withdrawal->isPaid())
                                            <tr>
                                                <th width="25%">Date Processed</th>
                                                <td>{{date_time_for_humans($withdrawal->processed_at)}}</td>
                                            </tr>
                                            <tr>
                                                <th width="25%">Transaction Hash/Reference</th>
                                                <td>{{$withdrawal->getTransReference()}}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="my-3 text-center">
                                    <p class="">
                                        <strong>
                                            <small><i class="fa fa-star"></i> Status</small>
                                            <br/>
                                            <span class="h4 {{$withdrawal->status()}}">{{$withdrawal->userStatus()}}</span>
                                        </strong>
                                    </p>
                                    @if($withdrawal->isApproved())
                                        <div class="progress progress-lg progress-striped active">
                                            <div style="width: {{$withdrawal->progress_value}}%" aria-valuemax="100" aria-valuemin="0"
                                                 aria-valuenow="{{$withdrawal->progress_value}}"
                                                 role="progressbar" class="progress-bar">
                                                <span>{{$withdrawal->progress_value}}% Completed</span>
                                            </div>
                                        </div>
                                        <p class="text-blue">{{$withdrawal->progress_description}}</p>
                                    @endif
                                    @if($withdrawal->isCancellable())
                                        <hr/>
                                        <form method="POST" action="{{route('core.client.withdrawal.manage')}}"
                                              class="ajax-form">
                                            <input type="hidden" name="action" value="cancel">
                                            <input type="hidden" name="ids[]" value="{{$withdrawal->id}}">
                                            <button type="submit" class="btn btn-sm btn-danger">Cancel Request</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
@endsection
