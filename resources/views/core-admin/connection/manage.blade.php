 @extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>Manage Supported Countries <small>(Match: {{$result_count}}; Grand Total: {{$net_count}})</small></h3>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{route('core.admin.connection.manage')}}" method="GET" enctype="application/x-www-form-urlencoded">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="search" class="small">Wallet Email</label>
                            <input name="search" id="search" class="form-control" value="{{$filter['search']}}">
                        </div>
                    </div>

                    <!--
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="status" class="small">Active?</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($statuses as $key=>$label)
                                    <option value="{{$key}}" @if($filter['status']==$key) selected @endif>{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    -->

                    <div class="col-sm-3 d-none d-sm-inline-block">
                        <div class="form-group">
                            <label for="per_page" class="small">Rows Per Page</label>
                            <input name="per_page" id="per_page" type="number" value="{{$filter['per_page']}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control btn-block btn-sm"><i class="fa fa-search">Search</i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="panel-body">
            <form class="ajax-form" method="post" action="{{route('core.admin.connection.manage')}}">
                {{csrf_field()}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="4%">##</th>
                            <th>UID</th>
                            <th>Wallet Type</th>
                            <th>Data...</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $_result)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><label for="i-{{$_result->id}}">{{$_result->uuid}}</label></td>
                                <td>{{$_result->type}}</td>
                                <th><code>{{ json_encode($_result->data) }}</code></th>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$_result->id}}" id="i-{{$_result->id}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <div class="panel-footer clearfix">
            <div class="pull-left">{{$results->appends($filter)->links()}}</div>
            <div class="pull-right">
                <!--
                <select class="form-control text-left d-inline btn btn-sm" style="width: auto;" name="action">
                    <option value="activate">Activate</option>
                    <option value="deactivate">De-Activate</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Go</button>
                -->
            </div>
        </div>
    </div>
@endsection
