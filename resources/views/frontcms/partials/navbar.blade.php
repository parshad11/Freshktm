<!-- Header navbar start -->
<div class="header-navbar fixed-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-nav-row">
                    <div class="row">
                        @if(Request::segment(1) != 'shop')
                            <div class="col-md-10">
                                @else
                                    <div class="col-md-7">
                                        @endif
                                        <nav class="navbar navbar-default">

                                            <div class="navbar-header d-flex">
                                                <div class="logo-main">
                                                    <a href="{{ route('front_dashboard') }}">
                                                        @if(isset($home_settings))
                                                            <img src="{{ asset('uploads/img/home/'.$home_settings->logo_image) }}"
                                                                 alt="">
                                                        @else
                                                            <img src="{{ asset('img/freshktm_logo.png') }}" alt="">
                                                        @endif
                                                    </a>
                                                </div>
                                                {{--@if(isset($home_settings))
                                                <div class="contact"><a href="{{ $home_settings->phone }}">{{ $home_settings->phone }}</a></div>
                                                @endif--}}
                                                <button type="button" class="navbar-toggle collapsed"
                                                        data-toggle="collapse"
                                                        data-target="#bs-example-navbar-collapse-1"
                                                        aria-expanded="false">
                                                    <span class="sr-only">Toggle navigation</span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                </button>
                                            </div>

                                            {{-- <div class="navbar-header">
                                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                                    <span class="sr-only">Toggle navigation</span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                </button>
                                            </div> --}}
                                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"
                                                 data-hover="dropdown" data-animations="fadeIn">
                                                <ul class="nav navbar-nav">
                                                    <li class="{{ Request::segment(1) == '' ? 'active' : ''}}">
                                                        <a href="{{ route('front_dashboard') }}">Home <i
                                                                    class="fa fa-home"
                                                                    aria-hidden="true"></i></a>
                                                    </li>

                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                           role="button"
                                                           aria-haspopup="true" aria-expanded="false">About Us&nbsp;<i
                                                                    class="fa fa-angle-down" aria-hidden="true"></i></a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="{{route('front_about')}}">About</a></li>
                                                            <li><a href="{{route('front_about')}}">Mission and
                                                                    Vision</a></li>
                                                            <li><a href="{{route('contact')}}">Contact Us</a></li>
                                                            <li><a href="{{route('teams')}}">Our Team</a></li>
                                                            <li><a href="{{route('faqs')}}">Faqs</a></li>

                                                        </ul>
                                                    </li>
                                                    <li class="{{ Request::segment(1) == 'buy-sell' ? 'active' : ''}}">
                                                        <a href="{{route('buy_sell')}}">Buy/Sell</a></li>
                                                    <li class="{{ Request::segment(1) == 'careers' ? 'active' : ''}}"><a
                                                                href="{{route('careers')}}">Careers</a></li>
                                                    <li class="{{ Request::segment(1) == 'blog' ? 'active' : ''}}"><a
                                                                href="{{route('blog')}}">Blog</a></li>
                                                </ul>
                                            </div>
                                        </nav>
                                    </div>
                                    @if(Request::segment(1) != 'shop')
                                        <div class="col-md-2 quote_div">
                                            <div class="quote-box">
                                                <a href="#quote" id="get_quote">Contact Us</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-5 shop-options">
                                            <nav class="navbar navbar-default">
                                                <ul class="nav d-sm-flex flex-row navbar-nav">
                                                    {{--<ul class="nav  navbar-nav">--}}
                                                    @guest('customer')
                                                        <li class=""><a href="{{route('front_login')}}">Login &nbsp;
                                                                <i class="fa fa-user-circle-o"
                                                                   aria-hidden="true"></i></a>
                                                        </li>
                                                    @else
                                                        <li class="dropdown">
                                                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                                                               role="button"
                                                               aria-haspopup="true" aria-expanded="false">{{auth()->guard('customer')->user()->name}}&nbsp;<i class="fa fa-user-circle-o"
                                                                                                                                                              aria-hidden="true"></i></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="{{route('customer.account')}}">Account</a></li>
                                                                <li><a href="{{route('user.logout')}}">Log Out</a></li>

                                                            </ul>
                                                        </li>
<!--                                                        <li class="account"><a
                                                                    href="javascript:;">{{auth()->guard('customer')->user()->name}}
                                                                &nbsp;
                                                                <i class="fa fa-user-circle-o"
                                                                   aria-hidden="true"></i></a>
                                                            <ul>
                                                                <li><a href="{{route('customer.account')}}">Account</a></li>
                                                                <li><a href="{{route('user.logout')}}">Log Out</a></li>
                                                            </ul>
                                                        </li>-->
                                                    @endguest
                                                    <li class=""><a
                                                                href="{{route('product.cart')}}">Cart @auth('customer')(
                                                            <span id="cart_count">0</span>)@endauth&nbsp;
                                                            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
