@extends('layouts.public', ['_pageTitle' => 'Frequently Asked Questions'])

@section('styles')
    @parent
    <style>
        .list-group-item {
            padding: 0;
        }

        .list-group-item a.nav-link {
            padding: 1.0rem 1.25rem;
            border-radius: 0;
            color: #0b0b0b;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #fff;
            background-color: #F63854;
        }

        .btn[data-toggle=collapse] {
            color: #0b0b0b !important;
            text-decoration: none;
            padding: 0;
        }

        .accordion .card-header {
            border-bottom: none;
            background-color: transparent;
        }

        .tab-content, .accordion .collapse .card-body * {
            font-weight: 400 !important;
        }
    </style>
@endsection

@section('content')
    <section id="content-area" style="min-height: 75vh;">
        <div class="section-padding bg-gray pt-15">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Frequently Asked Questions</h2>
                    <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
                </div>
                <div class="row align-items-center">
                    <div class="content col-md-10 col-lg-10 mx-auto">
                        <p class="text-center">
                            Below You Can Find Answers to Most Common Questions Asked by Other Investors and Participants of The Referral Program.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-padding bg-white">
            <div class="container">

                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group nav flex-column nav-pills mb-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @foreach($faq_categories as $category)
                                <li class="list-group-item">
                                    <a class="nav-link @if($loop->first) active @endif" id="v-pills-{{$category->slug}}-tab" data-toggle="pill" href="#v-pills-{{$category->slug}}"
                                       role="tab" aria-controls="v-pills-{{$category->slug}}" aria-selected="true">{{$category->title}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach($faq_categories as $category)
                                <div class="tab-pane fade @if($loop->first) show active @endif" id="v-pills-{{$category->slug}}" role="tabpanel"
                                     aria-labelledby="v-pills-{{$category->slug}}-tab">

                                    <div class="accordion" id="accordion-{{$category->id}}">
                                        @foreach($category->faqs()->orderByDesc('cardinality')->get() as $faq)
                                            <div class="card">
                                                <div class="card-header" id="heading-{{$faq->id}}">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-{{$faq->id}}"
                                                                aria-expanded="true" aria-controls="collapse-{{$faq->id}}">
                                                            {{$faq->question}}
                                                        </button>
                                                    </h2>
                                                </div>

                                                <div id="collapse-{{$faq->id}}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading-{{$faq->id}}"
                                                     data-parent="#accordion-{{$category->id}}">
                                                    <div class="card-body">
                                                        {!! $faq->answer !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="my-5 text-center">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <h4><span style="font-size: 50%">Can't find an answer?</span></h4>
                            <a href="{{route('cms.home')}}#contact" class="btn btn-common">CONTACT US</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
