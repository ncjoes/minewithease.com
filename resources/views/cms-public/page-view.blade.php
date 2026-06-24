@extends('layouts.public')

@section('content')

    <!-- breadcrumb content begin -->
    <!-- breadcrumb content end -->
    <main data-title="blog-single">
        <!-- blog content begin -->
        <div class="uk-section uk-margin-small-top">
            <div class="uk-container">
                <div class="uk-grid uk-flex uk-flex-center in-blog-1 in-article">
                    <div class="uk-width-1-1 in-figure-available">
                        <img class="uk-width-1-1 uk-border-rounded" src="{{$page->cover_url}}" alt="{{$page->title}}">
                    </div>
                    <div class="uk-width-3-4@m">
                        <article class="uk-card uk-card-default uk-border-rounded in-flat-rounded-top">
                            <div class="uk-card-body">
                                <h2 class="uk-margin-top uk-margin-medium-bottom">{{$page->title}}</h2>
                                {!! $page->content !!}
                            </div>
                            <div class="uk-card-footer uk-clearfix">
                                <!--
                                <div class="uk-float-left in-article-tags">
                                    <i class="fas fa-tags"></i><span class="uk-margin-small-left uk-text-bold">Tags: &nbsp;</span>
                                    <a href="../blog-find.html%3Ftag=inflation.html" class="uk-link-muted">inflation</a>,&nbsp;
                                    <a href="../blog-find.html%3Ftag=usa.html" class="uk-link-muted">usa</a>
                                </div>
                                -->
                                <div class="uk-float-right in-article-share share-btn">
                                    <a class="uk-label color-facebook" data-id="fb"><i class="fab fa-facebook-f"></i></a>
                                    <a class="uk-label color-twitter" data-id="tw"><i class="fab fa-twitter"></i></a>
                                    <a class="uk-label color-pinterest" data-id="pi"><i class="fab fa-pinterest-p"></i></a>
                                    <a class="uk-label color-email" data-id="mail"><i class="fas fa-envelope"></i></a>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
        <!-- blog content end -->
    </main>
@endsection
