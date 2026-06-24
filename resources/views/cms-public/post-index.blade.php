@extends('layouts.public')

@section('content')
    <!--Common hero Section Starts -->
    <section class="cmn_heros pb-120 pt-120">
        <div class="container">
            <div class="row justify-content-center mt-5 mt-md-8 mt-lg-0">
                <div class="col-xxl-6">
                    <div class="cmn_heros__title text-center pt-15 pt-lg-6">
                        <h1 class="display-three mb-5 mb-md-7 wow fadeInUp">Blog & Resources</h1>
                        <p class="roboto wow fadeInUp">
                            Stay updated with our latest news, insights, and industry trends. Explore our
                            recent articles and resources to gain valuable knowledge and stay ahead in the
                            ever-evolving world of Web3 technology and innovation.    
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Common hero Section Ends -->
    <!-- Pricing Plan Section Starts Starts -->
    <section class="blog_details blog_resource pt-120 pb-120 bg9-color">
        <div class="container">

            <!-- Pinned Post
            <div class="row pb-120">
                <div
                    class="blog_resource__topcard d-flex align-items-center gap-5 gap-sm-6 gap-lg-10 rounded-20 flex-wrap flex-lg-nowrap p-3 p-sm-6 p-lg-8 wow fadeInUp">
                    <div class="blog_resource__topcard-thumb">
                        <img src="assets/images/detailscard-top.png" class="max-un" alt="Images">
                    </div>
                    <div class="blog_details__cmncard-content">
                        <div class="blog_details__cmncard-head d-flex align-items-center gap-2 mb-5 mb-sm-7 mb-md-8">
                            <span class="fw-bold roboto p4-color">June 27, 2024</span>
                            <i class="ti ti-point-filled pointed2 fs-nine"></i>
                            <span class="fw-bold roboto p4-color">Marketing</span>
                        </div>
                        <a href="javascript:void(0)">
                            <h2 class="mb-5 mb-md-6 p4-color">Crypto 101: All you need to know about the crypto world
                            </h2>
                        </a>
                        <p class="mb-5 mb-sm-7 mb-md-8 p4-color">Explainability features and deep model. Specialized
                            consectetur adipiscing sed do eiusmod.Explainability features and deep model. Specialized
                            consectetur adipiscing sed do eiusmod.</p>
                        <div class="blog_details__cmncard-btn">
                            <a href="javascript:void(0)"
                                class="cmn-btn second-alt d-inline-flex px-4 px-sm-5 px-md-6 py-2 py-md-3 d-center gap-2">Read
                                More</a>
                        </div>
                    </div>
                </div>
            </div>
            -->
    
            <div class="row gy-5 gy-md-6">
                <div class="col-12">
                    <div class="blog_resource__cmncardtitile d-flex align-items-center justify-content-between gap-5 flex-wrap mb-10 mb-md-15 wow fadeInUp">
                        <div class="blog_resource__title">
                            <h2 class="mb-4">Our recent news</h2>
                        </div>
                        <ul class="blog_resource__tag d-flex align-content-center flex-wrap gap-3 gap-md-4">
                            <li class="nav-links">
                                <button
                                    class="tablink cmn-btn third-alt py-3 px-5 px-md-6 roboto">All</button>
                            </li>
                            <li class="nav-links">
                                <button class="tablink cmn-btn third-alt py-3 px-5 px-md-6 roboto">Finance</button>
                            </li>
                            <li class="nav-links">
                                <button
                                    class="tablink cmn-btn third-alt py-3 px-5 px-md-6 roboto">Marketing</button>
                            </li>
                            <li class="nav-links">
                                <button
                                    class="tablink cmn-btn third-alt py-3 px-5 px-md-6 roboto">Technology</button>
                            </li>
                        </ul>
                    </div>
                        <div class="row gy-5 gy-md-6">
                            @foreach($posts as $post)
                                <div class="col-lg-6">
                                    <div
                                        class="blog_details__cmncard p-3 p-sm-6 p-md-8 rounded-20 br2 bg1-color position-relative wow fadeInUp">
                                        <div class="blog_details__cmncard-thumb mb-5 mb-sm-7 mb-md-8 rounded-4">
                                            <img src="{{$post->cover_url}}" alt="Images">
                                        </div>
                                        <div class="blog_details__cmncard-content">
                                            <div
                                                class="blog_details__cmncard-head d-flex align-items-center gap-2 mb-5 mb-sm-7 mb-md-8">
                                                <span class="fw-bold roboto">{{date_for_humans($post->published_at)}} </span>
                                                <i class="ti ti-point-filled pointed fs-nine"></i>
                                                <span class="fw-bold roboto">{{ $post->category->title }}</span>
                                            </div>
                                            <a href="{{ $post->url }}">
                                                <h4 class="mb-5 mb-md-6">{{ $post->title }}</h4>
                                            </a>
                                            <p class="mb-5 mb-sm-7 mb-md-8">
                                                {{ clamp($post->summary, 140) }}
                                            </p>
                                            <div class="blog_details__cmncard-btn">
                                                <a href="{{ $post->url }}" class="cmn-btn d-inline-flex px-4 px-sm-5 px-md-6 py-2 py-md-3 d-center gap-2">
                                                    Read More <i class="ti ti-arrow-narrow-right fs-four"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-10">
                            {{ $posts->links() }}
                        </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing Plan Section Starts Ends -->

@endsection

@section('styles')
    <style>
    </style>
@endsection