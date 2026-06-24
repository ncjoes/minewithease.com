@extends('layouts.core.admin')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Create Content Category
                    <a href="{{route('cms.admin.category.manage')}}" class="btn btn-sm btn-primary pull-right">All Categories</a>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal ajax-form" method="post" action="{{route('cms.admin.category.create')}}">
                        <div class="form-group">
                            <label for="type" class="col-sm-3 control-label">Category Type</label>
                            <div class="col-sm-9">
                                <select name="type" id="type" class="form-control form-control-sm" required>
                                    @foreach($types as $key=>$label)
                                        <option value="{{$key}}">{{$label}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" required class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardinality" class="col-sm-3 control-label">Cardinality</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_menu" value="1">
                                    <i class="fa"></i>
                                    Show in menu?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_footer" value="1">
                                    <i class="fa"></i>
                                    Show in footer?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="use_index" value="1">
                                    <i class="fa"></i>
                                    Use Index?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Create Category</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
