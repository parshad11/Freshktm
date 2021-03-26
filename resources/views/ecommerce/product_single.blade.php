@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('styles')
    <link rel="stylesheet" href="{{asset('cms/css/shop.css')}}">
@endsection
@section('content')
    <div class="container">
        <div class="single-item-link">
            <ol class="breadcrumb">
                <li><a href="{{ route('front_dashboard') }}">Home</a></li>
                <li><a href="{{ route('shop') }}">Shop</a></li>
                <li class="active">{{ $variation->product->name }}</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-5 item-photo">
                <img class="img img-responsive"
                     src="@foreach($variation->media as $media){{ $media->display_url }}@endforeach" alt="">
            </div>
            <div class="col-sm-7" style="border:0px solid gray">
                <!-- Title & Description -->
                <h3>{{ $variation->product->name}}&nbsp;{{$variation->name != "DUMMY" ? $variation->name : '' }}</h3>

                <!-- Pricing -->
                @if($variation->market_price>0)
                    <h5>Kalimati Price : Rs. {{ number_format($variation->market_price,2) }}</h5>
                @endif
                <h4>Price : Rs. {{ number_format($variation->sell_price_inc_tax,2) }}</h4>

                <!-- Quantity-->


                <div class="section" style="padding-bottom:20px;">
                    <h5 class="title-attr">Order Quantity</h5>
                    <div style="display:flex;">
                        <div class="btn-minus plus-minus"><span class="glyphicon glyphicon-minus"></span></div>
                        <input value="1" class="input_quantity"/>
                        <div class="btn-plus plus-minus"><span class="glyphicon glyphicon-plus"></span></div>
                    </div>
                </div>

                <!-- buttons -->
                <div class="section carts" style="padding-bottom:20px;">
                    <button class="btn btn-success" id="add_to_carts" product_id="{{$variation->id}}">Add to Cart
                    </button>

                    <button class="btn btn-success" id="product_buy_now" product_id="{{$variation->id}}">Buy Now
                    </button>

                </div>
                <h4>Product Description</h4>
                <div class="description">

                    <p>{!! $variation->product->product_description !!}</p>
                </div>
            </div>


        </div>
    </div>


    <div class="container single-2">

        <h2>You May Like</h2>
        <div class="row ">
            @forelse($products as $product)
                @foreach ($product->product_variations as $product_variation)
                    @foreach ($product_variation->variations as $variation)
                        <div class="card col-md-3 col-sm-4 col-xs-12">
                            <div class="product">
                                <div class="img">
                                    <a href="{{route('product_single',$variation->sub_sku)}}">
                                        <img class="img img-responsive lazy_load_image"
                                             data-original="@foreach($variation->media as $media){{ $media->display_url }}@endforeach"
                                             alt="">
                                    </a>
                                </div>
                                <div class="description">
                                    <div class="title"><b><a
                                                    href="{{ route('product_single',$variation->sub_sku) }}">{{ $variation->product->name }}
                                                &nbsp;{{ $variation->name != "DUMMY" ? $variation->name : '' }}</a></b>
                                    </div>
                                    <div class="price">
                                        @if($variation->market_price>0)
                                            <div class="kalimati">
                                                <small>Market Price
                                                    :Rs. {{ number_format($variation->market_price,2) }}</small>
                                            </div>
                                        @endif
                                        {{-- <div class="kalimati"><small>Kalimati Price :Rs. {{ number_format($variation->market_price,2) }}</small></div> --}}

                                        <div class="offer">Price :
                                            Rs.{{ number_format($variation->sell_price_inc_tax,2) }}</div>
                                    </div>
                                    <button class="btn btn-success" id="add_to_carts" product_id="{{$variation->id}}">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endforeach
            @empty
            @endforelse
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('cms/js/shop.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(this).on('click', '#product_buy_now', function (event) {
                event.preventDefault();
                let quantity = $('.input_quantity').val();
                let product_variation_id = $(this).attr('product_id');
                $.ajax({
                    method: 'POST',
                    url: '{{ route('product_buy_now') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        quantity: quantity,
                        variation_id: product_variation_id
                    },
                    success: function (response) {
                        if (response.status == 'error') {
                            showFrontendAlert('warning', response.msg);
                        } else {
                            location.href = "{{ route('product.checkout') }}";
                        }
                    },
                    error: function (response) {
                        if (response.error) {
                            window.location.href = document.location.origin + '/shop/login';
                        }
                    }
                });

            });
        });
    </script>
@endsection