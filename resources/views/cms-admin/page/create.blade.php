@extends('layouts.core.admin')

@section('scripts')
    <script src="{{asset(mix('js/admin-basic-forms.js'))}}" type="text/javascript"></script>
    @include('_components.wysiwyg-editor');
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    Create Page
                    <a href="{{route('cms.admin.page.manage')}}" class="btn btn-sm btn-primary pull-right">All Pages</a>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal ajax-form" method="POST" action="{{route('cms.admin.page.create')}}">

                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" required placeholder="A short name for your page">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abstract" class="col-sm-3 control-label">Summary</label>
                            <div class="col-sm-9">
                                <textarea id="abstract" name="summary" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea id="content" name="content" class="form-control wysihtml" style="min-height: 30em;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cardinality" class="col-md-3 control-label">Cardinality</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" id="cardinality" name="cardinality" required value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <label class="cr-styled">
                                    <input type="checkbox" name="published" value="1">
                                    <i class="fa"></i>
                                    Published?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_menu" value="1">
                                    <i class="fa"></i>
                                    Show in menu?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <label class="cr-styled">
                                    <input type="checkbox" name="show_in_menu" value="1">
                                    <i class="fa"></i>
                                    Show in footer?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary">Create Web Page</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
