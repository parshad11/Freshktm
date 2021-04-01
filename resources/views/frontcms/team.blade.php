@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('scripts')
@endsection
@section('content')
<!-- Page heading Start -->
<section class="page-heading-area overlay-black" id="water-animation">
    @isset($about_info)
    <img class="jarallax-img" src="{{asset('uploads/img/home/about/'.$about_info->banner_image)}}" alt="">
    @endisset
</section>

<!-- Team Start -->
<section class="team-area">
    <div class="container">
        <div class="row">
            @if(count($teams) > 0)
            @foreach($teams as $team)
            <div class="col-md-3 col-sm-6 col-xs-6 fw600">
                <div class="our-team">
                    <div class="pic">
                        <img src="{{asset('uploads/img/home/team/'.$team->member_image)}}" alt="">
                    </div>
                    <div class="team-content">
                        <h3 class="title">{{ $team->name }}</h3>
                        <span class="post">{{$team->post}}</span>
                        <ul class="social">
                            <li><a href="#" class="fa fa-facebook"></a></li>
                            <li><a href="#" class="fa fa-twitter"></a></li>
                            <li><a href="#" class="fa fa-skype"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
@endsection