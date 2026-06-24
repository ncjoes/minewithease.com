@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h1>Homepage Slides</h1>
    </div>

    <form class="ajax-form" method="post" action="{{route('cms.admin.slide.manage')}}">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a class="btn btn-success btn-sm" href="{{route('cms.admin.slide.create')}}">New Slide...</a>
                <div class="btn-group pull-right">
                    <select class="form-control d-inline btn btn-sm" style="width: auto;" name="action">
                        <option value="trash">Trash</option>
                        <option value="recycle">Recycle</option>
                        <option value="delete">Delete Forever</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Go</button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    @foreach($slides as $slide)
                        <div class="col col-md-4 col-lg-3">
                            <label for="s-{{$slide->id}}">
                                <img src="{{$slide->photo_url}}"
                                     class="img-responsive img-thumbnail"
                                     @if($slide->trashed()) style="border:1px solid red;" @endif>
                            </label>
                            <p style="position: relative; top: -5em;" class="m-0 px-3 py-1 text-right">
                                <input name="ids[]" value="{{$slide->id}}" id="s-{{$slide->id}}" type="checkbox">
                                <a href="{{$slide->edit_url}}" class="btn btn-primary">Edit <i class="fa fa-pencil"></i></a>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection
