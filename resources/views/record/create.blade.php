@extends('layouts.app')
@section('title',  __('contact.add_new_supplier_record'))

@section('content')
    <style type="text/css">


    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('contact.add_new_supplier_record')</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @include('layouts.partials.error')
        {!! Form::open(['url' => action('RecordController@store'), 'method' => 'post', 'id' => 'add_supplier_record_form' ]) !!}
        @component('components.widget', ['class' => 'box-primary'])
            <div class="box-body">
                <div class="row">
                    @if(count($business_locations) == 1)
                        @php
                            $default_location = current(array_keys($business_locations->toArray()));
                        @endphp
                    @else
                        @php
                            $default_location = null;
                        @endphp
                    @endif
                    <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('business_location_id', __('purchase.business_location').':*') !!}
                        @show_tooltip(__('tooltip.supplier_record_location'))
                        {!! Form::select('business_location_id',[], $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required'], $bl_attributes); !!}
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('supplier_id', __('purchase.supplier') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('contact_id',[], null, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'supplier_id']); !!}
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('item', __('contact.item_name').':*') !!}
                            {!! Form::text('item', null, ['class' => 'form-control']); !!}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('quantity', __('contact.quantity').':*') !!}
                            {!! Form::text('quantity', null, ['class' => 'form-control']); !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                            <div class="input-group">
                                {!! Form::select('unit_id', $units,session('business.default_unit'), ['class' => 'form-control select2', 'required']); !!}
                                <span class="input-group-btn">
                            <button type="button" @if(!auth()->user()->can('unit.create')) disabled
                                    @endif class="btn btn-default bg-white btn-flat btn-modal"
                                    data-href="{{action('UnitController@create', ['quick_add' => true])}}"
                                    title="@lang('unit.add_unit')" data-container=".view_modal"><i
                                        class="fa fa-plus-circle text-primary fa-lg"></i></button>
                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('date', __('contact.expected_date') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('expected_collection_date', null, ['class' => 'form-control','id'=>'datetimepicker', 'required']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('supplier_location', __('contact.supplier_location') . ':*') !!}
                            {!! Form::text('location', null, ['class' => 'form-control','id'=>'supplier_location']); !!}
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        @endcomponent
        {!! Form::close() !!}

    </section>
    <!-- /.content -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true,'delivery_people=>$delivery_people'])
</div>
@endsection
@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function (e) {
            $('#datetimepicker').datepicker({
                useCurrent: false,
                minDate: moment()
             });

             $('#business_location_id').select2({
			ajax: {
				url: '/business/get_locations',
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term, // search term
						page: params.page,
					};
				},
				processResults: function(data) {
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
			}).on('select2:select', function (e) {
				var data = e.params.data;
			});

        });
    </script>
@endsection