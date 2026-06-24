@extends('layouts.public')

@section('content')

    <!--Common hero Section Starts -->
    <section class="cmn_heros pb-120 pt-120">
        <div class="container">
            <div class="row justify-content-center mt-5 mt-md-8 mt-lg-0">
                <div class="col-xxl-6">
                    <div class="cmn_heros__title text-center pt-15 pt-lg-6">
                        <h1 class="display-five mb-5 mb-md-7 wow fadeInUp">{{$org_name}} Blog</h1>
                        <p class="roboto wow fadeInUp">Explore our blog and resources for valuable insights, expert opinions, and
                            up-to-date information on the latest trends in the industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Common hero Section Ends -->

    <!-- Pricing Plan Section Starts Starts -->
    <section class="blog_details pt-120 pb-120 bg9-color">
        <div class="container">
            <div class="row gy-6 align-items-end justify-content-between mb-6">
                <div class="col-xl-6">
                    <div class="blog_details__title">
                        <h2 class="text_grdt wow fadeInUp">{{ $post->title }}</h2>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="blog_details__btn d-flex align-items-center justify-content-end gap-5 gap-md-6 wow fadeInUp">
                        <a href="{{ $category->url }}" class="p1-color py-2 px-4 br2 bg1-color">{{ $category->title }}</a>
                        <span>{{ date_for_humans($post->published_at) }}</span>
                    </div>
                </div>
                <!--
                <div class="col-12">
                    <div class="blog_details__chart br2 rounded-20 p-3 p-sm-7 p-md-10 wow fadeInUp mt-5 mt-md-7 mt-lg-9">
                        <div
                            class="blog_details__chart-title d-flex align-items-center justify-content-between gap-3 flex-wrap mb-6 mb-sm-8 mb-md-10">
                            <h2>Analytics</h2>
                            <div class="d-flex align-items-center flex-wrap gap-3 gap-sm-5 gap-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-point-filled fs-two p1-color"></i>
                                    <span class="fs-four">Income</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-point-filled fs-two p1-color"></i>
                                    <span class="fs-four">Outcome</span>
                                </div>
                                <a href="javascript:void(0)" class="cmn-btn py-2 px-4 rounded-1">2024</a>
                            </div>
                        </div>
                        <div class="blog_details__chart-thumb">
                            <img src="assets/images/blogdetailschart.png" alt="Images">
                        </div>
                    </div>
                </div>
                -->
                <div class="col-12">
                    <div class="terms_condition__content mt-5 mt-sm-7 mt-lg-9">
                        {!! $post->content !!}
                    </div>
                </div>
                <!--
                <div class="col-12">
                    <div class="blog_details__card py-3 py-sm-6 py-lg-10 py-xl-15 px-3 px-sm-8 py-lx-15 px-xl-20 rounded-20 d-center justify-content-md-start flex-wrap flex-md-nowrap gap-4 gap-sm-5 gap-md-8 gap-xl-10 wow fadeInUp">
                        <div class="blog_details__card-thumb">
                            <img src="assets/images/details-image-nayem.png" class="max-un" alt="Images">
                        </div>
                        <div class="blog_details__card-content">
                            <div class="d-flex align-items-center justify-content-center justify-content-sm-between gap-3 flex-wrap mb-5 mb-md-6">
                                <h2>Ryan Marshall</h2>
                                <div class="blog_details__card-social d-flex align-items-center gap-2 gap-md-3">
                                    <a href="javascript:void(0)" class="px-2 px-md-3 py-1 py-md-2  d-center">
                                        <i class="ti ti-brand-facebook-filled fs-four"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="px-2 px-md-3 py-1 py-md-2  d-center">
                                        <i class="ti ti-brand-instagram fs-four"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="px-2 px-md-3 py-1 py-md-2  d-center">
                                        <i class="ti ti-brand-twitter-filled fs-four"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="roboto text-center text-md-start">Id ipsum mi tempor eget. Pretium consectetur scelerisque blandit habitasse non ullamcorper enim, diam quam id et, tempus massa. Sed nam vulputate pellentesque quis. Varius a, nunc faucibus proin</p>
                        </div>
                    </div>
                </div>
                -->
            </div>
            <div class="row gy-5 gy-md-6">
                <div class="col-12 mt-10 mt-md-15">
                    <div class="blog_details__cmncardtitile d-flex align-items-center justify-content-between gap-3 flex-wrap mb-5 mb-md-9 wow fadeInUp">
                        <h2>Our recent news</h2>
                        <a href="{{ route('cms.post.index') }}" class="cmn-btn py-3 px-5 px-md-6 roboto">View More</a>
                    </div>
                </div>

                @foreach($recent_posts as $recent_post)
                    <div class="col-lg-6">
                        <div class="blog_details__cmncard p-3 p-sm-6 p-md-8 rounded-20 br2 bg1-color position-relative wow fadeInUp">
                            <div class="blog_details__cmncard-thumb mb-5 mb-sm-7 mb-md-8 rounded-4">
                                <img src="{{$recent_post->cover_url}}" alt="Images">
                            </div>
                            <div class="blog_details__cmncard-content">
                                <div class="blog_details__cmncard-head d-flex align-items-center gap-2 mb-5 mb-sm-7 mb-md-8">
                                    <span class="fw-bold roboto">{{ date_for_humans($recent_post->published_at) }}</span>
                                    <i class="ti ti-point-filled pointed fs-nine"></i>
                                    <span class="fw-bold roboto">{{ $recent_post->category->title }}</span>
                                </div>
                                <a href="{{ $recent_post->url }}">
                                    <h4 class="mb-5 mb-md-6">{{ $recent_post->title }}</h4>
                                </a>
                                <p class="mb-5 mb-sm-7 mb-md-8">{{ clamp($recent_post->summary, 140) }}</p>
                                <div class="blog_details__cmncard-btn">
                                    <a href="{{ $recent_post->url }}" class="cmn-btn d-inline-flex px-4 px-sm-5 px-md-6 py-2 py-md-3 d-center gap-2">Read More <i class="ti ti-arrow-narrow-right fs-four"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Pricing Plan Section Starts Ends -->


@endsection
