@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>Manage Referral Bonuses <small>(Match: {{$result_count}}; Grand Total: {{$net_count}})</small></h3>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{route('core.admin.bonus.manage')}}" method="GET" enctype="application/x-www-form-urlencoded">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="search">Bonus Description <small>(search with associated Deposit-ID or User-ID)</small></label>
                            <input name="search" id="search" class="form-control" value="{{$filter['search']}}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="status" class="small">Status</label>
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
                            <input name="per_page" id="per_page" type="number" value="{{$filter['per_page']}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control btn-block btn-sm"><i class="fa fa-search">Search</i></button>
                    </div>
                </div>
            </form>
        </div>
        <form class="ajax-form" method="post" action="{{route('core.admin.bonus.manage')}}">
            <div class="panel-body">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="4em">##</th>
                            <th>Awarded to...</th>
                            <th>Description</th>
                            <th class="money">Amount</th>
                            <th>Awarded-at <i class="fa fa-calendar"></i></th>
                            <th>Released-at <i class="fa fa-calendar"></i></th>
                            <th>Status</th>
                            <th width="3em"><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = end_sn($results) @endphp
                        @foreach($results as $_result)
                            <tr>
                                <td>{{$sn--}}</td>
                                <td class="no-wrap"><a href="{{$_result->user->admin_url}}">{{$_result->user->uuid}} - {{$_result->user->name}}</a></td>
                                <td class="no-wrap"><label for="i-{{$_result->id}}">{{$_result->description}}</label></td>
                                <td class="no-wrap font-weight-bold money">{{$_result->amount()}}</td>
                                <td>{{date_time_for_humans($_result->created_at)}}</td>
                                <td>{{date_time_for_humans($_result->due_from)}}</td>
                                <td>{{$_result->status()}}</td>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$_result->id}}" id="i-{{$_result->id}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">
                    {{$results->appends($filter)->links()}}
                </div>
                <div class="pull-right">
                    <select class="form-control text-left d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="approve">Approve Bonuses</option>
                        <option value="cancel">Cancel Bonuses</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </form>
    </div>
@endsection
