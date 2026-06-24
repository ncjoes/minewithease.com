@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/photo-cropper.js'))}}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Create Content Category
                    <a href="{{route('cms.admin.category.manage')}}" class="btn btn-sm btn-primary pull-right">All Categories</a>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal ajax-form" method="post" action="{{$category->edit_url}}">

                        <div class="form-group">
                            <label for="type" class="col-sm-3 control-label">Category Type</label>
                            <div class="col-sm-9">
                                <select name="type" id="type" class="form-control form-control-sm" required>
                                    <option>--select type--</option>
                                    @foreach($types as $key=>$label)
                                        <option value="{{$key}}"
                                                @if($key==$category->type) selected @endif>{{$label}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" required value="{{$category->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slug" class="col-sm-3 control-label">Slug</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug/URI" required value="{{$category->slug}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" class="form-control" required>{{$category->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardinality" class="col-sm-3 control-label">Cardinality</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" placeholder="0" value="{{$category->cardinality}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_menu" value="1" @if($category->show_in_menu) checked @endif>
                                    <i class="fa"></i>
                                    Show in menu?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_footer" value="1" @if($category->show_in_footer) checked @endif>
                                    <i class="fa"></i>
                                    Show in footer?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="use_index" value="1" @if($category->use_index) checked @endif>
                                    <i class="fa"></i>
                                    Use Index?
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Update Category</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('_components.image-previewer')
@endsection
