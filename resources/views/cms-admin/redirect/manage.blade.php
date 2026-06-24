@extends('layouts.core.admin')

@section('scripts')
    @if(count($redirects) > 10)
        <script src="{{asset(mix('js/admin-datatables.js'))}}" type="text/javascript"></script>
    @endif
@endsection

@section('content')
    <div class="page-header">
        <h1>URL Redirects</h1>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.redirect.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading text-right">
                <div class="btn-group">
                    <select class="form-control d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                    <a class="btn btn-default btn-sm" href="{{route('cms.admin.redirect.create')}}">New...</a>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0"
                           class="table table-striped table-bordered table-hover"
                           id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="4%">##</th>
                            <th>Source URL</th>
                            <th>Destination URL</th>
                            <th width="10%">&hellip;</th>
                            <th width="4%"><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($redirects as $redirect)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><label for="i-{{$redirect->id}}">{{$redirect->slug}}</label></td>
                                <td><label for="i-{{$redirect->id}}">{{$redirect->destination}}</label></td>
                                <td class="center no-wrap">
                                    <a href="{{$redirect->edit_url}}"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="center">
                                    <input type="checkbox" name="ids[]" value="{{$redirect->id}}"
                                           id="i-{{$redirect->id}}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <strong>No redirects yet.</strong>
                                    <p><a href="{{route('cms.admin.redirect.create')}}">Create Redirects</a></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection
