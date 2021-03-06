@extends('layouts.app')
@section('title', __( 'delivery.delivery_transaction'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
        <h1>@lang( 'delivery.delivery_transaction' )
        </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        @include('delivery.partials.transaction_list_filters')
    @endcomponent
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_transactions_for_delivery')])
        @if(auth()->user()->can('purchase.view') ||  auth()->user()->can('view_own_purchase') ||auth()->user()->can('direct_sell.access')||auth()->user()->can('sell.view')||auth()->user()->can('view_own_sell_only'))
            <table class="table table-bordered table-striped ajax_view" id="delivery_assign_table">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('lang_v1.transaction_id')</th>
                        <th>@lang('lang_v1.type')</th>
                        <th>@lang('messages.date')</th>
                        <th>@lang('sale.customer_name')</th>
                        <th>@lang('sale.location')</th>
                        <th>@lang('delivery.shipping_details')</th>
                        <th>@lang('lang_v1.added_by')</th>
                        <th>@lang('lang_v1.assign_status')</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        @endif
    @endcomponent
</section>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->

@stop

@section('javascript')
<script type="text/javascript">
$(document).ready( function(){
    //Date range as a button
    $('#transaction_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#transaction_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            delivery_assign_table.ajax.reload();
        }
    );
    $('#transaction_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#transaction_list_filter_date_range').val('');
        delivery_assign_table.ajax.reload();
    });

    delivery_assign_table = $('#delivery_assign_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[1, 'desc']],
        "ajax": {
            "url": "/delivery-transaction",
            "data": function ( d ) {
                if($('#transaction_list_filter_date_range').val()) {
                    var start = $('#transaction_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#transaction_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }


                d.location_id = $('#transaction_list_filter_location_id').val();
                d.assign_delivery_status = $('#transaction_list_filter_assign_status').val();
                d.customer_id = $('#transaction_list_filter_customer_id').val();
                d.created_by = $('#created_by').val();



                d = __datatable_ajax_callback(d);
            }
        },
        scrollY:        "75vh",
        scrollX:        true,
        scrollCollapse: true,
        columns: [
            { data: 'action', name: 'action', orderable: false, "searchable": false},
            { data: 'id', name: 'id'},
            { data: 'type', name: 'type'},
            { data: 'transaction_date', name: 'transaction_date'  },
            { data: 'name', name: 'contacts.name'},
            { data: 'business_location', name: 'bl.name'},
            { data: 'shipping_details', name: 'shipping_details'},
            { data: 'added_by', name: 'u.first_name'},
            { data: 'assign_delivery_status', name: 'assign_delivery_status'},

        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#delivery_assign_table'));
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(6)').attr('class', 'clickable_td');
        }
    });

    $(document).on('change', '#transaction_list_filter_location_id,#transaction_list_filter_customer_id,#created_by,#transaction_list_filter_assign_status',  function() {
        delivery_assign_table.ajax.reload();
    });

});
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
