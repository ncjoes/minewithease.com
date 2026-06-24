@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h3>
            Content Categories <small>FAQs &amp; Blog Posts</small>
            <a href="{{route('cms.admin.category.create')}}" class="btn btn-success pull-right">New Category...</a>
        </h3>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.category.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="basic-datatable">
                        <thead>
                        <tr>
                            <th width="2%">##</th>
                            <th>Type</th>
                            <th>Caption</th>
                            <th>Description</th>
                            <th>&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = start_sn($categories) @endphp
                        @forelse($categories as $category)
                            <tr class="@if($category->trashed()) danger @endif">
                                <td>{{$sn++}}</td>
                                <td>{{strtoupper($category->type_str)}}</td>
                                <td><label for="i-{{$category->id}}">{{$category->title}}</label></td>
                                <td>{{clamp($category->description, 150)}}</td>
                                <td class="center">
                                    <a href="{{$category->edit_url}}"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="center">
                                    <input type="checkbox" name="ids[]" value="{{$category->id}}" id="i-{{$category->id}}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <strong>No categories defined yet.</strong>
                                    <p><a href="{{route('cms.admin.category.create')}}">Add Category</a></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <div class="pull-left">{{$categories->links()}}</div>
                <div class="pull-right">
                    <select class="form-control text-left d-inline btn btn-sm" style="width: auto;" name="action">
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
