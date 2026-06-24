@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>Manage User Accounts <small>(Match: {{$result_count}}; Grand Total: {{$net_count}})</small></h3>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{route('core.admin.user.manage')}}" method="GET" enctype="application/x-www-form-urlencoded">
                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="search">User ID/Emails <small>separate multiple items with commas</small></label>
                            <input name="search" id="search" class="form-control" value="{{$filter['search']}}">
                        </div>
                    </div>

                    <div class="col-sm-4 col-lg-2">
                        <div class="form-group">
                            <label for="country" class="small">Country</label>
                            <select name="country" id="country" class="form-control">
                                <option></option>
                                @foreach($countries as $country)
                                    <option value="{{$country->iso2}}" @if($filter['country']==$country->iso2) selected @endif>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3 col-lg-2">
                        <div class="form-group">
                            <label for="status" class="small">Status</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($statuses as $key=>$label)
                                    <option value="{{$key}}" @if($filter['status']==$key) selected @endif>{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3 col-lg-2">
                        <div class="form-group">
                            <label for="per_page" class="small">Rows Per Page</label>
                            <input name="per_page" id="per_page" type="number" value="{{$filter['per_page']}}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-2 col-lg-2">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control btn-block btn-sm"><i class="fa fa-search">Search</i></button>
                    </div>
                </div>
            </form>
        </div>
        <form class="ajax-form" method="post" action="{{route('core.admin.user.manage')}}">
            <div class="panel-body">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="4%">##</th>
                            <th>Account UUID</th>
                            <th>Country</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="money">Net-Deposits</th>
                            <th class="money">Net-Withdrawals</th>
                            <th class="money">Active-Stakes</th>
                            <th class="money">Total-Balance</th>
                            <th>Affiliate Bonus</th>
                            <th>Date Joined</th>
                            <th>Status</th>
                            <th>&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($users) @endphp
                        @foreach($users as $_user)
                            <tr>
                                <td>{{$sn--}}</td>
                                <td><label for="i-{{$_user->id}}">{{$_user->uuid}}</label></td>
                                <td>{{$_user->country->name}}</td>
                                <td><label for="i-{{$_user->id}}">{{$_user->name}}</label></td>
                                <td>{{$_user->email}}</td>
                                <td>{{$_user->phone}}</td>
                                <td class="money font-weight-bold">{{$_user->getTotalDepositsStr()}}</td>
                                <td class="money font-weight-bold">{{$_user->getTotalWithdrawalsStr()}}</td>
                                <td class="money font-weight-bold">{{$_user->getActivePortfoliosStr()}}</td>
                                <td class="money font-weight-bold">{{$_user->getTotalBalanceStr()}}</td>
                                <td>{{$_user->isSpecialAffiliate() ? implode('% - ',$_user->getAffiliateRates()).'%' : 'Standard'}}</td>
                                <td>{{date_time_for_humans($_user->created_at)}}</td>
                                <td>{{$_user->status()}}</td>
                                <td class="center"><a href="{{$_user->admin_url}}"><i class="fa fa-eye"></i> Details</a></td>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$_user->id}}" id="i-{{$_user->id}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">
                    {{$users->appends($filter)->links()}}
                </div>
                <div class="pull-right">
                    <select class="form-control d-inline btn btn-sm text-left" style="width: auto;" name="action">
                        <optgroup label="Access Control">
                            <option value="activate">Activate</option>
                            <option value="deactivate">De-Activate</option>
                            <option value="disable">Disable</option>
                        </optgroup>
                        <optgroup label="KYC Verification">
                            <option value="mark_as_verified">Mark as Verified</option>
                            <option value="reject_documents">Reject Documents</option>
                        </optgroup>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </form>
    </div>
@endsection
