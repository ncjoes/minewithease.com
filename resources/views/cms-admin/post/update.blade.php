@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    <script src="{{asset(mix('js/admin-img_cropper.js'))}}" type="text/javascript"></script>
    @include('_components.wysiwyg-editor');
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Update Blog Post</div>
        <div class="panel-body">

            <form class="form-horizontal ajax-form" method="POST" action="{{$post->edit_url}}">
                <div class="row">
                    <div class="col-md-8">
                        <div class="pic-container text-center">
                            <div class="padd-xs">
                                <img class="img-responsive center-block cropper-destination cover_photo"
                                     itemprop="image"
                                     src="{{$post->cover_url}}" alt="{{$post->title}}"/>
                            </div>
                            <div class="after text-center" style="padding-top: 2em;">
                                <label class="btn btn-primary" for="cover_photo">
                                    <span class="fa fa-camera"></span>
                                    <strong>
                                        @if(empty($post->cover_photo))
                                            Upload Cover Image
                                        @else
                                            Change Cover Image
                                        @endif
                                    </strong>
                                </label>
                                <input class="hidden cropper-source" id="cover_photo" type="file"
                                       data-handler="{{route('cms.admin.post.image', ['post' => $post->slug])}}"
                                       data-width="770" data-height="434" data-attribute="cover_photo"
                                       data-preview="{{$post->cover_url}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="pic-container text-center">
                            <div class="padd-xs text-center">
                                <img class="img-responsive center-block cropper-destination thumb_photo"
                                     src="{{$post->thumb_url}}" alt="{{$post->title}}" style="max-width: 400px;"/>
                            </div>
                            <div class="after text-center" style="padding-top: 2em;">
                                <label class="btn btn-primary" for="thumb_photo">
                                    <span class="fa fa-camera"></span>
                                    <strong>
                                        @if(empty($post->thumb_photo))
                                            Upload Thumbnail Image
                                        @else
                                            Change Thumbnail Image
                                        @endif
                                    </strong>
                                </label>
                                <input class="hidden cropper-source" id="thumb_photo" type="file"
                                       data-handler="{{route('cms.admin.post.image', ['post' => $post->slug])}}"
                                       data-width="370" data-height="270" data-attribute="thumb_photo"
                                       data-preview="{{$post->thumb_url}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="A short and captivating name for your post"
                                       value="{{$post->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="slug" class="col-sm-3 control-label">Slug/URI</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="slug" name="slug" required
                                       value="{{$post->slug}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abstract" class="col-sm-3 control-label">Abstract</label>
                            <div class="col-sm-9">
                                <textarea id="abstract" name="abstract"
                                          class="form-control">{{$post->abstract}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea id="content" name="content" required class="form-control wysihtml"
                                          style="min-height: 30em; width: 100%;">{!! $post->content !!}</textarea>
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
                                        <option @if($post->authors->contains($editor)) selected @endif
                                        value="{{$editor->id}}">{{$editor->name()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="categories" class="col-sm-3 control-label">Categories</label>
                            <div class="col-sm-9">
                                <select name="categories[]" id="categories"
                                        class="form-control form-control-sm chosen-select"
                                        multiple>
                                    @foreach($categories as $category)
                                        <option @if($post->categories->contains($category)) selected @endif
                                        value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="published_at">Date &amp; Time</label>
                            <div class="col-sm-9">
                                <div class="input-group date" id="datetimepicker">
                                    <input type="text" class="form-control" id="published_at" name="published_at"
                                           value="{{date_time_for_humans($post->published_at, 'm/d/Y h:i A')}}">
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="published" class="col-sm-3 control-label">Published?</label>
                            <div class="col-sm-9">
                                <div class="switch-button success sm showcase-switch-button">
                                    <input id="published" name="published" type="checkbox"
                                           @if($post->isPublished()) checked @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    @include('_components.image-previewer')
@endsection
