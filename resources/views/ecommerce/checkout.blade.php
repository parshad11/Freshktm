@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('styles')
    <link rel="stylesheet" href="{{asset('cms/css/shop.css')}}">
@endsection
@section('content')
<div class="container checkout">
    <div class="row" style="padding-top:25px; padding-bottom:25px;">
      <div class="col-md-12">
        <div id="mainContentWrapper">
          <div class="col-md-8 col-md-offset-2">
            <h2 style="text-align: center;">
              Review Your Order &amp; Complete Checkout
            </h2>
            <hr>
            <a href="{{ route('shop') }}" class="btn btn-info" style="width: 100%;">Add More Products and Services</a>
            <hr>
            <div class="shopping_cart">
              <form class="form-horizontal" role="form" action="{{action('Front\ShopController@store')}}" method="post" id="payment-form">
              @csrf
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">Review
                          Your Order</a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="height: auto;">
                      <div class="panel-body">
                        <div class="items">
                          <div class="col-md-12">
                            <table class="table table-striped">

                              <tbody>
                                  @forelse ($cart_items as $item)
                                <tr class="table-column">
                                    <td>
                                      <ul>
                                        <li><b>{{$item->variation->product->name}}&nbsp;{{$item->variation->name != 'DUMMY' ? $item->variation->name : '' }}</b></li>
                                        <li>Quantity : <span>{{$item->quantity}}</span></li>
                                        <li>Rate : <span>Rs: {{ number_format($item->variation->sell_price_inc_tax,2) }}</span></li>
    
                                      </ul>
                                    </td>
    
                                    <td>
                                      <b>Rs.{{ number_format($item->total_price,2) }}</b>
                                    </td>
                                    </tr>
                                  @empty
                                      
                                  @endforelse
                            </tbody></table>
                          </div>
                          <div class="col-md-12">
                            <hr>
                            <div class="order-total text-right">
                              <h4>Order Total</h3>
                              <h5><span style="color:green;">Rs : {{ number_format($total_price,2) }}</span></h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading" style="background: none; border: none;">
                    <h4 class="panel-title">
                      <div style="text-align: center; width:100%;"><a style="width: 100%; display: none;" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="btn btn-success" onclick="$(this).fadeOut(); $('#payInfo').fadeIn();" aria-expanded="true">Continue
                          to Billing Information»</a></div>
                    </h4>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true">Contact
                        and Billing Information</a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse in" aria-expanded="true" style="">
                    <div class="panel-body">

                      <table class="table table-striped" style="font-weight: bold;">
                        <tbody>
                        <tr class="table-column">
                          <td style="width: 175px;">
                            <label for="id_address_line_1">Shipping Address:</label>
                          </td>
                          <td>
                            <input class="form-control" id="id_address_line_1" name="shipping_address" required="required" type="text" value={{$user->shipping_address}}>
                          </td>
                        </tr>
                      </tbody></table>
                    </div>
                    <input type="hidden" name="cart_items" id="cart_items" value="{{$cart_items}}">
                    <input type="hidden" name="total_price" id="total_price" value="{{$total_price}}">
                    <div class="panel-heading" style="background: none; border: none;">
                      <h4 class="panel-title">
                        <button type="submit" id="submit_order_form" class="btn btn-primary pull-right btn-flat">Place Order</button>
                        </div>
                        </div>
                      </h4>
                    </div>
                  </div>

                </div>

            </form></div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
    <script src="{{asset('cms/js/shop.js')}}"></script>
@endsection