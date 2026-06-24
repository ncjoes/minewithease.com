@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    @include('_components.wysiwyg-editor');
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Create Blog Post</div>
        <div class="panel-body">

            <form class="form-horizontal ajax-form" method="POST" action="{{route('cms.admin.post.create')}}">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="A short and captivating name for your post">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slug" class="col-sm-3 control-label">Slug/URI</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="slug" name="slug" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abstract" class="col-sm-3 control-label">Abstract</label>
                            <div class="col-sm-9">
                                <textarea id="abstract" name="abstract" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea id="content" name="content" class="form-control wysihtml"
                                          style="min-height: 30em;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="authors" class="col-sm-3 control-label">Authors</label>
                            <div class="col-sm-9">
                                <select name="authors[]" id="authors" multiple
                                        class="form-control form-control-sm chosen-select">
                                    @foreach($editors as $editor)
                                        <option value="{{$editor->id}}">{{$editor->name()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="categories" class="col-sm-3 control-label">Categories</label>
                            <div class="col-sm-9">
                                <select name="categories[]" id="categories" multiple
                                        class="form-control form-control-sm chosen-select">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="published_at">Date &amp; Time</label>
                            <div class="col-sm-9">
                                <div class="input-group date" id="datetime-picker">
                                    <input type="text" name="published_at" id="published_at" required
                                           class="form-control">
                                    <span class="input-group-addon">
                                        <span class="fa-calendar fa"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Create Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
