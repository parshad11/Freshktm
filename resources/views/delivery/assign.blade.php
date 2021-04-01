@extends('layouts.app')
@section('title', __('delivery.assign_delivery'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('delivery.assign_delivery')
</section>

<!-- Main content -->
<section class="content">

	@include('layouts.partials.error')

	{!! Form::open(['url' => action('DeliveryController@store'), 'method' => 'post', 'id' => 'assign_delivery_form', 'files' => true ]) !!}
	    @component('components.widget', ['class' => 'box-primary'])
				<div class="row">
                <input type="hidden" id="transaction_id" value="{{ $transaction->id }}" name="transaction_id">
				<div class="col-md-12 " style="display:flex;justify-content: space-between;">
						<div class=" col-sm-4 ">
							<div class="form-group">
								{!! Form::label('delivery_person_id', __('delivery.delivery_person') . ':*') !!}
								<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-user"></i>
						</span>
									{!! Form::select('delivery_person_id',isset($default_delivery_person)?[$default_delivery_person->id=>$default_delivery_person->user->user_name]:[],isset($default_delivery_person)?$default_delivery_person->id:null , ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'),'id' => 'delivery_person_id', 'style' => 'width: 100%;' ]); !!}
								</div>
							</div>
						</div>


						<div class=" col-sm-4 @if(!empty($default_delivery_status)) hide @endif">
							<div class="form-group">
								{!! Form::label('delivery_status', __('delivery.delivery_status') . ':*') !!}
								{!! Form::select('delivery_status', $deliveryStatuses , null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required','style' => 'width: 100%;','id'=>'delivery_status']); !!}
							</div>
						</div>
				</div>

        @if($transaction->type=='sell')
        @php
        $shipping_address=$transaction->contact->shipping_address;
        $shipping_latitude=$transaction->contact->latitude;
        $shipping_longitude=$transaction->contact->longitude;
        $pickup_address=$transaction->location->location_address;
        $pickup_latitude=$transaction->location->latitude;
        $pickup_longitude=$transaction->location->longitude;
        @endphp
        @else
        @php
        $shipping_address=$transaction->location->location_address;
        $shipping_latitude=$transaction->location->latitude;
        $shipping_longitude=$transaction->location->longitude;
        $pickup_address=$transaction->contact->shipping_address;
        $pickup_latitude=$transaction->contact->longitude;
        $pickup_longitude=$transaction->contact->longitude;
        @endphp
        @endif
				<div class="col-md-12" style="display:flex;justify-content: space-between;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('shipping_address', __('lang_v1.shipping_address') . ':') !!}
                                <div class="input-group">
                                <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('shipping_address', $shipping_address, ['class' => 'form-control', 'placeholder' => __('lang_v1.shipping_address')]); !!}
                                </div>
                            </div>
                            <div class="form-group">
                            <span><a target="_blank" href="https://www.mapcoordinates.net/en" class="btn-sm btn-primary">Click here</a> to find latitude and longitude</span>
                            </div>
                        </div>

						<div class=" col-sm-4 ">
							<div class="form-group">
								{!! Form::label('shipping_latitude', __('delivery.shipping_latitude') . ':') !!}
								{!! Form::text('shipping_latitude', $shipping_latitude, ['class' => 'form-control','rows'=>3]); !!}
							</div>
                            <div class="form-group">
								{!! Form::label('shipping_longitude', __('delivery.shipping_longitude') . ':') !!}
								{!! Form::text('shipping_longitude', $shipping_longitude, ['class' => 'form-control','rows'=>3]); !!}
							</div>
						</div>
				</div>


				<div class="col-md-12" style="display:flex;justify-content: space-between;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('pickup_address', __('delivery.pickup_address') . ':') !!}
                                <div class="input-group">
                                <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('pickup_address', $pickup_address, ['class' => 'form-control', 'placeholder' => __('delivery.pickup_address')]); !!}
                                </div>
                            </div>
                            <div class="form-group">
                            <span><a target="_blank" href="https://www.mapcoordinates.net/en" class="btn-sm btn-primary">Click here</a> to find latitude and longitude</span>
                            </div>
                        </div>

						<div class=" col-sm-4 ">
							<div class="form-group">
								{!! Form::label('pickup_latitude', __('delivery.pickup_latitude') . ':') !!}
								{!! Form::text('pickup_latitude', $pickup_latitude, ['class' => 'form-control','rows'=>3]); !!}
							</div>
                            <div class="form-group">
								{!! Form::label('pickup_longitude', __('delivery.pickup_longitude') . ':') !!}
								{!! Form::text('pickup_longitude', $pickup_longitude, ['class' => 'form-control','rows'=>3]); !!}
							</div>
						</div>
				</div>


                <div class="col-md-12" style="display:flex;justify-content: space-between;">

                <div class=" col-sm-4  hide delivered_to_div">
                        <div class="form-group">
                                    {!! Form::label('delivered_to', __('delivery.delivered_to') . ':') !!}
                                    {!! Form::text('delivered_to', null, ['class' => 'form-control']); !!}
                        </div>
                    </div>

                    <div class=" col-sm-4 ">
                        <div class="form-group">
                                    {!! Form::label('special_instructions', __('delivery.special_delivery_instructions') . ':') !!}
                                    {!! Form::textarea('special_delivery_instructions', null, ['class' => 'form-control','rows'=>3]); !!}
                        </div>
                    </div>
                </div>


                    <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" id="save_assign_delivery" class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>
			</div>
		@endcomponent

    {!! Form::close() !!}
</section>
@endsection

@section('javascript')
	<script type="text/javascript">

        $( "#delivery_status" ).change(function() {
        if(this.value == 'delivered'){
            $('div.delivered_to_div').removeClass( "hide" );
        }
        else{
            $('div.delivered_to_div').addClass("hide");

        }
        });

		$(document).ready( function(){
            $('#delivery_person_id').select2({
            ajax: {
                url: '/user/get_delivery_people',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: data,
                    };
                },
            },
            minimumInputLength: 1,
            escapeMarkup: function(m) {
                return m;
            },

            templateResult: function(data) {
                if (!data.id) {
                    return data.text;
                }
                var html = data.text;
                return html;
            },
        })
		});
	</script>

@endsection
