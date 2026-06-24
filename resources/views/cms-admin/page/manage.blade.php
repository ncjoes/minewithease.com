@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3 class="clearfix">Manage Website Pages <a href="{{route('cms.admin.page.create')}}" class="btn btn-success pull-right">New Page...</a></h3>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.page.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="2%">##</th>
                            <th>Title</th>
                            <th>Summary</th>
                            <th>Created_at</th>
                            <th>Status</th>
                            <th>&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = start_sn($all_pages) @endphp
                        @forelse($all_pages as $page)
                            <tr class="@if($page->trashed()) danger @endif">
                                <td>{{$sn++}}</td>
                                <td><label for="i-{{$page->id}}">{{$page->title}}</label></td>
                                <td>{{clamp(trim($page->summary), 180)}}</td>
                                <td>{{date_time_for_humans($page->created_at)}}</td>
                                <td>{{$page->isPublished()?'Published':'Draft'}}</td>
                                <td class="center"><a href="{{$page->edit_url}}"><i class="fa fa-edit"></i> Edit</a></td>
                                <td class="center"><input type="checkbox" name="ids[]" value="{{$page->id}}" id="i-{{$page->id}}"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <strong>No pages yet.</strong>
                                    <p><a href="{{route('cms.admin.page.create')}}">Create Page</a></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">{{$all_pages->links()}}</div>
                <div class="pull-right">
                    <select class="form-control d-inline btn text-left btn-sm" style="width: auto;" name="action">
                        <option value="trash">Delete</option>
                        <option value="recycle">Restore</option>
                        <option value="delete">Delete Permanently</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
        </div>
    </form>
@endsection
