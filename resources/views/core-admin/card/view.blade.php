@extends('layouts.core.admin')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 col-lg-offset-3 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="clearfix">
                        Web3 Card Request - {{$card->uuid}}
                        <a class="btn btn-primary pull-right btn-sm" href="{{route('core.admin.card.manage')}}">All Cards</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <section class="invoice">
                        @if (is_array($message = session()->get('message')))
                            @php
                                $class = 'alert-'.$message['status']
                            @endphp
                            <div class="alert {{$class}} text-center">
                                {{ $message['message'] }}
                            </div>
                        @endif

                        <div class="grey lighten-4 border text-center">
                            <p>
                                <small><i class="fa fa-money"></i> Amount:</small>
                                <br/>
                                <strong class="h2 money">
                                    {{$card->amount()}}
                                    @if($show_local)
                                        <br/>
                                        <small>{{$card->localAmount()}}</small>
                                    @endif
                                </strong>
                            </p>
                            <p>
                                <small><i class="fa fa-microchip"></i> Invoice ID:</small>
                                <br/>
                                <strong class="h4 mono-font">{{$card->uuid}}</strong>
                            </p>
                            <hr/>
                            <div class="table-responsive text-left p-2">
                                <table class="table table-bordered table-sm d-table">
                                    <tr>
                                        <th class="w-25">Account Name</th>
                                        <td><a href="{{$card->user->admin_url}}">{{$card->user->name}}</a></td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">Contact Details</th>
                                        <td>{{$card->user->email}} | {{$card->user->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">Invoice Date/Time</th>
                                        <td>{{date_time_for_humans($card->created_at)}}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">Payment Channel</th>
                                        <td><a href="{{$card->channel->edit_url}}">{{$card->channel->currency->name}}</a></td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">Payment Reference</th>
                                        <td>{{$card->payment_reference}}</td>
                                    </tr>
                                    @if($card->verified_at)
                                        <tr>
                                            <th class="w-25">Date/Time Verified</th>
                                            <td>{{date_time_for_humans($card->verified_at)}}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <hr/>
                            <div class="mt-3 pb-3">
                                <strong>
                                    <small><i class="fa fa-star"></i> Status</small>
                                    <br/>
                                    <span class="h4 {{$card->status()}}">{{$card->status()}}</span>
                                </strong>
                                @if($card->isCancelable() or $card->isProcessing())
                                    <hr/>
                                    <form method="POST" action="{{route('core.admin.card.manage')}}" class="ajax-form">
                                        <input type="hidden" name="action" value="verify">
                                        <input type="hidden" name="ids[]" value="{{$card->id}}">
                                        <div class="form-group">
                                            <label for="trans_hash" class="col-form-label">Payment Reference</label>
                                            <div class="col">
                                                <input id="trans_hash" class="form-control text-center form-control-sm" name="trans_hash[{{$card->id}}]" value="{{$card->payment_reference}}"/>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">Verify Payment</button>
                                    </form>

                                    <hr/>
                                    <form method="POST" class="ajax-form" action="{{route('core.admin.card.manage')}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="ids[]" value="{{$card->id}}">
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel Invoice</button>
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
