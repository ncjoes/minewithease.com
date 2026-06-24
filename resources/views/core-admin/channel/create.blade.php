@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Create Payment Channel
                    <a href="{{route('core.admin.channel.manage')}}" class="btn btn-sm btn-primary pull-right">Manage Channels</a>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal ajax-form" method="post"
                          action="{{route('core.admin.channel.create')}}">
                        <div class="form-group">
                            <label for="currency" class="col-sm-3 control-label">Local Currency</label>
                            <div class="col-sm-9">
                                <select name="currency" id="currency" class="form-control form-control-sm" required>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="website" class="col-sm-3 control-label">Website</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="website" name="website" placeholder="Payment processor's website">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" required class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_wallet" class="col-sm-3 control-label">Payment Wallet</label>
                            <div class="col-sm-9">
                                <input id="payment_wallet" name="payment_wallet" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_amount" class="col-sm-3 control-label">Min. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="100" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_amount" class="col-sm-3 control-label">Max. Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="10000" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="split_amount" class="col-sm-3 control-label">Split Amount</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="split_amount" name="split_amount" placeholder="10" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="is_active" value="1">
                                    <i class="fa"></i> Is Active?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="for_inflow" value="1">
                                    <i class="fa"></i>
                                    For Inflow?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="for_outflow" value="1">
                                    <i class="fa"></i>
                                    For Outflow?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-success">Create Payment Channel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
