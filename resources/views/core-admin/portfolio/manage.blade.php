@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>Manage Investment Portfolios <small>(Match: {{$result_count}}; Grand Total: {{$net_count}})</small></h3>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{route('core.admin.portfolio.manage')}}" method="GET"
                  enctype="application/x-www-form-urlencoded">
                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="search">Portfolio ID
                                <small>(search with full Portfolio-ID or User-ID)</small>
                            </label>
                            <input name="search" id="search" class="form-control" value="{{$filter['search']}}">
                        </div>
                    </div>

                    <div class="col-sm-5 col-lg-2">
                        <div class="form-group">
                            <label for="package" class="small">Package</label>
                            <select name="package" id="package" class="form-control">
                                <option></option>
                                @foreach($packages as $package)
                                    <option value="{{$package->id}}" class="cur-{{$package->currency_id}}" @if($filter['package']==$package->id) selected @endif>
                                        {{$package->name}}
                                    </option>
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
                    <div class="col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="per_page" class="small">Rows Per Page</label>
                            <input name="per_page" id="per_page" type="number" value="{{$filter['per_page']}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2 col-lg-2">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control btn-block btn-sm">
                            <i class="fa fa-search">Search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <form class="ajax-form" method="post" action="{{route('core.admin.portfolio.manage')}}">
            <div class="panel-body">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="4%">##</th>
                            <th>Portfolio UUID</th>
                            <th>Package</th>
                            <th>Account Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="money">Amount</th>
                            <th>Date Created</th>
                            <th>Expiring-By</th>
                            <th>Status</th>
                            <th>&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($results) @endphp
                        @foreach($results as $_result)
                            <tr class="@if($_result->isActive()) text-green @endif">
                                <td>{{$sn--}}</td>
                                <td><label for="i-{{$_result->id}}">{{$_result->uuid}}</label></td>
                                <td>{{$_result->package->name}}</td>
                                <td><a href="{{$_result->user->admin_url}}">{{$_result->user->name}}</a></td>
                                <td>{{$_result->user->email}}</td>
                                <td>{{$_result->user->phone}}</td>
                                <td class="money font-weight-bold">{{$_result->amount()}}</td>
                                <td>{{date_time_for_humans($_result->created_at)}}</td>
                                <td>{{date_time_for_humans($_result->expires_at)}}</td>
                                <td>{{$_result->status()}}</td>
                                <td class="center"><a href="{{$_result->admin_url}}"><i class="fa fa-eye"></i>Details</a></td>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$_result->id}}" id="i-{{$_result->id}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">{{$results->appends($filter)->links()}}</div>
                <div class="pull-right">
                    <select class="form-control d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="close">
                            Close (Refund capital to user and prevent further interests)
                        </option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </form>
    </div>
@endsection
