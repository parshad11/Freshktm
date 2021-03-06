@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Market And Agro ecommerce platform' )
@section('scripts')
@endsection
@section('content')
<!-- Page heading Start -->
<section class="page-heading-area overlay-black" id="water-animation">
    @isset($about_info)
    <img class="jarallax-img"  src="{{asset('uploads/img/home/about/'.$about_info->banner_image)}}" alt="">
{{--    <img class="jarallax-img"  src="{{asset('cms/images/bg/banner.jpg')}}" alt="">--}}
    @endisset
</section>

<!-- Faq Start -->
<section class="faq-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="my-faq-col">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        @if(isset($faq) && $faq->faqs != null )
                        @php
                            $faqs = json_decode($faq->faqs, true);
                            $count = count($faqs);
                            $first = array_key_first($faqs);
                        @endphp
                        @foreach($faqs as $key=>$value)
                        @php
                            $str=str_replace(' ', '', $key);
                        @endphp
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading{{$str}}">
                                <h4 class="panel-title">
                                    <a class="{{ ($first!=$key) ? 'collapsed' : ''}}" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$str}}" aria-expanded="true" aria-controls="collapse{{$str}}">
                                        {!! $key !!}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse{{$str}}" class="panel-collapse collapse {{ ($first==$key) ? 'in' : ''}}" role="tabpanel" aria-labelledby="heading{{$str}}">
                                <div class="panel-body">
                                    <p>{!! $value !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection