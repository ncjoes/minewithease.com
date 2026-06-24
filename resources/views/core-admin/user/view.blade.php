@extends('layouts.core.admin')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body ml-md-5">
                    <p class="py-2">
                        <img src="{{$_user->photo_url}}" class="img-responsive img-thumbnail center-block"/>
                    </p>
                    <p class="h4">{{$_user->name}}</p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Registered on: </small>
                        <strong>{{date_time_for_humans($_user->created_at)}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Referred by: </small>
                        <strong>{!! is_object($_user->referrer) ? '<a href="'.$_user->referrer->admin_url.'">'.$_user->referrer->name.'</a>' : 'nil' !!}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Country:</small>
                        <strong>{{$_user->country->name}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Account UUID:</small>
                        <strong>{{$_user->uuid}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Email Address:</small>
                        <strong>{{$_user->email}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Mobile Number:</small>
                        <strong>{{$_user->phone}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Password:</small>
                        <code>{{$_user->password}}</code>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Account Status:</small>
                        <strong>{{$_user->status()}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Last Login: </small>
                        <strong>{{date_time_for_humans($_user->last_login)}}</strong>
                    </p>
                    <p class="data-row">
                        <small class="d-inline-block w-25">Last Updated: </small>
                        <strong>{{date_time_for_humans($_user->updated_at)}}</strong>
                    </p>

                    <p class="h5 mt-5">Wallet Addresses</p>
                    @foreach($_user->accounts as $account)
                        <div class="input-group mb-2">
                            <div class="input-group-addon">
                                <label for="w-{{$account->id}}">
                                    <img src="{{$account->photo_url()}}" style="width: 1rem;" alt="{{$account->name}}">
                                </label>
                            </div>
                            <input type="text" class="form-control" id="w-{{$account->id}}" readonly value="{{$account->wallet_address}}">
                        </div>
                    @endforeach
                    <hr/>

                    <div class="list-group">
                        <h6 class="list-group-item h6 font-bold small"><i class="fa fa-list-alt"></i> Quick links</h6>
                        <a class="list-group-item small"
                           href="{{route('core.admin.deposit.manage',['search'=>$_user->uuid])}}">
                            <i class="fa fa-credit-card"></i> Deposit History
                        </a>
                        <a class="list-group-item small"
                           href="{{route('core.admin.portfolio.manage',['search'=>$_user->uuid])}}">
                            <i class="fa fa-credit-card"></i> Portfolio History
                        </a>
                        <a class="list-group-item small"
                           href="{{route('core.admin.withdrawal.manage',['search'=>$_user->uuid])}}">
                            <i class="fa fa-credit-card"></i> Withdrawal History
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Account Summary</strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card">
                                <strong>Net Deposits:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getTotalDepositsStr()}}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card text-blue">
                                <strong>Total Investments:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getTotalPortfoliosStr()}}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card text-green">
                                <strong>Bonuses Earned:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getRefBonusStr()}}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card">
                                <strong>Net Withdrawals:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getTotalWithdrawalsStr()}}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card text-orange">
                                <strong>Active Portfolios:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getActivePortfoliosStr()}}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <p class="data-card">
                                <strong>Account Balance:</strong><br/>
                                <strong class="h1 money no-wrap" style="font-size: 2em;">{{$_user->getTotalBalanceStr()}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>

            <div class="panel panel-default">
                <div class="panel-body">

                    <ul role="tablist" class="nav nav-tabs" id="user-data-tabs">
                        <li class="active"><a data-toggle="tab" role="tab" href="#transactions">Transactions History</a></li>
                        <li><a data-toggle="tab" role="tab" href="#investments">Investments</a></li>
                        <li><a data-toggle="tab" role="tab" href="#community">Community</a></li>
                        <li><a data-toggle="tab" role="tab" href="#identity">Identity Verification</a></li>
                        <li><a data-toggle="tab" role="tab" href="#advanced">Advanced</a></li>
                    </ul>

                    <div class="tab-content" id="user-data-panels">
                        <div id="transactions" class="tab-pane tabs-up fade panel panel-default active in">
                            <div class="panel-body">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Create Transaction Record</strong></div>
                                    <form action="{{route('core.admin.user.reconcile-account', ['user'=>$_user->uuid])}}" class="ajax-form panel-body" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="account" class="control-label">Sub-Account</label>
                                                    <select name="account_id" id="account" class="form-control" required>
                                                        <option></option>
                                                        @foreach($_user->accounts as $account)
                                                            <option value="{{$account->id}}">{{$account->currency->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="action" class="control-label">Action</label>
                                                    <select name="action" id="action" class="form-control" required>
                                                        <option></option>
                                                        <option value="credit">Credit Account</option>
                                                        <option value="debit">Debit Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="description" class="control-label">Description/Narration</label>
                                                <input type="text" name="description" id="description" class="form-control" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="amount" class="control-label">Amount</label>
                                                <input name="amount" id="amount" type="number" min="0" step="any" class="form-control" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="date" class="control-label">Date and Time</label>
                                                    <div class="">
                                                        <input name="date-time" id="date" required type="datetime-local" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <br/>
                                                <button type="submit" class="btn btn-primary form-control btn-block btn-sm">
                                                    <i class="fa fa-check"> Create Transaction</i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr/>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="9"> Transactions (Net Total: {{$transactions->total()}})</th>
                                        </tr>
                                        <tr>
                                            <th width="4%">##</th>
                                            <th>Item</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th class="money">Amount</th>
                                            <th class="money">New Acc. Bal.</th>
                                            <th>Date-Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $sn = end_sn($transactions) @endphp
                                        @forelse($transactions as $transaction)
                                            <tr class="{{$transaction->isCredit()? 'text-green' : 'text-warning'}}">
                                                <td>{{$sn--}}</td>
                                                <td class="no-wrap">{{$transaction->itemDesc()}}</td>
                                                <td class="no-wrap">{{$transaction->effect()}}</td>
                                                <td>{{$transaction->description}}</td>
                                                <td class="money no-wrap">{{$transaction->amount()}}</td>
                                                <td class="money no-wrap">{{$transaction->newBalance()}}</td>
                                                <td class="no-wrap">{{date_time_for_humans($transaction->created_at)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <strong>
                                                        This user has no transactions yet.
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-center">{{$transactions->links()}}</p>
                            </div>
                        </div>
                        <div id="investments" class="tab-pane tabs-up fade panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th colspan="5" class="text-center">Net Profits/Interests Earned</th>
                                        </tr>
                                        <tr>
                                            <th>Investment Plan</th>
                                            <th>Number of Portfolios</th>
                                            <th>Amount Invested</th>
                                            <th>Profits Earned</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($packages as $package)
                                            <tr>
                                                <td>{{$package->name}}</td>
                                                <td>{{$_user->getPortfolios($package)->count()}}</td>
                                                <td>{{$_user->getPackageInvestmentAmountStr($package)}}</td>
                                                <td>{{$_user->getPackageInvestmentProfitStr($package)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr/>

                                <div class="panel panel-primary">
                                    <div class="panel-heading"><strong>Create New Investment Portfolio</strong></div>
                                    <form action="{{route('core.admin.user.reconcile-account',['user'=>$_user->uuid])}}" class="ajax-form panel-body" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="action" class="control-label">Action</label>
                                                    <select name="action" id="action" class="form-control" required>
                                                        <option></option>
                                                        <option value="credit">Credit Account</option>
                                                        <option value="debit">Debit Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="description" class="control-label">Description/Narration</label>
                                                <input type="text" name="description" id="description" class="form-control" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="amount" class="control-label">Amount</label>
                                                <input name="amount" id="amount" type="number" min="0" step="any" class="form-control" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="date" class="control-label">Date and Time</label>
                                                    <div class="">
                                                        <input name="date-time" id="date" required type="datetime-local" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <br/>
                                                <button type="submit" class="btn btn-primary form-control btn-block btn-sm">
                                                    <i class="fa fa-check"> Create Transaction</i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr/>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="9">Investment Portfolios (Net Total: {{$portfolios->total()}})</th>
                                        </tr>
                                        <tr>
                                            <th width="2%" class="text-center">##</th>
                                            <th>Portfolio UUID</th>
                                            <th>Package</th>
                                            <th class="money">Amount</th>
                                            <th>Date Created</th>
                                            <th>Expiring-By</th>
                                            <th>Status</th>
                                            <th>&hellip;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $sn = end_sn($portfolios) @endphp
                                        @foreach($portfolios as $_result)
                                            <tr class="@if($_result->isActive()) text-green @endif">
                                                <td class="text-center">{{$sn--}}</td>
                                                <td><label for="i-{{$_result->id}}">{{$_result->uuid}}</label></td>
                                                <td>{{$_result->package->name}}</td>
                                                <td class="money font-weight-bold">{{$_result->amount()}}</td>
                                                <td>{{date_time_for_humans($_result->created_at)}}</td>
                                                <td>{{date_time_for_humans($_result->expires_at)}}</td>
                                                <td>{{$_result->status()}}</td>
                                                <td class="center"><a href="{{$_result->admin_url}}"><i class="fa fa-eye"></i>Details</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-center">{{$portfolios->links()}}</p>
                            </div>
                        </div>
                        <div id="community" class="tab-pane tabs-up fade panel panel-default">
                            <div class="panel-body">
                                <p class="text-center">
                                    <strong>Referrer:</strong><br/>
                                    <strong class="h2 money no-wrap" style="font-size: 2em;">
                                        {!! is_object($_user->referrer) ? '<a href="'.$_user->referrer->admin_url.'">'.$_user->referrer->name.'</a>' : 'nil' !!}
                                    </strong>
                                </p>
                                <hr/>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="9"> Referrals (Net Total: {{count($referrals)}})</th>
                                        </tr>
                                        <tr>
                                            <th width="4em">##</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Phone</th>
                                            <th>Total-Investments</th>
                                            <th width="10%">Downlines</th>
                                            <th width="10%">Country</th>
                                            <th width="15%">Date/Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($referrals as $referral)
                                            <tr>
                                                <td>{{$loop->index+1}}</td>
                                                <td><a href="{{$referral->admin_url}}">{{$referral->name}}</a></td>
                                                <td>{{$referral->email}}</td>
                                                <td>{{$referral->phone}}</td>
                                                <td class="money">{{$referral->getTotalPortfoliosStr()}}</td>
                                                <td>{{$referral->referrals()->count()}}</td>
                                                <td>{{$referral->country->name}}</td>
                                                <td>{{date_time_for_humans($referral->created_at)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <strong>
                                                        This user has no referrals yet.
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <hr/>
                                <div class="panel panel-warning">
                                    <div class="panel-heading"><h5>Set Custom Referral Bonus % (Affiliate Rate) for {{$_user->name}}</h5></div>
                                    <div class="panel-body">
                                        <form class="form-horizontal ajax-form" method="post" action="{{route('core.admin.user.set-affiliate-rate',['user'=>$_user->uuid])}}">
                                            <fieldset>
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label for="rbr" class="control-label">Custom Affiliate Rate</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="rbr" name="affiliate_rates" placeholder="3,2"
                                                               value="{{$_user->getSetting('affiliate_rates','')}}">
                                                        <span class="text-muted small">e.g. 3,2 for 3%,2% rate</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <button type="submit" class="btn form-control btn-primary">Update Rates</button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="identity" class="tab-pane tabs-up fade panel panel-info">
                            <div class="panel-body">
                                <p class="center-block">
                                    <img src="{{$_user->identification_url}}" alt="{{$_user->identification_url}}" class="center-block img-fluid img-responsive">
                                </p>
                                <div class="text-center">
                                    @if($_user->needsVerification())
                                        <form method="POST" class="ajax-form form-inline" style="display: inline;" action="{{route('core.admin.user.manage')}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="action" value="mark_as_verified">
                                            <input type="hidden" name="ids[]" value="{{$_user->id}}">
                                            <button type="submit" class="btn btn-sm btn-success">Mark Profile as Verified</button>
                                        </form>
                                    @endif
                                    @if($_user->hasFilledProfileData())
                                        <form method="POST" class="ajax-form form-inline" style="display: inline;" action="{{route('core.admin.user.manage')}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="action" value="reject_documents">
                                            <input type="hidden" name="ids[]" value="{{$_user->id}}">
                                            <button type="submit" class="btn btn-sm btn-danger">Reject Documents</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="advanced" class="tab-pane tabs-up fade panel panel-warning">
                            <div class="panel-body">
                                <form class="form-horizontal ajax-form mb-5 pb-5" method="post" action="{{route('core.admin.user.account-settings',['user'=>$_user->uuid])}}">
                                    @csrf
                                    <fieldset>
                                        @foreach($settings as $setting)
                                            @include('_components.setting-field')
                                        @endforeach

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
