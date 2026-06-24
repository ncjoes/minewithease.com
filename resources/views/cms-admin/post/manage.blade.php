@extends('layouts.core.admin')

@section('scripts')
    @if(count($posts) > 10)
        <script src="{{asset(mix('js/admin-datatables.js'))}}" type="text/javascript"></script>
    @endif
@endsection

@section('content')
    <div class="page-header">
        <h1>Blog Posts
            <small>All Categories</small>
        </h1>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.post.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading text-right">
                <div class="btn-group">
                    <select class="form-control d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="trash">Trash</option>
                        <option value="recycle">Recycle</option>
                        <option value="delete">Delete Forever</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                    <a class="btn btn-default btn-sm" href="{{route('cms.admin.post.create')}}">New...</a>
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
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>&hellip;</th>
                            <th><i class="fa fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($posts as $post)
                            <tr class="@if($post->trashed()) danger @endif">
                                <td>{{$loop->index+1}}</td>
                                <td><label for="i-{{$post->id}}">{{$post->title}}</label></td>
                                <td>{{clamp($post->categoriesStr(),150)}}</td>
                                <td>{{date_time_for_humans($post->created_at)}}</td>
                                <td class="center">
                                    <a href="{{$post->edit_url}}"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="center">
                                    <input type="checkbox" name="ids[]" value="{{$post->id}}" id="i-{{$post->id}}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <strong>No posts yet.</strong>
                                    <p><a href="{{route('cms.admin.post.create')}}">Create the First Blog Post</a></p>
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
