@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    <script src="{{asset(mix('js/admin-img_cropper.js'))}}" type="text/javascript"></script>
    @include('_components.wysiwyg-editor');
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Update Website Page
                    <a href="{{route('cms.admin.page.manage')}}" class="btn btn-sm btn-primary pull-right">All Pages</a>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal ajax-form" method="POST" action="{{$page->edit_url}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pic-container text-center">
                                    <div class="padd-xs">
                                        <img class="img-responsive center-block cropper-destination cover_photo"
                                             itemprop="image" src="{{$page->cover_url}}" alt="{{$page->title}}"/>
                                    </div>
                                    <div class="after text-center" style="padding-top: 2em;">
                                        <label class="btn btn-primary" for="cover_photo">
                                            <span class="fa fa-camera"></span>
                                            <strong>
                                                @if(empty($page->cover_photo))
                                                    Upload Cover Image
                                                @else
                                                    Change Cover Image
                                                @endif
                                            </strong>
                                        </label>
                                        <input class="hidden cropper-source" id="cover_photo" type="file"
                                               data-handler="{{route('cms.admin.page.image', ['page' => $page->slug])}}"
                                               data-width="1414" data-height="435" data-attribute="cover_photo"
                                               data-preview="{{$page->cover_url}}"/>
                                    </div>
                                </div>
                            </div>
                        <!--
                            <div class="col-md-4">
                                <div class="pic-container text-center">
                                    <div class="padd-xs text-center">
                                        <img class="img-responsive center-block cropper-destination thumb_photo"
                                             src="{{$page->thumb_url}}" alt="{{$page->title}}" style="max-width: 400px;"/>
                                    </div>
                                    <div class="after text-center" style="padding-top: 2em;">
                                        <label class="btn btn-primary" for="thumb_photo">
                                            <span class="fa fa-camera"></span>
                                            <strong>
                                                @if(empty($page->thumb_photo))
                            Upload Thumbnail Image
@else
                            Change Thumbnail Image
@endif
                                </strong>
                            </label>
                            <input class="hidden cropper-source" id="thumb_photo" type="file"
                                   data-handler="{{route('cms.admin.page.image', ['page' => $page->slug])}}"
                                               data-width="600" data-height="426" data-attribute="thumb_photo"
                                               data-preview="{{$page->thumb_url}}"/>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                        <hr/>

                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="A short name for your page" value="{{$page->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abstract" class="col-sm-3 control-label">Summary</label>
                            <div class="col-sm-9">
                                <textarea id="abstract" name="summary" class="form-control">{{$page->summary}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea id="content" name="content" required class="form-control wysihtml" style="min-height: 30em; width: 100%;">{!!$page->content!!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cardinality" class="col-md-3 control-label">Cardinality</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" required value="{{$page->cardinality}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="published" value="1" @if($page->is_published) checked @endif>
                                    <i class="fa"></i>
                                    Published?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_menu" value="1" @if($page->show_in_menu) checked @endif>
                                    <i class="fa"></i>
                                    Show in menu?
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_footer" value="1" @if($page->show_in_footer) checked @endif>
                                    <i class="fa"></i>
                                    Show in footer?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Update Page</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('_components.image-previewer')
@endsection
