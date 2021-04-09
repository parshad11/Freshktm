@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('styles')
    <link rel="stylesheet" href="{{asset('cms/css/shop.css')}}">
@endsection
@section('content')
    <!-- SHOP SECTION -->
    <div class="shop">

        <!-- OVERLAY -->
        @if(Request::segment(2)!='category')
            @isset($banner)
                <div class="banner">
                    <div class="img img-responsive"><img
                                data-original="{{ asset('uploads/shop').'/'.$banner->file_name }}"
                                class="lazy_load_image" alt="Freshktm shop banner image"></div>
                </div>

        @endisset
    @endif

    <!-- CONTENT -->
        <div class="content container" id="content">
            @if(Request::segment(2)=='category')
                <ol class="breadcrumb">
                    <li><a href="{{ route('front_dashboard') }}">Home</a></li>
                    <li><a href="{{ route('shop') }}    ">Shop</a></li>
                    <li class="active">{{$category->name}}</li>
                </ol>
            @endif

            <div class="row">
                <div class=" col-md-3 left">

                    @isset($catalogues)
                        <div class="download">
                            <div class="title"> Catalogue</div>

                            <div class="download-options">
                                @foreach ($catalogues as $key => $catalogue )
                                    <a href="{{route('downloadfile',$catalogue->id)}}">
                                        <button>Catalogue {{$key+1}}&nbsp;<i class="fa fa-download"
                                                                             aria-hidden="true"></i></button>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    @endisset
                    @if (isset($special_category))
                        <div class="category">
                            <div class="title"> SPECIAL SALE</div>

                            <ul class="category-list">

                                @foreach ($special_category->sub_categories as $sub_cat)
                                    <li>
                                        <a href="{{route('product_subcategory',[$special_category->slug,$sub_cat->slug])}}">{{$sub_cat->name}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endif

                    @if (isset($categories))
                        <div class="category">
                            <div class="title"> Categories</div>

                            <ul class="category-list">
                                <li class="main">
                                    <span style="display: contents;"><a href="{{route('shop')}}">All Category </a>
                                        &nbsp;<i class="fa fa-chevron-right" style="margin:auto 0 auto auto;"
                                                 aria-hidden="true" style="float: right; margin:auto"></i></span>

                                </li>
                                @foreach ($categories as $category)
                                    <li class="main">
                                        <span style="display: contents;"><a
                                                    href="{{route('product_category',$category->slug)}}">{{$category->name}} </a>
                                        &nbsp;<i class="fa fa-chevron-right" style="margin:auto 0 auto auto;"
                                                 aria-hidden="true" style="float: right; margin:auto"></i></span>
                                        @if($category->sub_categories!= null)
                                            <ul class="sub">
                                                @foreach ($category->sub_categories as $sub_category)

                                                    <li>
                                                        <a href="{{route('product_subcategory',[$category->slug,$sub_category->slug])}}">{{$sub_category->name}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                    </li>

                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-md-9 right">

                    <!-- SEARCH BOX -->
                    <div class="search-box">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" id="searchTextLg"
                                   placeholder="Search ...."
                                   aria-describedby="basic-addon2">

                            <span class="input-group-addon btn btn-success" id="basic-addon2">Search &nbsp;<i
                                        class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                    </div>

                    <!-- PRODUCTS -->
                    <div class="row" style="display:flex;flex-wrap: wrap;">

                        @forelse($products as $product)
                            @foreach ($product->product_variations as $product_variation)
                                @foreach ($product_variation->variations as $variation)
                                    <div class="card col-md-4 col-sm-4 col-xs-6">
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
                                                                href="{{route('product_single',$variation->sub_sku)}}">{{$variation->product->name}}
                                                            &nbsp;{{$variation->name != "DUMMY" ? $variation->name : ''}}</a></b>
                                                </div>
                                                <div class="price">

                                                    @if($variation->market_price>0)
                                                        <div class="kalimati"><small>Market Price
                                                                :Rs. {{ number_format($variation->market_price,2) }}</small>
                                                        </div>
                                                    @endif

                                                    <div class="offer">Price :
                                                        Rs.{{ number_format($variation->sell_price_inc_tax,2) }}
                                                        /{{$variation->product->unit->short_name}}</div>
                                                </div>
                                                <button class="btn btn-success" id="add_to_carts"
                                                        product_id="{{$variation->id}}">Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @endforeach
                        @empty
                            <div class="card col-lg-12">
                                <div class="row">
                                    <div class="text-center" style="padding: 50px ">
                                        <h1>No Product Found</h1>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    {!! $products->links('frontcms.pagination') !!}
                </div>
            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{asset('cms/js/shop.js')}}"></script>
    @if (session()->has('success'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Order Added Successfully',
                    showConfirmButton: false,
                    timer: 5000
                })
            })
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "{{Session::get('error')}}",
                    showConfirmButton: false,
                    timer: 3000
                })
            })
        </script>
    @endif
    <script>
        $(function () {
            autocomplete('#searchTextLg', {}, {
                source: function (request, response) {
                    $.ajax({
                        url: '{{route('autocomplete.search')}}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            query: $('#searchTextLg').val(),
                        },
                        success: function (data) {
                            response($.map(data, function (obj) {

                                if (obj.variation_name !== "DUMMY") {
                                    return {
                                        name: obj.name + ' ' + obj.variation_name,
                                        slug: obj.sub_sku,
                                    };
                                }
                                return {
                                    name: obj.name,
                                    slug: obj.sub_sku,
                                };
                            }));
                        }
                    });
                },
                displayKey: 'name',
                templates: {
                    // header: '<div class="aa-suggestions-category">Products</div>',
                    suggestion: function (suggestion) {
                        return '<a href="{{ url('/') }}/shop/product/' + suggestion.slug + '">' + suggestion.name +
                            '</a>';
                    }
                }

            });
        });
    </script>
@endsection