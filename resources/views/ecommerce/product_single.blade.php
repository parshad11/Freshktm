@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Market And Agro ecommorce platform' )
@section('styles')
 
<link rel="stylesheet" href="{{ asset('/ecom/app.min.css') }}">
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.16/js/uikit.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.16/js/uikit-icons.min.js"></script>

<script src="https://unpkg.com/ionicons@4.5.5/dist/ionicons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.8/metisMenu.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="{{ asset('/ecom/app.min.js') }}"></script>
<script>
$(document).ready(function(){
    // console.log(qty);
    // function addToCart(){
    //     console.log('testing');
    $('.view-cart').click(function(e){
        e.preventDefault();
        var qty = $('#prdtQty').val();
        var id = $(this).data('id');
        // var qty = $(this).attr('qty');
        var price = $(this).attr('price');
       
        $.ajax({
            url: '/addtocart',
            method: 'get',
            data:{
                product_id:id,
                quantity:qty,
                price:price
            },
            success:function(response){
                echo (response);
            },
            error:function(response){

            }
        });
    });
    // }
}); 
</script>
@endsection
@section('content')

<section id="single_page">
    <div class="container">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="large_device_img">
                    <div class="col-sm-2 col-md-2 col-lg-2 thumb_image_div">
                        <div id="design_gallery">
                            <div class="thumb_images" id="gal1">

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 col-md-9 col-lg-10 large_img_div" style="padding: 0px;">
                        <div class="large_img">
                            <img src="{{asset('uploads/img/'.$product->image)}}"
                                 alt="acamera">
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="product_short_info">
                    <div class="heading">
                        <h3>{{ $product->name }}</h3>
                    </div>
                    <div class="product_short_info-price">

                        <span class="actual-price">
                            {{$product->variations[0]->default_sell_price}}
                        </span>
                        {{-- <span class="discount-price text-muted">
                            Rs.12000
                        </span> --}}
                    </div>
                    <hr>
                    <div class="product_short_info-desc">
                        {!! $product->product_description !!}
                    </div>
                    <div class="product_short_info-quantity">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="prdtQty" max="{{ number_format ($product->current_stock) }}" min="1" placeholder="0" >
                        <h5>available stock( {{ number_format ($product->current_stock)  }} )</h5>
                    </div>
                    {{-- <div class="product_short_info-color">
                        <div class="heading">
                            <h4>Colors</h4>
                        </div>
                        <form action="">
                            <div class="radio-item">
                                <input type="radio" id="ritema" name="ritem" value="ropt1">
                                <label for="ritema" style="background-color: red"></label>
                            </div>

                            <div class="radio-item">
                                <input type="radio" id="ritemb" name="ritem" value="ropt2">
                                <label for="ritemb" style="background-color: black"></label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="ritemc" name="ritem" value="ropt3">
                                <label for="ritemc" style="background-color: blue"></label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="ritemd" name="ritem" value="ropt4">
                                <label for="ritemd" style="background-color: yellow"></label>
                            </div>

                        </form>
                    </div> --}}

                    <div class="product_short_info-buttons">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn_add-to-cart">
                               <button class="uk-button view-cart" data-id="{{$product->id}}" qty="8" price="{{$product->variations[0]->default_sell_price}}">Add to Cart</button>
                            </div>
                            <div class=" btn_add-to-wishlist">
                                <button class=" uk-button checkout">Add To Wishlist</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="product_short_info-social_media">
                        <ul class="liststyle--none social-icons d-flex align-items-center flex-wrap">
                            <li class="social-icon">
                                Follow Us On:
                            </li>
                            <li class="social-icon ">
                                <a class="facebook fab fa-facebook-square" href="" target="_blank">

                                </a>
                            </li>
                            <li class="social-icon">
                                <a class="twitter fab fa-twitter-square" href="" target="_blank">

                                </a>
                            </li>
                            <li class="social-icon ">
                                <a class="google-plus fab fa-google-plus-square" href="" target="_blank">

                                </a>
                            </li>
                            <li class="social-icon ">
                                <a class="linked-in fab fa-linkedin" href="" target="_blank">

                                </a>
                            </li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="single-tour-tabs ">
                <ul class="nav nav-tabs " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab-description" role="tab" data-toggle="tab"
                           aria-expanded="true">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#tab-information" role="tab" data-toggle="tab"
                           aria-expanded="false">Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#tab-reviews" role="tab" data-toggle="tab"
                           aria-expanded="false">Reviews (1)</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade show active description " id="tab-description">
                        <h3>Product Description</h3>

                    </div>
                    <div role="tabpanel" class="tab-pane fade itinerary_tab " id="tab-information">

                    </div>

                    <div role="tabpanel" class="tab-pane fade reviews " id="tab-reviews">
                        <div class="">

                            <form action="">
                                <div class="form-group">
                                    <label for="">
                                        Name
                                    </label>
                                    <input type="text" placeholder="Your Name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">
                                        Email
                                    </label>
                                    <input type="email" placeholder="Your Name" class="form-control">
                                </div>

                            </form>
                            <div class="form-group">
                                <span class="review--heading">Add review</span>
                                <fieldset class="rating">
                                    <input type="radio" id="star5" name="rating" value="5"><label class="full"
                                                                                                  for="star5"
                                                                                                  title="Awesome - 5 stars"></label>
                                    <input type="radio" id="star4" name="rating" value="4"><label class="full"
                                                                                                  for="star4"
                                                                                                  title="Pretty good - 4 stars"></label>

                                    <input type="radio" id="star3" name="rating" value="3"><label class="full"
                                                                                                  for="star3"
                                                                                                  title="Meh - 3 stars"></label>

                                    <input type="radio" id="star2" name="rating" value="2"><label class="full"
                                                                                                  for="star2"
                                                                                                  title="Kinda bad - 2 stars"></label>

                                    <input type="radio" id="star1" name="rating" value="1"><label class="full"
                                                                                                  for="star1"
                                                                                                  title="Sucks big time - 1 star"></label>

                                </fieldset>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                        <form action="" method="post" class="review-form">

                            <label for="comment">write something</label>
                            <textarea type="text" class="form-control" id="comment"
                                      placeholder="write something" rows="3" cols="100">
                    </textarea>
                            <button class="btn-view_more float-right mt-1 " href=""> comment</button>
                            <div class="clearfix"></div>
                        </form>
                        <div class="clearfix"></div>
                        <p class="review-user">4.1 average based on 254 reviews.</p>
                        <hr style="border:3px solid #f1f1f1; width:70%">
                        <div class="row review-rating">
                            <div class="side">
                                <div>5 star</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-5"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div>150</div>
                            </div>
                            <div class="side">
                                <div>4 star</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-4"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div>63</div>
                            </div>
                            <div class="side">
                                <div>3 star</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-3"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div>15</div>
                            </div>
                            <div class="side">
                                <div>2 star</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-2"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div>6</div>
                            </div>
                            <div class="side">
                                <div>1 star</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-1"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div>20</div>
                            </div>
                        </div>
                        <div class="review-container">
                            <h3 class="review-title">Reviews</h3>
                            <article class="reviews" style="display: block;">
                                <figure class="user-image">
                                    <img src="https://yt3.ggpht.com/-lASduCKYbGI/AAAAAAAAAAI/AAAAAAAAAAA/bCSffOUUASk/s48-c-k-no-mo-rj-c0xffffff/photo.jpg"
                                         alt="">
                                </figure>
                                <div class="review-right">
                                    <span class="username"> Jhon Deo</span>&nbsp;<span class="published">2018/2/9</span>&nbsp;&nbsp;<span>stars</span>
                                    <p>lorem ipsum sumet is the inlu one that is very popular in webs designing
                                        to place a dumy text</p>
                                </div>
                                <div class="clearfix"></div>
                            </article>
                            <article class="reviews" style="display: block;">
                                <figure class="user-image">
                                    <img src="https://yt3.ggpht.com/-lASduCKYbGI/AAAAAAAAAAI/AAAAAAAAAAA/bCSffOUUASk/s48-c-k-no-mo-rj-c0xffffff/photo.jpg"
                                         alt="">
                                </figure>
                                <div class="review-right">
                                    <span class="username"> Jhon Deo</span>&nbsp;<span class="published">2018/2/9</span>&nbsp;&nbsp;<span>stars</span>
                                    <p>lorem ipsum sumet is the inlu one that is very popular in webs designing
                                        to place a dumy text</p>
                                </div>
                                <div class="clearfix"></div>
                            </article>
                            <article class="reviews" style="display: block;">
                                <figure class="user-image">
                                    <img src="https://yt3.ggpht.com/-lASduCKYbGI/AAAAAAAAAAI/AAAAAAAAAAA/bCSffOUUASk/s48-c-k-no-mo-rj-c0xffffff/photo.jpg"
                                         alt="">
                                </figure>
                                <div class="review-right">
                                    <span class="username"> Jhon Deo</span>&nbsp;<span class="published">2018/2/9</span>&nbsp;&nbsp;<span>stars</span>
                                    <p>lorem ipsum sumet is the inlu one that is very popular in webs designing
                                        to place a dumy text</p>
                                </div>
                                <div class="clearfix"></div>
                            </article>
                            <article class="reviews">
                                <figure class="user-image">
                                    <img src="https://yt3.ggpht.com/-lASduCKYbGI/AAAAAAAAAAI/AAAAAAAAAAA/bCSffOUUASk/s48-c-k-no-mo-rj-c0xffffff/photo.jpg"
                                         alt="">
                                </figure>
                                <div class="review-right">
                                    <span class="username"> Jhon Deo</span>&nbsp;<span class="published">2018/2/9</span>&nbsp;&nbsp;<span>stars</span>
                                    <p>lorem ipsum sumet is the inlu one that is very popular in webs designing
                                        to place a dumy text</p>
                                </div>
                                <div class="clearfix"></div>
                            </article>
                            <article class="reviews">
                                <figure class="user-image">
                                    <img src="https://yt3.ggpht.com/-lASduCKYbGI/AAAAAAAAAAI/AAAAAAAAAAA/bCSffOUUASk/s48-c-k-no-mo-rj-c0xffffff/photo.jpg"
                                         alt="">
                                </figure>
                                <div class="review-right">
                                    <span class="username"> Jhon Deo</span>&nbsp;<span class="published">2018/2/9</span>&nbsp;&nbsp;<span>stars</span>
                                    <p>lorem ipsum sumet is the inlu one that is very popular in webs designing
                                        to place a dumy text</p>
                                </div>
                                <div class="clearfix"></div>
                            </article>


                            <button class="btn show-more center"> show more</button>
                            <div class="clearfix"></div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>
 @endsection
