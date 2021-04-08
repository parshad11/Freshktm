<div class="panel-body" id="cart_item_detail">
    @if(isset($cart_items) && count($cart_items)>0)
        @php
            $total_sum = 0;
        @endphp
        @foreach ($cart_items as $key=>$item)
            @php
                $total_sum = $total_sum + $item['total_price'];
            @endphp
            <div class="row productInCart">
                <div class="col-xs-2">
                    <img class="img img-responsive lazy_load_image" data-original="@foreach($item->variation->media as $media){{ $media->display_url }}@endforeach" alt="">
                    {{--<img class="img-responsive" src="@if($item->variation->name != "DUMMY")
                    @foreach($item->variation->media as $media)
                     {!! $media->thumbnail([300, 300]) !!}
                    {{$media->display_url}}
                    @endforeach
                    @else
                    {{$item->variation->product->image_url}}
                    @endif" alt="">--}}
                </div>
                <div class="col-xs-4">
                    <h4 class="product-name"><strong>{{$item->variation->product->name}}
                            &nbsp;{{$item->variation->name != "DUMMY" ? $item->variation->name : ''}}</strong></h4>
                </div>
                <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-3 text-right">
                            <h6><strong>{{ number_format($item->variation->sell_price_inc_tax,2) }} <span
                                            class="text-muted">x</span></strong>
                            </h6>
                        </div>
                        <div class="col-xs-2">
                            <input type="text" readonly class="form-control input-sm" value="{{$item->quantity}}">
                        </div>
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-link btn-xs deleteCartItem" data-id="{{$item->id}}">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>

                        <div class="col-xs-4">
                            <strong>{{ number_format($item->total_price,2) }}&nbsp;Rs</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-xs-12 text-right">
                <h4 class="" style="margin-top:2%;">Total <strong>Rs : {{ number_format($total_sum,2) }}</strong></h4>
            </div>
        </div>
    @else
        <h4>No items left in cart</h4>
    @endif
</div>
