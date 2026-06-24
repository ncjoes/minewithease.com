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
    <title>{{$org_name}}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
    <link rel="manifest" href="/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="/favicon.png">
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
            if (linkRTL != null && userLinkRTL != null) {
                linkRTL.setAttribute('disabled', true);
                userLinkRTL.setAttribute('disabled', true);
            }
        }
    </script>
</head>

<body>
<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <div class="container-fluid" style="background-color: #354724;">
        <div class="bg-holder bg-auth-card-overlay" style="background-image:url({{{asset('/assets/img/bg/37.png')}}});"></div>
        <!--/.bg-holder-->
        @yield('content')
        <footer class="footer position-absolute">
            <div class="row g-0 justify-content-between align-items-center h-100">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 mt-2 mt-sm-0 text-body"  style=" color: #fff !important;">
                        All rights reserved
                        <span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none"/>{{ date('Y') }}. {{$org_name}}
                    </p>
                </div>
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-body-tertiary text-opacity-85"  style=" color: #fff !important;">v1.0.0</p>
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