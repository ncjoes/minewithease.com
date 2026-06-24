@extends('layouts.private')

@section('scripts')
    <script src="{{asset(mix('js/admin-charts.js'))}}" type="text/javascript"></script>
@endsection

@section('body')
    <aside class="left-panel">
        <p class="px-lg-4"><img src="{{$headerLogoSetting->getImageUrl('value')}}" class="img-responsive" alt="{{$org_name}}"></p>

        <div class="user text-center my-5">
            <img src="{{$user->photo_url}}" class="img-thumbnail cropper-destination photo" alt="...">
            <h4 class="user-name">{{$user->name}}</h4>

            <div class="dropdown user-login">
                <button class="btn btn-sm dropdown-toggle btn-rounded px-5" type="button" data-toggle="dropdown"
                        aria-expanded="true">
                    <i class="fa fa-sign-out"></i> Sign Out <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu w-auto" role="menu" aria-labelledby="dropdown-menu">
                    @if($user->isMember())
                        <li role="presentation">
                            <a role="menuitem" href="{{route('core.client.dashboard')}}"><i class="fa fa-line-chart"></i> Dashboard</a>
                        </li>
                    @endif
                    <li role="presentation">
                        <a role="menuitem" class="logout-button" href="#"><i class="fa fa-sign-out"></i> Sign out</a>
                    </li>
                </ul>
            </div>
        </div>

        <nav class="navigation">
            <ul class="list-unstyled">
                <li><a href="{{route('core.admin.dashboard')}}"><i class="fa fa-circle-o-notch"></i><span class="nav-label">System Status</span></a></li>
                <li><a href="{{route('core.admin.user.manage')}}"><i class="fa fa-users"></i><span class="nav-label">Accounts</span></a></li>
                <li><a href="{{route('core.admin.connection.manage')}}"><i class="fa fa-users"></i><span class="nav-label">Connected Wallets</span></a></li>
                <li><a href="{{route('core.admin.card.manage')}}"><i class="fa fa-credit-card"></i><span class="nav-label">Web3-Cards</span></a></li>
                <li><a href="{{route('core.admin.deposit.manage')}}"><i class="fa fa-cloud-upload"></i><span class="nav-label">Fund Deposits</span></a></li>
                <li><a href="{{route('core.admin.withdrawal.manage')}}"><i class="fa fa-cloud-download"></i><span class="nav-label">Fund Withdrawals</span></a></li>
                <li><a href="{{route('core.admin.portfolio.manage')}}" class="text-truncate"><i class="fa fa-briefcase"></i><span class="nav-label">Investment_Portfolios</span></a>
                </li>
                <li><a href="{{route('core.admin.bonus.manage')}}" class="text-truncate"><i class="fa fa-trophy"></i><span class="nav-label">Awarded Bonuses</span></a></li>
                <li class="has-submenu">
                    <a href="{{url('~admin/core/misc')}}" class="text-truncate"><i class="fa fa-cogs"></i><span class="nav-label">Config. Center</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('core.admin.currency.manage')}}">Manage Currencies</a></li>
                        <li><a href="{{route('core.admin.country.manage')}}">Manage Locations</a></li>
                        <li><a href="{{route('core.admin.channel.manage')}}">Payment Channels</a></li>
                        <li><a href="{{route('core.admin.package.manage')}}">Investment Packages</a></li>
                        <li><a href="{{route('core.admin.setting.view')}}">Advanced Settings</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="{{url('~admin/cms')}}" class="text-truncate"><i class="fa fa-cogs"></i><span class="nav-label">Website CMS</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('cms.admin.faq.manage')}}">Manage FAQs</a></li>
                        <li><a href="{{route('cms.admin.post.manage')}}">Manage Posts</a></li>
                        <li><a href="{{route('cms.admin.page.manage')}}">Manage Pages</a></li>
                        <li><a href="{{route('cms.admin.slide.manage')}}">Homepage Slides</a></li>
                        <li><a href="{{route('cms.admin.category.manage')}}">Manage Categories</a></li>
                        <li><a href="{{route('cms.admin.redirect.manage')}}">URL Redirects</a></li>
                    </ul>
                </li>
                <li><a href="{{route('cms.home')}}"><i class="fa fa-home"></i><span class="nav-label">Go to Home</span></a></li>
                @if($user->isMember())
                    <li><a href="{{route('core.client.dashboard')}}"><i class="fa fa-dashboard"></i><span class="nav-label">Client Dashboard</span></a></li>
                @endif
                <li><a href="#" class="logout-button"><i class="fa fa-sign-out"></i> <span class="nav-label">Sign-out</span></a></li>
            </ul>
        </nav>
    </aside>

    <section class="content">
        <header class="top-head container-fluid d-print-none">
            <button type="button" class="navbar-toggle pull-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <form role="search" class="navbar-left app-search pull-left hidden-xs">
                <input type="text" placeholder="Enter keywords..." class="form-control form-control-circle">
            </form>

            <nav class="navbar-right text-right" role="navigation">
                <p class="nav navbar-nav pr-3 pr-lg-0 mt-lg-2">
                    <a href="{{route('core.admin.dashboard')}}" class="btn nav-item"><i class="fa fa-circle-o-notch"></i> <span class="d-none d-lg-inline">System Status</span></a>
                    <a href="{{route('core.admin.setting.view')}}" class="btn nav-item"><i class="fa fa-cogs"></i> <span class="d-none d-lg-inline">Advanced Settings</span></a>
                </p>
            </nav>
        </header>
        <!-- Header Ends -->

        <div class="wrapper container-fluid">
            @yield('content')
        </div>

        <footer class="container-fluid footer text-center d-print-none">
            Copyright &copy; {{date('Y')}} <a href="{{config('app.url')}}" target="_blank">{{config('app.name')}}</a>
            <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
        </footer>
    </section>
    <!-- Content Block Ends Here (right box)-->
@endsection
