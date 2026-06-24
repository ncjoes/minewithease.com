@php use App\Models\Core\Withdrawal; @endphp
@extends('layouts.core.admin')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 col-lg-offset-3 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="clearfix">
                        Withdrawal Request - {{$withdrawal->uuid}}
                        <a class="btn btn-primary pull-right btn-sm" href="{{route('core.admin.withdrawal.manage')}}">All Withdrawals</a>
                    </h4>
                </div>
                <div class="panel-body">
                    <section class="cashout">
                        <div class="grey lighten-4 border">
                            <p class="text-center">
                                <small><i class="fa fa-money"></i> Amount:</small>
                                <br/>
                                <strong class="h2 money">
                                    {{$withdrawal->amount()}}
                                    @if($show_local)
                                        <br/>
                                        <small>{{$withdrawal->localAmount()}}</small>
                                    @endif
                                </strong>
                            </p>
                            <div class="table-responsive text-left p-2">
                                <table class="table table-bordered table-sm d-table">
                                    <tr>
                                        <th width="25%">Account Name</th>
                                        <td>
                                            <a href="{{$withdrawal->user->admin_url}}">{{$withdrawal->user->name}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Contact Details</th>
                                        <td>{{$withdrawal->user->email}} | {{$withdrawal->user->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Request Date</th>
                                        <td>{{date_time_for_humans($withdrawal->created_at)}}</td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Currency</th>
                                        <td>{{$withdrawal->account->currency->name}}</td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Payment Wallet</th>
                                        <td>{{$withdrawal->payment_wallet}}</td>
                                    </tr>
                                    @if($withdrawal->isPaid())
                                        <tr>
                                            <th width="25%">Date Processed</th>
                                            <td>{{date_time_for_humans($withdrawal->processed_at)}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%">Transaction Reference</th>
                                            <td>{{$withdrawal->getTransReference()}}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="my-3">
                                <p class="font-weight-bold text-center">
                                    <small><i class="fa fa-star"></i> Status</small>
                                    <br/>
                                    <span class="h4 {{$withdrawal->status()}}">{{$withdrawal->status()}}</span>
                                </p>
                                @if($withdrawal->isCancellable() or $withdrawal->isApproved())
                                    <hr/>
                                    <form method="POST" action="{{route('core.admin.withdrawal.manage')}}" class="ajax-form">
                                        <input type="hidden" name="ids[]" value="{{$withdrawal->id}}">

                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="pw">Payment Wallet</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="pw" name="payment_wallet[{{$withdrawal->id}}]" value="{{$withdrawal->payment_wallet}}">
                                                <p class="text-muted small">(Required if you wish to mark withdrawal as paid)</p>
                                            </div>
                                        </div>

                                        @if($withdrawal->isApproved())
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="progress_value">Progress Value (%)</label>
                                                <div class="col-md-9">
                                                    <input type="number" step="0.5" class="form-control" id="progress_value" name="progress_value[{{$withdrawal->id}}]"
                                                           value="{{$withdrawal->progress_value}}" required>
                                                    <p class="text-muted small">Value for Progress Bar</p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="progress_description">Progress Description</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="progress_description" name="progress_description[{{$withdrawal->id}}]"
                                                           value="{{$withdrawal->progress_description}}" required>
                                                    <br/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="trans_ref">Transaction Reference</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="trans_ref" name="trans_ref[{{$withdrawal->id}}]">
                                                    <p class="text-muted small">(Required if you wish to mark withdrawal as paid)</p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="action">Action</label>
                                            <div class="col-md-9">
                                                <select class="form-control" id="action" name="action">
                                                    <option></option>
                                                    <optgroup label="Process -->">
                                                        <option value="approve">Approve Request</option>
                                                        <option value="update_progress" @selected($withdrawal->status == Withdrawal::S_APPROVED)>Update Progress</option>
                                                        <option value="mark_as_paid">Mark as Paid</option>
                                                    </optgroup>
                                                    <optgroup label="<-- Reverse">
                                                        <option value="disapprove">Disapprove Request</option>
                                                        <option value="decline">Decline Request</option>
                                                        <option value="mark_as_failed">Mark as Failed (in case of data or processor errors)</option>
                                                    </optgroup>
                                                    <optgroup label="-- others --">
                                                        <option value="retract">Retract Funds (make this money disappear)</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>

                                        <br/>
                                        <div class="form-group text-center">
                                            <button type="submit" class="mt-5 btn btn-primary">Updated Withdrawal Info.</button>
                                        </div>
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
