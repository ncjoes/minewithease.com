@extends('layouts.core.admin')

@section('content')
    <div class="page-header">
        <h1>Dashboard
            <small>Let's get a quick overview...</small>
        </h1>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats">
                <i class="fa fa-users bg-danger transit stats-icon"></i>
                <h3 class="transit text-center">
                    {{$editors_count}}
                    <small class="text-green hidden"><i class="fa fa-caret-up"></i> 8%</small>
                </h3>
                <p class="text-muted transit text-center">Editors</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats">
                <i class="fa fa-tags bg-info transit stats-icon"></i>
                <h3 class="transit text-center">
                    {{$categories_count}}
                    <small class="text-red hidden"><i class="fa fa-caret-down"></i> 6%</small>
                </h3>
                <p class="text-muted transit text-center">Categories</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats">
                <i class="fa fa-bullhorn bg-success transit stats-icon"></i>
                <h3 class="transit text-center">
                    {{$posts_count}}
                    <small class="text-green hidden"><i class="fa fa-caret-up"></i> 9%</small>
                </h3>
                <p class="text-muted transit text-center">Blog Posts</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default clearfix dashboard-stats">
                <i class="fa fa-question-circle bg-info transit stats-icon"></i>
                <h3 class="transit text-center">
                    {{$faqs_count}}
                    <small class="text-red hidden"><i class="fa fa-caret-down"></i> 20%</small>
                </h3>
                <p class="text-muted transit text-center">FAQs & Answers</p>
            </div>
        </div>
    </div>
@endsection
