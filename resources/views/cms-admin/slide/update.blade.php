@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-img_cropper.js'))}}" type="text/javascript"></script>
@endsection

@section('content')
    <form class="form-horizontal ajax-form" method="POST"
          action="{{$slide->edit_url}}">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Slide</div>
                    <div class="panel-body pt-0">
                        <div class="form-group">
                            <div class="pic-container text-center">
                                <div class="padd-xs">
                                    <img class="img-responsive center-block cropper-destination photo"
                                         itemprop="image" src="{{$slide->photo_url}}" alt="{{$slide->title}}"/>
                                </div>
                                <div class="after text-center" style="padding-top: 2em;">
                                    <label class="btn btn-primary" for="cover_photo">
                                        <span class="fa fa-camera"></span>
                                        <strong>
                                            @if(empty($slide->photo))
                                                Upload Image
                                            @else
                                                Change Image
                                            @endif
                                        </strong>
                                    </label>
                                    <input class="hidden cropper-source" id="cover_photo" type="file"
                                           data-handler="{{route('cms.admin.slide.image', ['slide' => $slide->id])}}"
                                           data-width="1200" data-height="675" data-attribute="photo"
                                           data-preview="{{$slide->photo_url}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="A short name for your slide" value="{{$slide->title}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description"
                                          class="form-control">{{$slide->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="action_url" class="col-sm-3 control-label">Action URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="action_url" name="action_url"
                                       value="{{$slide->action_url}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="action_label" class="col-sm-3 control-label">Action Label</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="action_label" name="action_label"
                                       value="{{$slide->action_label}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardinality" class="col-sm-3 control-label">Cardinality</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" required
                                       value="{{$slide->cardinality}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col col-sm-9 col-sm-offset-3">
                                <button type="submit" class="btn btn-primary">Update Slide</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('_components.image-previewer')
@endsection
