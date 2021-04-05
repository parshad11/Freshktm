@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('styles')
    <style>
        /* Values Section */
        .values-box {
            height: 100%;
            width: 100%;
            border-radius: 0;
            transition: .2s linear;
        }

        .values-box::before {
            content: none;
        }

        .values-box::after {
            content: none;
        }

        .values-box:hover {
            transform: translateY(-5px) !important;
            background-color: #82b440;
            color: white;
        }

        .values-icon {
            background: none !important;
            color: #82b440;
        }

        .values-icon i {
            color: #82b440 !important;
        }

        .values-box:hover i {
            color: white !important;
        }

        .features-box .value.features-content {
            padding: 0;
        }

        .value.features-box:hover {
            transform: translateY(0);
        }

        .values-box h3 {
            margin: 0 0 10px 0;
            padding: 0;
            line-height: 26px;
            color: #16314d;
        }

        .values-box .description {
            font-size: 15px;
            line-height: 22px;
            color: #333;
            /* text-align: left; */
            padding: 0 15px 15px;
        }

        .value .title {
            text-align: center
        }

        .value_img {
            height: 60px;
            width: auto;
        }

        .features-icon.why_us img {
            width: auto;
            height: 65px;
        }
    </style>
@endsection
@section('content')
    <!-- Banner Start -->
    <section class="slider-area jarallax overlay-black">
        <div id="slider" data-zs-src='[
        @if(isset($home_setting) && $home_setting->banner_images != null)
        @php
            $banner_images= explode(',',$home_setting->banner_images);
        @endphp
        @if(isset($banner_images[0]))
                "{{ asset('uploads/img/home/banner/'.$banner_images[0]) }}"
            @endif
        {{-- ,"{{ asset('uploads/img/home/banner/'.$banner_images[0]) }}" --}}
        @if(isset($banner_images[1]))
                ,"{{ asset('uploads/img/home/banner/'.$banner_images[1]) }}"
            @endif
        @if(isset($banner_images[2]))
                ,"{{ asset('uploads/img/home/banner/'.$banner_images[2]) }}"
            @endif
        @endif
                ]' data-zs-bullets="false" data-zs-interval="4000" data-zs-switchSpeed="1000" data-zs-speed="4000"
             data-zs-overlay="false" data-zs-autoplay="true">
            <div class="slider-content">
                <div class="container">
                    <div class="row">

                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="slider-col slider-ontext">
                                {{-- <div class="main-banner">
                                    <img src="{{ asset('cms/images/main-banner.jpg')}}" alt="" srcset="">
                                </div> --}}
                                {{-- <h1>Start your business with <span>freshktm<span></span></span></h1> --}}
                                <h3 class="cd-headline clip">
                                    <span class="cd-words-wrapper" style="width: 30.9827px; overflow: hidden;">
                                        <b class="is-visible">We are very experienced</b>
                                        <b class="is-hidden">we are very trusted</b>
                                        <b class="is-hidden">Be happy with us</b>
                                    </span>
                                </h3>
                                <div class="slider-buttons">
                                    <a class="btn btn-default theme-btn btn-hover" href="{{ route('shop') }}" id="">Shop
                                        Now</a>
                                    <a class="btn btn-default theme-btn btn-hover" href="#quote" id="get_contact">Become
                                        a
                                        Partner</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Start -->
    <section class="features-area bg-shape">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2>why choose us</h2>
                        <div class="title-border"></div>
                        <p>Better food life for people.</p>
                    </div>
                </div>
                @if(isset($home_setting) && $home_setting->why_choose_us != null)
                    @php
                        $why_text = json_decode($home_setting->why_choose_us, true);
                    @endphp
                    <div class="col-md-4 col-sm-6 col-xs-6 fw600">
                        <div class="features-col">
                            <div class="features-box">
                                <div class="features-icon why_us">
                                    <img src="{{ asset('cms/images/farmer.png') }}" alt="">
                                </div>
                                <div class="features-content">
                                    <h3 class="title">Benifits for Farmers</h3>
                                    <p class="description">
                                    @php
                                        $first_list = explode(",", $why_text['Benifits for Farmers']);
                                    @endphp
                                    <ul>
                                        @foreach ($first_list as $item)
                                            <li><i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;{{$item}}
                                            </li>
                                        @endforeach
                                    </ul>
                                    {{-- {{$why_text['Benifits for Farmers']}} --}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6 fw600">
                        <div class="features-col">
                            <div class="features-box">
                                <div class="features-icon why_us">
                                    <img src="{{ asset('cms/images/retail.png') }}" alt="">
                                </div>
                                <div class="features-content">
                                    <h3 class="title">Benifits for Retailers</h3>
                                    <p class="description">
                                    @php
                                        $second_list = explode(",", $why_text['Benifits for Retailers']);
                                    @endphp
                                    <ul>
                                        @foreach ($second_list as $item)
                                            <li><i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;{{$item}}
                                            </li>
                                        @endforeach
                                    </ul>
                                    {{-- {{$why_text['Benifits for Retailers']}} --}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6 fw600">
                        <div class="features-col">
                            <div class="features-box">
                                <div class="features-icon why_us">
                                    <img src="{{ asset('cms/images/user.png') }}" alt="">
                                </div>
                                <div class="features-content">
                                    <h3 class="title">Saving for Consumers</h3>
                                    <p class="description">
                                    @php
                                        $third_list = explode(",", $why_text['Saving for Consumers']);
                                    @endphp
                                    <ul>
                                        @foreach ($third_list as $item)
                                            <li><i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;{{$item}}</li>
                                        @endforeach
                                    </ul>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- About Start -->
    <section class="about-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="about-col">
                        <h2>Welcome to <span>Freshktm</span></h2>
                        <h4>B to B Fresh Produce Supply Chain</h4>
                        @if(isset($home_setting))
                            <p>{!! substr($home_setting->welcome_description, 0, 1200) !!}</p>
                        @endif
                        <span><a class="btn btn-default theme-btn btn-hover"
                                 href="{{route('front_about')}}">Read More</a>
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    @if(isset($home_setting))
                        <div class="about-col">
                            <img src="{{ asset('uploads/img/home/'.$home_setting->welcome_image)}}" alt="">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section section-default jarallax overlay-black">
        @if(isset($home_setting))
            <img class="jarallax-img" src="{{ asset('uploads/img/home/'.$home_setting->vdo_image)}}" alt="">
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="video-col">
                        <h2>Let's see a quick video</h2>
                        <a class="bla-2 hvr-ripple-out" href="{{ isset($home_setting) ? $home_setting->vdo_link : ''}}">
                            <i class="fa fa-play-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Section -->
    <section class="features-area bg-shape">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2>Our Values : FINEST</h2>
                        <div class="title-border"></div>
                        <p>Revolutionizing Farmers and Food in Nepal through Business Approaches</p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/food_loss.jpg')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">NO FOOD LOSS</h3>
                                <p class="description">
                                    Post harvest fresh produce loss is about 40% in Nepal.
                                    We design the supply chain to reduce the post-harvest fruits and vegetable loss in
                                    Nepal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/innovation.jpg')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">INNOVATION</h3>
                                <p class="description">
                                    We Think that the challenges in fresh produce supply chain and its productivity can
                                    be converted
                                    into opportunities by using innovative digital technology.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/nutrition.jpg')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">NUTRITION</h3>
                                <p class="description">
                                    We aim to make an active contribution to provide nutritious food.
                                    We are dedicated to solve the nutrition problem in Nepal through our product.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/empowerment.png')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">EMPOWERMENT</h3>
                                <p class="description">
                                    With the vision of Revolutionizing farmers and food supply chain in Nepal,
                                    we aim to increase the job opportunities in the agricultural field.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/sustainibility.jpg')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">SUSTAINABILITY</h3>
                                <p class="description">
                                    We provide more than expected opportunities to our farmers for discovering and
                                    learning.
                                    Also, Enhance pleasure to be a farmer with more incomes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 fw600" style="padding:0 5px;">
                    <div class="features-col">
                        <div class="features-box values-box">
                            <div class="values-icon features-icon">
                                {{-- <i class="glyphicon glyphicon-user"></i> --}}
                                <img class="value_img" src="{{asset('cms/images/values/traceability.jpg')}}" alt="">
                            </div>
                            <div class="value features-content">
                                <h3 class="title">TRACEABILITY</h3>
                                <p class="description">
                                    We guarantee the safety of product and ensure the tracking of our product at
                                    every stage like harvest, warehousing, distribution and sales.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Call Start -->
    <section class="call-area jarallax overlay-black">
        <img class="jarallax-img"
             src="{{ isset($home_setting) ? asset('uploads/img/home/'.$home_setting->call_section_image) : '' }}"
             alt="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="call-box call-number">
                        <h1>we are ready to receive your call</h1>
                        <h2><i class="zmdi zmdi-headset-mic"></i>{{ isset($home_setting) ? $home_setting->phone : '' }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Start -->
    @if(isset($services))
        <section class="service-area bg-shape">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2>our Services</h2>
                            <div class="title-border"></div>
                            <p>Facilitate consumers with highly experienced fresh value chain management services.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($services as $service)
                        <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                            <div class="service-col">
                                <div class="service-img">
                                    @if(isset($service->service_image) && !empty($service->service_image) && file_exists(public_path().'/uploads/img/home/services/'.$service->service_image))
                                        <img src="{{ asset('uploads/img/home/services/'.$service->service_image)}}"
                                             alt="">
                                    @endif
                                </div>
                                <div class="service-content">
                                    <h3><a href="#">{{ $service->title }}</a></h3>
                                    <p>{{ $service->summary }}</p>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Counter Start -->
    <section class="counter-area jarallax overlay-black" id="water-animation-two">
        <img class="jarallax-img"
             src="{{ isset($home_setting) ? asset('uploads/img/home/'.$home_setting->counter_section_image) : '' }}"
             alt="">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                    <div class="counter">
                        <div class="counter-content">
                            <span class="count">{{isset($counter_info) ? $counter_info->farmers : 0}}</span>
                        </div>
                        <h3 class="title">Happy Farmers</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                    <div class="counter">
                        <div class="counter-content">
                            <span class="count">{{isset($counter_info) ? $counter_info->clients : 0}}</span>
                        </div>
                        <h3 class="title">Happy Clients</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                    <div class="counter">
                        <div class="counter-content">
                            <span class="count">{{isset($counter_info) ? $counter_info->staffs : 0}}</span>
                        </div>
                        <h3 class="title">our staff</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                    <div class="counter">
                        <div class="counter-content">
                            <span class="count">{{isset($counter_info) ? $counter_info->awards : 0}}</span>
                        </div>
                        <h3 class="title">Win Awards</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Start -->
    @if(isset($team_members))
        <section class="team-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <div class="section-title">
                            <h2>Meet Our Team</h2>
                            <div class="title-border"></div>
                            <p>Our core team comprises of dynamic individuals with passion and dedication.</p>
                        </div>
                    </div>
                    @foreach($team_members as $team_member)
                        <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                            <div class="our-team">
                                <div class="pic">
                                    @if (isset($team_member->member_image) && !empty($team_member->member_image) && file_exists(public_path().'/uploads/img/home/team/'.$team_member->member_image))
                                        <img src="{{ asset('uploads/img/home/team/'.$team_member->member_image) }}"
                                             alt="">
                                    @endif
                                </div>
                                <div class="team-content">
                                    <h3 class="title"><a href="team-single.html">{{$team_member->name}}</a></h3>
                                    <span class="post">{{$team_member->post}}</span>
                                    <ul class="social">
                                        <li><a href="#" class="fa fa-facebook"></a></li>
                                        <li><a href="#" class="fa fa-twitter"></a></li>
                                        <li><a href="#" class="fa fa-skype"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Request Start -->
    <section class="request-area jarallax overlay-black quote" id="contact_us">
        <img class="jarallax-img"
             src="{{ isset($home_setting) ? asset('uploads/img/home/'.$home_setting->quote_background_image) : '' }}"
             alt="">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6">
                    <div class="request-title">
                        <h2>Get a Quote</h2>
                    </div>
                    <form action="{{action('Front\FrontendController@mailRequest')}}" method="post">
                    @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="request-col">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="request-col">
                                    <input type="email" name="email" class="form-control" id="inputEmail3"
                                           placeholder="Email Address">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="request-col">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="request-col">
                                    <input type="text" name="subject" class="form-control" placeholder="Subject">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="request-col">
                                    <input type="text" name="address" class="form-control" placeholder="Address" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="request-col">
                                    <textarea name="message" class="form-control" rows="5" placeholder="Message"></textarea>
                                    <button class="btn btn-default theme-btn" type="submit">Send Now</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="request-col">
                        <img src="{{ isset($home_setting) ? asset('uploads/img/home/'.$home_setting->quote_front_image) : '' }}"
                             alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Start -->
    @if(isset($testimonials) && count($testimonials)>1)
        <section class="testimonial-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2>Testimonial</h2>
                            <div class="title-border"></div>
                            <p>What People Say About Freshktm</p>
                        </div>
                    </div>
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="testimonial-carousel">
                            @foreach ($testimonials as $testimonial_info)
                                <div class="testimonial">
                                    <div class="pic">
                                        <img src="{{asset('uploads/img/home/testimonials/'.$testimonial_info->image)}}"
                                             alt="" class="img-responsive">
                                    </div>
                                    <h3 class="testimonial-title">
                                        {{$testimonial_info->name}}
                                        <small>{{$testimonial_info->post}}</small>
                                    </h3>
                                    <p class="description">{{$testimonial_info->comment}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Blog Start -->
    @if(isset($blogs))
        <section class="blog-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2>Our Blog</h2>
                            <div class="title-border"></div>
                            <p>Know more about fresh produce supply chain</p>
                        </div>
                    </div>
                    @if(isset($blogs))
                        @foreach($blogs as $blog)
                            <div class="col-md-6 col-sm-12">
                                <div class="blog-box">
                                    <div class="blog-img">
                                        <img src="{{ asset('uploads/img/home/blogs/'.$blog->image)}}" alt="">
                                    </div>
                                    <div class="blog-box-content">
                                        <h4>{{$blog->title}}</h4>
                                        <div class="time-date">
                                            <ul>
                                                {{-- <li><i class="fa fa-user" aria-hidden="true"></i> <a href="#">Admin</a></li> --}}
                                                <li><i class="fa fa-calendar" aria-hidden="true"></i> <a
                                                            href="#">{{\Carbon\Carbon::parse($blog->created_at)->format('M d, Y')}}</a>
                                                </li>
                                                {{-- <li><i class="fa fa-comments-o" aria-hidden="true"></i> <a href="#">3 comments</a></li> --}}
                                            </ul>
                                        </div>
                                        <p>{{ $blog->summary }}<a href="{{ route('blog_single', $blog->slug) }}">[Read
                                                More]</a></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Client start -->
    @if(isset($home_setting) && $home_setting->client_images != null)
        <section class="client-area">
            @php
                $client_images= explode(',',$home_setting->client_images);
            @endphp
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="client-carousel">
                            @foreach ($client_images as $client_image)
                                <div class="item">
                                    <a href="#"><img src="{{ asset('uploads/img/home/client/'.$client_image)}}" alt=""></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
@section('scripts')
    @if (session()->has('success'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    icon: 'success',
                    title: "{{Session::get('success')}}",
                    showConfirmButton: false,
                    timer: 4000
                })
            })
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    icon: 'error',
                    title: "{{Session::get('error')}}",
                    showConfirmButton: false,
                    timer: 4000
                })
            })
        </script>
    @endif
@endsection