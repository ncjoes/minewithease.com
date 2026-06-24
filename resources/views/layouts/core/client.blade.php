<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="horizontal" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>{{isset($pageTitle) ? $pageTitle.' | '.$org_name : $org_name}}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
    <meta name="theme-color" content="#ffffff">
    <script src="/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/js/config.js"></script>
    <script>
        window.config.set({
            phoenixNavbarPosition: 'horizontal',
            phoenixNavbarTopShape: 'default',
        });
    </script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="{{asset(mix('css/dashboard.css'))}}"/>
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            if(linkRTL != null && userLinkRTL != null){
                linkRTL.setAttribute('disabled', true);
                userLinkRTL.setAttribute('disabled', true);
            }
        }
    </script>
    <style>
        .control-label, .col-form-label {
            text-align: right !important;
        }
    </style>
    @yield('styles')
</head>

<body>
<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarTop">
        <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse"
                    aria-controls="navbarTopCollapse" aria-expanded="false" aria-label="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
            <a class="navbar-brand me-1 me-sm-3" href="/">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center"><img src="{{asset('/images/logo.png')}}" alt="phoenix" height="52"/>
                        <!--<h5 class="logo-text ms-2 d-none d-sm-block">{{$org_name}}</h5>-->
                    </div>
                </div>
            </a>
        </div>
        <div class="collapse navbar-collapse navbar-top-collapse order-1 order-lg-0 justify-content-center" id="navbarTopCollapse">
            <ul class="navbar-nav navbar-nav-top" data-dropdown-on-hover="data-dropdown-on-hover">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('core.client.dashboard')}}">
                        <span class="me-2 uil uil-dashboard" data-feather="dashboard"></span> Wallet Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('core.client.withdrawal.create')}}">
                        <span class="uil fs-8 me-2 uil-send" data-feather="send"></span> Send
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('core.client.deposit.create')}}">
                        <span class="uil fs-8 me-2 uil-inbox" data-feather="inbox"></span> Receive
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('core.client.swap.create')}}">
                        <span class="uil fs-8 me-2 uil-exchange" data-feather="exchange"></span> Swap
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('cms.connect-wallet')}}">
                        <span class="uil fs-8 me-2 uil-link" data-feather="link"></span> Connect Wallet
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('core.client.portfolio.create')}}">
                        <span class="uil fs-8 me-2 uil-puzzle-piece" data-feather="puzzle-piece"></span> Stakes
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="uil fs-8 me-2 uil-document-layout-right"></span> Account
                    </a>
                    <ul class="dropdown-menu navbar-dropdown-caret">
                        <li>
                            <a class="dropdown-item" href="{{route('core.client.transactions')}}">
                                <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="list"></span> Account History</div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('core.client.notifications')}}">
                                <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="plus"></span> Notifications</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
                <div class="theme-control-toggle fa-icon-wait px-2">
                    <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle"/>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                           data-bs-title="Switch theme" style="height:32px;width:32px;">
                        <span class="icon" data-feather="moon"></span>
                    </label>
                    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                           data-bs-title="Switch theme" style="height:32px;width:32px;">
                        <span class="icon" data-feather="sun"></span>
                    </label>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" style="min-width: 2.25rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                   data-bs-auto-close="outside">
                    <span class="d-block" style="height:20px;width:20px;">
                        <span data-feather="bell" style="height:20px;width:20px;"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication"
                     aria-labelledby="navbarDropdownNotfication">
                    <div class="card position-relative border-0">
                        <div class="card-header p-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-body-emphasis mb-0">Notifications</h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollbar-overlay" style="max-height: 30rem; height: auto;">
                                @foreach($user->unreadNotifications()->take(7)->get() as $notification)
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative @if($notification->unread()) unread @endif border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-hover">{{$notification->data['title']}}</h4>
                                                    <p class="fs-10 mb-0">
                                                        <span class="me-1 fas fa-clock"></span><span class="fw-bold">{{date_time_for_humans($notification->created_at)}} </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer p-0 border-top border-translucent border-0">
                            <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85">
                                <a class="fw-bolder" href="{{route('core.client.notifications')}}">Notification history</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="avatar avatar-l ">
                        <img class="rounded-circle " src="{{$user->photo_url}}" alt=""/>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <div class="avatar avatar-xl ">
                                    <img class="rounded-circle " src="{{$user->photo_url}}" alt=""/>
                                </div>
                                <h6 class="mt-2 text-body-emphasis">{{$user->name}}</h6>
                            </div>
                        </div>
                        <div class="overflow-auto scrollbar" style="">
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block" href="{{route('core.client.profile-update')}}">
                                        <span class="me-2 text-body align-bottom" data-feather="user"></span><span>Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block" href="{{route('core.client.security')}}">
                                        <span class="me-2 text-body align-bottom" data-feather="lock"></span>Security
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3 d-block" href="{{route('core.client.account-settings')}}">
                                        <span class="me-2 text-body align-bottom" data-feather="settings"></span>Settings &amp; Privacy
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer border-top border-translucent">
                            @if($user->isAdmin())
                                <ul class="nav d-flex flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link px-3 d-block" href="{{route('core.admin.dashboard')}}">
                                            &nbsp;<span class="me-2 text-body align-bottom" data-feather="chart"></span>Switch to Admin. Dashboard
                                        </a>
                                    </li>
                                </ul>
                                <hr/>
                            @endif
                            <div class="px-3">
                                <a class="btn btn-phoenix-secondary d-flex flex-center w-100 logout-button" href="#!">
                                    <span class="me-2" data-feather="log-out"> </span>Sign out
                                </a>
                            </div>
                            <div class="my-2 text-center fw-bold fs-10 text-body-quaternary">
                                <a class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;
                                <a class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;
                                <a class="text-body-quaternary ms-1" href="#!">Cookies</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <div class="content">
        @yield('content')
        <footer class="footer position-absolute">
            <div class="row g-0 justify-content-between align-items-center h-100">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 mt-2 mt-sm-0 text-body">
                        All rights reserved
                        <span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none"/>{{ date('Y') }}. {{$org_name}}</p>
                </div>
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-body-tertiary text-opacity-85">v1.0.0</p>
                </div>
            </div>
        </footer>
    </div>

</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

@if(!Auth::guest())
    <form id="logout-form" action="{{ route('auth.logout') }}" onsubmit="return false;" method="POST" class="d-none hidden">
        @csrf
    </form>
@endif

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="{{asset(mix('js/dashboard.js'))}}"></script>
<script src="/assets/js/phoenix.js"></script>
<script src="/assets/js/ecommerce-dashboard.js"></script>
<script src="{{asset('/js/jquery-1.9.1.min.js')}}"></script>
<script type="text/javascript">
    window.userCurrency = 'USD';
    window.liveChatKey = '{{$livechat_key}}';
    window.loginUrl = '{{route('auth.login')}}';
</script>
<script src="{{asset('/js/app-config.js')}}"></script>
@yield('scripts')


@if($livechat_service)
    @include('_components.Livechat.'.$livechat_service)
@endif

</body>

</html>