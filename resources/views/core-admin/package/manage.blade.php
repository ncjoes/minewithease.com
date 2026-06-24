@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>
            Manage Investment Packages
            <small>(Match: {{$result_count}}; Grand Total: {{$net_count}})</small>
            <a href="{{route('core.admin.package.create')}}" class="btn btn-success pull-right">New...</a>
        </h3>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{route('core.admin.package.manage')}}" method="GET"
                  enctype="application/x-www-form-urlencoded">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="search" class="small">Package Name</label>
                            <input name="search" id="search" class="form-control" value="{{$filter['search']}}">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="status" class="small">Active?</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($statuses as $key=>$label)
                                    <option value="{{$key}}" @if($filter['status']==$key) selected @endif>{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 d-none d-sm-inline-block">
                        <div class="form-group">
                            <label for="per_page" class="small">Rows Per Page</label>
                            <input name="per_page" id="per_page" type="number" value="{{$filter['per_page']}}"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control btn-block btn-sm">
                            <i class="fa fa-search">Search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <form class="ajax-form" method="post" action="{{route('core.admin.package.manage')}}">
            <div class="panel-body">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="4em">##</th>
                            <th>Name</th>
                            <th class="money">Min. Amount</th>
                            <th class="money">Max. Amount</th>
                            <th>Interest Rate (% / days)</th>
                            <th>Duration (days)</th>
                            <th>Affiliate Bonus</th>
                            <th>Is Active?</th>
                            <th>&hellip;</th>
                            <th width="2em"><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $_result)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><label for="i-{{$_result->id}}"><img src="{{$_result->photo_url}}" style="max-width: 1.5em;"> {{$_result->name}}</label></td>
                                <td class="money">{{$_result->minAmount()}}</td>
                                <td class="money">{{$_result->maxAmount()}}</td>
                                <td>{{$_result->getInterestRateStr()}} / {{$_result->interest_interval}} days(s)</td>
                                <td>Min: {{$_result->min_duration}}days; Max: {{$_result->max_duration}}days;</td>
                                <td>{{implode('% - ',explode(',',$_result->referral_bonus_rate)).'%'}}</td>
                                <td>{{$_result->status()}}</td>
                                <td class="center"><a href="{{$_result->edit_url}}"><i class="fa fa-eye"></i>Details</a></td>
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
                    <select class="form-control text-left d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="activate">Activate</option>
                        <option value="deactivate">De-Activate</option>
                        <option value="delete">Delete Forever</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </form>
    </div>
@endsection
