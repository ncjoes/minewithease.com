@extends('layouts.core.admin')

@section('content')
    <form class="form-horizontal ajax-form" method="POST" action="{{route('cms.admin.slide.create')}}">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Slide</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required
                                       placeholder="A short name for your slide">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="action_url" class="col-sm-3 control-label">Action URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="action_url" name="action_url">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="action_label" class="col-sm-3 control-label">Action Label</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="action_label" name="action_label">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardinality" class="col-sm-3 control-label">Cardinality</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col col-sm-9 col-sm-offset-3">
                                <button type="submit" class="btn btn-primary">Create Slide</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
