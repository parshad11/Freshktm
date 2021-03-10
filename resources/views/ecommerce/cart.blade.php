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
@endsection
@section('content')

    <!-- all category -->
    <section id="shopping-cart">
        <div class="container box-shadow mt-2 mb">
            <h1>Shopping Cart</h1>
    
            <div class="shopping-cart">
    
                <div class="column-labels">
                    <label class="product-image text-center">Image</label>
                    <label class="product-details">Product</label>
                    <label class="product-price">Price</label>
                    <label class="product-quantity">Quantity</label>
                    <label class="product-removal">Remove</label>
                    <label class="product-line-price">Total</label>
                </div>
    
                <div class="product">
                    <div class="product-image">
                        <img src="https://www.comfylux.com/wp-content/uploads/2018/02/comfy-golf-shoes.jpg">
                    </div>
                    <div class="product-details">
                        <div class="product-title">Nike Flex Form TR Women's Sneaker</div>
                        <p class="product-description"> It has a lightweight, breathable mesh upper with forefoot cables for
                            a locked-down fit.</p>
                    </div>
                    <div class="product-price">12.99</div>
                    <div class="product-quantity">
                        <input type="number"  class="form-control" value="2" min="1">
                    </div>
                    <div class="product-removal">
                        <button class=" btn btn-danger remove-product ">
                           <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="product-line-price">25.98</div>
                </div>
    
                <div class="product">
                    <div class="product-image">
                        <img src="https://barefootpat.org/springblade.jpg">
                    </div>
                    <div class="product-details">
                        <div class="product-title">ULTRABOOST UNCAGED SHOES</div>
                        <p class="product-description">Born from running culture, these men's shoes deliver the freedom of a
                            cage-free design</p>
                    </div>
                    <div class="product-price">45.99</div>
                    <div class="product-quantity">
                        <input type="number"  class="form-control" value="1" min="1">
                    </div>
                    <div class="product-removal">
                        <button class=" btn btn-danger remove-product">
                           <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="product-line-price">45.99</div>
                </div>
    
                <div class="totals">
                    <div class="totals-item">
                        <label>Subtotal</label>
                        <div class="totals-value" id="cart-subtotal">71.97</div>
                    </div>
                    <div class="totals-item">
                        <label>Tax (13%)</label>
                        <div class="totals-value" id="cart-tax">3.60</div>
                    </div>
                    <div class="totals-item">
                        <label>Shipping</label>
                        <div class="totals-value" id="cart-shipping">15.00</div>
                    </div>
                    <div class="totals-item totals-item-total">
                        <label>Grand Total</label>
                        <div class="totals-value" id="cart-total">90.57</div>
                    </div>
                </div>
    
                    <a class="uk-button view-cart" href="index.html"> <span uk-icon="icon:chevron-left"></span>Continue Shopping</a>
                    <a class="uk-button checkout" href="checkoutpage.html">Checkout</a>
                <div class="clearfix"></div>
    
    
    
    
            </div>
        </div>
    </section>
    

@endsection