@extends('layouts.core.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Update URL Redirect</div>
        <div class="panel-body">

            <form class="form-horizontal ajax-form" method="post" action="{{$redirect->edit_url}}">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="form-group">
                            <label for="slug" class="col-sm-3 control-label">
                                Slug <span class="text-muted">(Source URL)</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">{{config('app.domain')}}/</div>
                                    <input type="text" class="form-control" id="slug" name="slug" required
                                           placeholder="path/to/non-existing-page.html" value="{{$redirect->slug}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="destination" class="col-sm-3 control-label">
                                Destination URL
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">{{config('app.domain')}}/</div>
                                    <input type="text" class="form-control" id="destination" name="destination" required
                                           placeholder="path/to/existing-page.html" value="{{$redirect->destination}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="submit" class="btn btn-block btn-primary">Update Redirect</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection