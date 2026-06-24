<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if(strlen((string)$g_analytics_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{$g_analytics_id}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '{{$g_analytics_id}}');
        </script>
    @endif

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta name="viewport" content="width=968px"/> -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ($_pageTitle ?? $org_tagline).' | '.$org_name }}</title>
    <link rel="icon" href="{{asset('favicon.png')}}" type="image/x-icon"/>

    <meta name="description" content="{{$_pageTitle ?? $org_name}}">
    <meta name="author" content="{{$org_name}}">

    <!-- Fonts  -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>

    <!-- Base Styling  -->
    <link rel="stylesheet" href="{{asset(mix('css/admin.css'))}}"/>

    @yield('styles')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body data-ng-app>
@yield('body')

@if(!Auth::guest())
    <form id="logout-form" action="{{ route('auth.logout') }}" onsubmit="return false;" method="POST" class="d-none hidden">
        @csrf
    </form>
@endif

<script src="{{asset(mix('js/admin.js'))}}" type="text/javascript"></script>
<script type="text/javascript">
    window.userCurrency = 'USD';
    window.liveChatKey = '{{$livechat_key}}';
    window.loginUrl = '{{route('auth.login')}}';
</script>
<script src="{{asset(mix('js/app-config.js'))}}" type="text/javascript"></script>

@yield('scripts')

@if($enable_translation)
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                autoDisplay: true,
                layout: google.translate.TranslateElement.InlineLayout.VERTICAL
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endif


@if($livechat_service)
    @include('_components.Livechat.'.$livechat_service)
@endif

</body>
</html>
